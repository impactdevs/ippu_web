<?php

namespace App\Http\Controllers;

use App\Jobs\DownloadBulkCPDCertificatesJob;
use App\Jobs\SendBulkCpdEmailJob;
use App\Mail\CertificateMail;
use App\Models\Attendence;
use App\Models\Cpd;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Symfony\Component\Mailer\Exception\TransportException;
use ZipArchive;

class CpdsController extends Controller
{
    public function index()
    {
        $cpds = Cpd::all();

        return view('members.cpds.index', compact('cpds'));
    }

    public function upcoming()
    {
        $cpds = Cpd::where('start_date', '>=', date('Y-m-d'))->get();

        return view('members.cpds.index', compact('cpds'));
    }

    public function attend($id = '')
    {

        $event = Cpd::find($id);
        return view('members.cpds.confirmation', compact('event'));

        // try{
        //     \DB::beginTransaction();
        //     $attendence = new Attendence;
        //     $attendence->user_id = \Auth::user()->id;
        //     $attendence->cpd_id = $id;
        //     $attendence->type = "CPD";
        //     $attendence->status = "Pending";
        //     $attendence->save();

        //     \DB::commit();

        //     return redirect()->back()->with('success','CPD has been recorded!');
        // }catch(\Throwable $e){
        //     return redirect()->back()->with('error',$e->getMessage());
        // }
    }

    public function redirect_url()
    {
        $cpd_id = request()->input('cpd_id');

        try {
            $register_attendance = $this->confirm_attendence(request());
            if ($register_attendance) {
                //redirect to event details page
                return redirect()->route('cpd_details', $cpd_id)->with('success', 'booking successful!');
            } else {
                return view('members.dashboard')->with('error', 'registration failed!');
            }

        } catch (TransportException $exception) {
            return view('members.dashboard')->with('error', $exception->getMessage());
        }
    }

    public function pay($id = '')
    {
        //  dd("pay");
        try {
            $client = new Client();
            $cpd = Cpd::find($id);
            $amount = request()->input('amount') ?? $cpd->normal_rate;
            //url('redirect_url_cpds') . '?cpd_id=' . $cpd->id

            $response = $client->post('https://api.flutterwave.com/v3/payments', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('FLW_SECRET_KEY'),
                ],
                'json' => [
                    'tx_ref' => Str::uuid(),
                    'amount' => $amount,
                    'currency' => 'UGX',
                    // 'redirect_url' => 'http://localhost:8000/redirect_url_cpds?cpd_id=' . $cpd->id . '&amount=' . $amount,
                    'redirect_url' => url('redirect_url_cpds') . '?cpd_id=' . $cpd->id.'&amount='.$amount,
                    'meta' => [
                        'consumer_id' => auth()->user()->id,
                        "full_name" => auth()->user()->name,
                        "email" => auth()->user()->email,
                        "being_payment_for" => "Attendance of CPD",
                        "cpd_id" => $cpd->id,
                        "cpd_topic" => $cpd->topic,
                        "flw_app_id" => env('FLW_APP_ID'),
                    ],
                    'customer' => [
                        'email' => auth()->user()->email,
                        'phonenumber' => auth()->user()->phone_no,
                        'name' => auth()->user()->name,
                    ],
                    'customizations' => [
                        'title' => 'IPP Membership APP',
                        'logo' => 'https://ippu.or.ug/wp-content/uploads/2020/03/cropped-Logo-192x192.png',
                    ],
                ],
            ]);

            // dd($response);

            $responseBody = json_decode($response->getBody(), true);
            //check if the request was successful
            if ($responseBody['status'] == 'success') {
                // return redirect()->away($responseBody['data']['link']);
                return response()->json(['success' => true, 'data' => $responseBody['data']['link']]);
            } else {
                return redirect()->back()->with('error', 'Payment request failed!');
            }
        } catch (RequestException $e) {
            dd($e);
            if ($e->hasResponse()) {
                $responseBody = json_decode($e->getResponse()->getBody(), true);
                return redirect()->back()->with('error', $responseBody['message']);
            } else {
                return redirect()->back()->with('error', 'Payment request failed!');
            }
        }
    }

    public function confirm_attendence(Request $request)
    {
        try {
            // Check if the attendance record already exists for the current user and event
            $attendance = Attendence::where('user_id', \Auth::user()->id)
                ->where('cpd_id', $request->cpd_id)
                ->first();

            if ($attendance) {
                // If record exists, update the booking fee by adding the new amount to the existing one
                $attendance->booking_fee += $request->amount; // Add the new booking fee to the existing one
                $attendance->save();

                return redirect()->back()->with('success', 'Attendance booking fee updated!');
            } else {
                $attendence = new Attendence;
                $attendence->user_id = \Auth::user()->id;
                $attendence->cpd_id = $request->cpd_id;
                $attendence->type = "CPD";
                $attendence->status = "Pending";
                $attendence->booking_fee = $request->amount;

                $attendence->save();
            }

            return redirect()->back()->with('success', 'CPD has been recorded!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function attended()
    {
        $cpds = Cpd::whereHas('attended')->get();

        return view('members.cpds.index', compact('cpds'));
    }

    public function details($id)
    {
        $event = Cpd::find($id);

        return view('members.cpds.details', compact('event'));
    }

    public function certificate($event)
    {
        $event = Cpd::find($event);

        return view('members.cpds.certificate', compact('event'));
    }

    public function generate_certificate($event)
    {
        $manager = new ImageManager(new Driver());
        //read the image from the public folder
        $image = $manager->read(public_path('images/cpd-certificate-template.jpg'));

        $user = auth()->user();

        $event = Cpd::find($event);

        $image->text($event->code, 173, 27, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#000000');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text($user->name, 780, 550, function ($font) {
            $font->filename(public_path('fonts/GreatVibes-Regular.ttf'));
            $font->color('#1F45FC');
            $font->size(45);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text('Attended a Continuing Professional Development(CPD) activity', 760, 620, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#000000');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        //add event name
        $image->text('"' . $event->topic . '"', 730, 690, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#000000');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });


        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);

        if ($startDate->month === $endDate->month) {
            $x = 720;
            // Dates are in the same month
            $formattedRange = $startDate->format('jS') . ' - ' . $endDate->format('jS F Y');
        } else {
            $x = 780;
            // Dates are in different months
            $formattedRange = $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');
        }


        $image->text('on ', 600, 760, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#000000');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text($formattedRange, $x, 760, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#000000');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text($event->hours . "CPD HOURS", 1400, 945, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#000000');
            $font->size(17);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->toPng();

        //save the image to the public folder
        $image->save(public_path('images/certificate-generated.png'));

        //download the image
        return response()->download(public_path('images/certificate-generated.png'))->deleteFileAfterSend(true);
    }


    public function downloadCertificate($cpd_id, $user_id)
    {
        //dd("am here");
        try {
            $manager = new ImageManager(new Driver());

            $event = Cpd::find($cpd_id);
            $user = \App\Models\User::find($user_id);

            // Load the certificate template
            $image = $manager->read(public_path('images/cpd_template.jpeg'));

            // Add details to the certificate
            $image->text($event->code, 180, 85, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($user->name, 780, 625, function ($font) {
                $font->file(public_path('fonts/GreatVibes-Regular.ttf'));
                $font->size(45);
                $font->color('#1F45FC');
                $font->align('center');
            });

            $image->text($event->topic, 850, 770, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(25);
                $font->color('#000000');
                $font->align('center');
            });

            $startDate = Carbon::parse($event->start_date);
            $endDate = Carbon::parse($event->end_date);

            $x = ($startDate->month === $endDate->month) ? 720 : 780;
            $formattedRange = ($startDate->month === $endDate->month)
                ? $startDate->format('jS') . ' - ' . $endDate->format('jS F Y')
                : $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');

            // $image->text('on ', 600, 760, function ($font) {
            //     $font->file(public_path('fonts/Roboto-Regular.ttf'));
            //     $font->size(20);
            //     $font->color('#000000');
            //     $font->align('center');
            // });

            $image->text($formattedRange, $x, 825, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($event->hours . " CPD HOURS", 1400, 1020, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(17);
                $font->color('#000000');
                $font->align('center');
            });

            // Save the certificate to a temporary file
            $path = public_path('certificates/' . $user->id . '_certificate.png');
            $image->save($path);

            //check if the file exists and is readable and send the file
            if (file_exists($path) && is_readable($path)) {
                return response([
                    "success" => true,
                    "message" => "Certificate generated successfully.",
                    "url" => url('certificates/' . $user->id . '_certificate.png'),
                    "name" => $user->name
                ]);
            } else {
                return redirect()->back()->with('error', 'An error occurred while downloading the certificate.');
            }
        } catch (\Exception $e) {
            // Handle any errors that occur during the certificate generation
            return redirect()->back()->with('error', 'An error occurred while generating the certificate: ' . $e->getMessage());
        }
    }

    public function emailCertificate($cpd_id, $user_id)
    {
        try {

            $manager = new ImageManager(new Driver());

            $event = Cpd::find($cpd_id);
            // dd($event);
            $user = \App\Models\User::find($user_id);

            // Load the certificate template
            $image = $manager->read(public_path('images/cpd_template.jpeg'));

            // Add details to the certificate
            $image->text($event->code, 180, 85, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($user->name, 780, 625, function ($font) {
                $font->file(public_path('fonts/GreatVibes-Regular.ttf'));
                $font->size(45);
                $font->color('#1F45FC');
                $font->align('center');
            });

            $image->text($event->topic, 850, 770, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(25);
                $font->color('#000000');
                $font->align('center');
            });

            $startDate = Carbon::parse($event->start_date);
            $endDate = Carbon::parse($event->end_date);

            $x = ($startDate->month === $endDate->month) ? 720 : 780;
            $formattedRange = ($startDate->month === $endDate->month)
                ? $startDate->format('jS') . ' - ' . $endDate->format('jS F Y')
                : $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');

            $image->text($formattedRange, $x, 825, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($event->hours . " CPD HOURS", 1400, 1020, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(17);
                $font->color('#000000');
                $font->align('center');
            });


            // Save the certificate to a temporary file
            $path = public_path('certificates/' . $user->id . '_certificate.png');
            $image->save($path);

            // Send the certificate via email
            // Mail::to($user->email)->send(new CertificateMail($user, $event, $path));
            // Send the certificate via email
            Mail::to($user->email)->send(new CertificateMail($user, $event, $path, $formattedRange));


            // Optionally, delete the file after sending the email
            unlink($path);

            return redirect()->back()->with('success', 'Certificate has been emailed successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while emailing the certificate: ' . $e->getMessage());
        }
    }

    public function bulkEmail(Request $request)
    {
        $cpdId = $request->input('cpd_id');

        // Dispatch the job to handle sending emails asynchronously
        SendBulkCpdEmailJob::dispatch($cpdId);

        return redirect()->back()->with('success', 'Certificates are being processed and will be emailed shortly.');
    }


    public function downloadBulkCertificates(Request $request)
    {
        $cpd_id = $request->input('cpd_id');
        $loggedInUser = Auth::user();
        \Log::info('Bulk download request received for CPD ID: ' . $cpd_id);

        // Queue the job for downloading bulk CPD certificates
        DownloadBulkCPDCertificatesJob::dispatch($cpd_id, $loggedInUser);

        return redirect()->back()->with('success', 'The bulk download process for CPD certificates has been queued. Check your email later for the zip file.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'attendence_id' => 'required|exists:attendences,id',
            'email' => 'required|email',
            'name' => 'required'
        ]);

        $user_details = Attendence::find($request->attendence_id);
        $user = User::find($user_details->user_id);

        if ($user) {
            $user->email = $request->email;
            $user->name = $request->name;
            $user->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'User not found.']);
    }


}
