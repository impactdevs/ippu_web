<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cpd;
use App\Models\Attendence;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Symfony\Component\Mailer\Exception\TransportException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use Auth;
use App\Mail\CertificateMail;
use Illuminate\Support\Facades\Mail;
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
        $payment_details = request()->all();
        try {
            if ($payment_details['status'] == 'successful') {
                $this->confirm_attendence(request());
            }
        } catch (TransportException $exception) {
            return view('members.dashboard')->with('error', 'Email could not be sent!');
        }

        return view('members.dashboard')->with('success', 'Payment was not successful!');
    }

    public function pay($id = '')
    {
        //  dd("pay");
        try {
            $client = new Client();
            $cpd = Cpd::find($id);

            $response = $client->post('https://api.flutterwave.com/v3/payments', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('FLW_SECRET_KEY'),
                ],
                'json' => [
                    'tx_ref' => Str::uuid(),
                    'amount' => $cpd->normal_rate,
                    'currency' => 'UGX',
                    'redirect_url' => url('redirect_url_cpds') . '?cpd_id=' . $cpd->id,
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
                return redirect()->away($responseBody['data']['link']);
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
            $attendence = new Attendence;
            $attendence->user_id = \Auth::user()->id;
            $attendence->cpd_id = $request->cpd_id;
            $attendence->type = "CPD";
            $attendence->status = "Pending";

            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $extension = $file->getClientOriginalExtension();

                $filename = time() . rand(100, 1000) . '.' . $extension;

                $storage = \Storage::disk('public')->putFileAs(
                    'images/',
                    $file,
                    $filename
                );

                if (!$storage) {
                    return response()->json(['message' => 'Unable to upload payment proof!']);
                } else {
                    $attendence->payment_proof = $filename;
                }
            }

            $attendence->save();

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
            $image = $manager->read(public_path('images/cpd-certificate-template.jpg'));

            // Add details to the certificate
            $image->text($event->code, 173, 27, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($user->name, 780, 550, function ($font) {
                $font->file(public_path('fonts/GreatVibes-Regular.ttf'));
                $font->size(45);
                $font->color('#1F45FC');
                $font->align('center');
            });

            $image->text($event->topic, 730, 690, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $startDate = Carbon::parse($event->start_date);
            $endDate = Carbon::parse($event->end_date);

            $x = ($startDate->month === $endDate->month) ? 720 : 780;
            $formattedRange = ($startDate->month === $endDate->month)
                ? $startDate->format('jS') . ' - ' . $endDate->format('jS F Y')
                : $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');

            $image->text('on ', 600, 760, function ($font) {
                $font->file(public_path('fonts/Roboto-Regular.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($formattedRange, $x, 760, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($event->hours . " CPD HOURS", 1400, 945, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(17);
                $font->color('#000000');
                $font->align('center');
            });

            // Save the certificate to a temporary file
            $path = public_path('certificates/' . $user->id . '_certificate.png');
            $image->save($path);

            // Return the certificate for download
            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            dd($e->getMessage());
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
            $image = $manager->read(public_path('images/cpd-certificate-template.jpg'));

            // Add details to the certificate
            $image->text($event->code, 173, 27, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($user->name, 780, 550, function ($font) {
                $font->file(public_path('fonts/GreatVibes-Regular.ttf'));
                $font->size(45);
                $font->color('#1F45FC');
                $font->align('center');
            });

            $image->text($event->topic, 730, 690, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $startDate = Carbon::parse($event->start_date);
            $endDate = Carbon::parse($event->end_date);

            $x = ($startDate->month === $endDate->month) ? 720 : 780;
            $formattedRange = ($startDate->month === $endDate->month)
                ? $startDate->format('jS') . ' - ' . $endDate->format('jS F Y')
                : $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');

            $image->text('on ', 600, 760, function ($font) {
                $font->file(public_path('fonts/Roboto-Regular.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($formattedRange, $x, 760, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });

            $image->text($event->hours . " CPD HOURS", 1400, 945, function ($font) {
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
        $userIds = $request->input('attendees', []);

        foreach ($userIds as $userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                // Assuming $cpd_id is passed through a hidden input field or other means
                $cpd_id = $request->input('cpd_id');
                $cpd = Cpd::find($cpd_id);

                // Generate and email certificate
                $this->emailCertificate($cpd_id, $userId);
            }
        }

        return redirect()->back()->with('success', 'Certificates have been emailed successfully.');
    }


    public function downloadBulkCertificates(Request $request)
    {

        $cpd_id = $request->input('cpd_id');
    
        // Find the CPD event
        $cpd = Cpd::find($cpd_id);
    
        if (!$cpd) {
            return redirect()->back()->with('error', 'CPD event not found.');
        }
    
        // If no user IDs are provided, default to all users who attended the CPD
        
            $userIds = \App\Models\Attendence::where('cpd_id', $cpd_id)
                ->where('status', 'Attended')
                ->pluck('user_id')->toArray();
        
    
        // Create a unique name for the ZIP file
        $zipFileName = 'bulk_certificates_' . $cpd->code . '.zip';
        $zipFilePath = public_path('certificates/' . $zipFileName);
    
        // Ensure the directory exists for storing certificates
        if (!file_exists(public_path('certificates'))) {
            mkdir(public_path('certificates'), 0777, true);
        }
    
        // Create a new ZIP file
        $zip = new \ZipArchive;
        if ($zip->open($zipFilePath, \ZipArchive::CREATE) !== true) {
            return redirect()->back()->with('error', 'Could not create ZIP file.');
        }
    
        // Array to store paths of generated certificates for cleanup
        $certificatePaths = [];
    
        // Generate a certificate for each user and add it to the ZIP
        foreach ($userIds as $userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                try {
                    // Generate the certificate for the user
                    $this->downloadCertificate($cpd_id, $userId);
    
                    // Path to the generated certificate
                    $certificatePath = public_path('certificates/' . $user->id . '_certificate.png');
    
                    // Add the certificate to the ZIP file
                    if (file_exists($certificatePath)) {
                        $zip->addFile($certificatePath, $user->name . '_certificate.png');
                        $certificatePaths[] = $certificatePath; // Track the path for cleanup
                    } else {
                        \Log::error('Certificate file not found for user: ' . $user->name);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error generating certificate for user: ' . $user->name . ' - ' . $e->getMessage());
                }
            }
        }
    
        // Close the ZIP file once all certificates are added
        $zip->close();
    
        // Cleanup: Delete the individual certificate files after they are added to the ZIP
        foreach ($certificatePaths as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }
    
        // Return the ZIP file for download, and delete it after sending
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
    

}
