<?php

namespace App\Http\Controllers;

use App\Jobs\CertificateRequested;
use App\Jobs\CpdCertificateGeneration;
use App\Jobs\DownloadBulkCertificatesJob;
use App\Jobs\RegularCertificateRequested;
use App\Jobs\SendBulkEmailEventJob;
use App\Mail\CertificateMail;
use App\Mail\EventCertificate;
use App\Models\Attendence;
use App\Models\Cpd;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Symfony\Component\Mailer\Exception\TransportException;
use ZipArchive;



class EventsController extends Controller
{
    public function index()
    {
        $events = Event::all();

        return view('members.events.index', compact('events'));
    }

    public function upcoming()
    {
        $events = Event::where('start_date', '>=', date('Y-m-d'))->get();

        return view('members.events.index', compact('events'));
    }

    public function attend($id = '')
    {

        $event = Event::find($id);
        return view('members.events.confirmation', compact('event'));
    }

    public function redirect_url()
    {
        $payment_details = request()->all();

        try {
                $register_attendance = $this->confirm_attendence(request());
                if ($register_attendance) {
                    return view('members.dashboard')->with('success', 'registration successful!');
                } else {
                    return view('members.dashboard')->with('error', 'registration failed!');
                }

        } catch (TransportException $exception) {
            return view('members.dashboard')->with('error', $exception->getMessage());
        }
    }

    public function pay($id = '')
    {
        try {
            $client = new Client();
            $event = Event::find($id);

            $amount = request()->input('amount') ?? $event->rate;



            // dd(url('redirect_url_events') . '?event_id=' . $event->id);

            $options = [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('FLW_SECRET_KEY'),
                ],
                'json' => [
                    'tx_ref' => Str::uuid(),
                    'amount' => $amount,
                    'currency' => 'UGX',
                    'redirect_url' => 'https://ippu.org/login',
                    'meta' => [
                        'consumer_id' => auth()->user()->id,
                        "full_name" => auth()->user()->name,
                        "email" => auth()->user()->email,
                        "being_payment_for" => "Attendance of Event",
                        "event_id" => $event->id,
                        "event_topic" => $event->name,
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
            ];

            // dd($options);

            $response = $client->post('https://api.flutterwave.com/v3/payments', $options);

            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody['status'] == 'success') {
                return response()->json(['success' => true, 'data' => $responseBody['data']['link']]);
            } else {
                return redirect()->back()->with('error', 'Payment request failed!');
            }
        } catch (RequestException  $e) {
            dd($e->getMessage());
            if ($e->hasResponse()) {
                $responseBody = json_decode($e->getResponse()->getBody(), true);
                dd($responseBody);
                //return redirect()->back()->with('error', $responseBody['message']);
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
            $attendence->event_id = $request->event_id;
            $attendence->type = "Event";
            $attendence->status = "Pending";
            $attendence->save();

            return redirect()->back()->with('success', 'Attendence has been recorded!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function attended()
    {
        $events = Event::whereHas('attended')->get();

        return view('members.events.index', compact('events'));
    }

    public function details($id)
    {
        $event = Event::find($id);

        return view('members.events.details', compact('event'));
    }

    public function certificate($event)
    {
        $event = Event::find($event);

        return view('members.events.certificate', compact('event'));

        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $view = View::make('members.events.certificate', compact('event'))->render();
        $dompdf->loadHtml($view);
        $dompdf->setPaper('auto');

        // Render the HTML as PDF
        $dompdf->render();
        $dompdf->stream($event->name . '.pdf');
    }

    public function generate_certificate($event)
    {
        $manager = new ImageManager(new Driver());
        //read the image from the public folder
        $image = $manager->read(public_path('images/certificate-template.jpeg'));

        $event = Event::find($event);
        $user = auth()->user();


        $image->text('PRESENTED TO', 420, 250, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(12);
            $font->align('center');
            $font->valign('middle');
        });

        $image->text($user->name, 420, 300, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#b01735');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text('FOR ATTENDING THE', 420, 340, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(12);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        //add event name
        $image->text($event->name, 420, 370, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#008000');
            $font->size(22);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });


        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);

        if ($startDate->month === $endDate->month) {
            $x = 420;
            // Dates are in the same month
            $formattedRange = $startDate->format('jS') . ' - ' . $endDate->format('jS F Y');
        } else {
            $x = 480;
            // Dates are in different months
            $formattedRange = $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');
        }

        $image->text('Organized by the Institute of Procurement Professionals of Uganda on ' . $formattedRange, $x, 400, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#405189');
            $font->size(12);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        //add membership number
        $membership_number = $user->membership_number ?? 'N/A';

        //add membership number
        $image->text('Membership Number: ' . $membership_number, 450, 483, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(12);
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


    public function direct_attendence($type, $id)
    {
        $data = new \stdClass;
        $data->type = $type;
        $data->id = $id;

        if ($type == "cpd") {
            $cpd = \App\Models\Cpd::find($id);
            $data->name = $cpd->topic;
            $data->points = $cpd->points;
            $data->end_date = $cpd->end_date;
            $data->banner = $cpd->banner;
            $data->code = $cpd->code;
        }

        if ($type == "event") {
            $event = Event::find($id);
            $data->name = $event->name;
            $data->points = $event->points;
            $data->end_date = $event->end_date;
            $data->banner = $event->banner_name;
        }

        if (\Carbon\Carbon::parse($data->end_date)->isPast()) {
            $data->end_date = "Past";
        } else {
            $data->end_date = "Future";
        }

        return view('members.attendence.direct', compact('data'));
    }

    public function record_direct_attendence(Request $request)
    {
        // if ($this->device_attended()) {
        //     return redirect()->back()->with('error', 'You have already registered');
        // }
        //dd($request->all());

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone_no' => 'required',
            'organisation'=>'required'
        ]);


        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            $password = Str::random(9);

            $user = new \App\Models\User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_no = $request->phone_no;
            $user->organisation = $request->organisation;
            $user->password = Hash::make($password);
            $user->account_type_id = \App\Models\AccountType::first()->id;
            $user->save();
        }

        Auth::login($user);

        $attendence = new Attendence;
        $attendence->user_id = Auth::user()->id;
        if ($request->type == "event") {
            $attendence->event_id = $request->id;
            $attendence->type = "Event";
        } else {
            $attendence->cpd_id = $request->id;
            $attendence->type = "CPD";
        }
        $attendence->status = "Attended";
        $attendence->membership_number = $request->membership_number;
        $attendence->save();

    if ($request->type == "event") {
        //get the logged in user
        $event = Event::find($request->id);
        if ($event != null) {
            return $this->direct_event_attendance_certificate_parser($user, $event, "event");
        } else {
            return redirect()->back()->with('error', 'Event not found');
        }
    } else {
        $event = Cpd::find($request->id);

        if ($event != null) {
            return $this->direct_cpd_attendance_certificate_parser($user, $event, "cpd");
        } else {
            return redirect()->back()->with('error', 'CPD not found');
        }
    }
    }





    public function direct_event_attendance_certificate_parser(User $user, $event, $eventType)
    {
        $manager = new ImageManager(new Driver());
        //read the image from the public folder
        $image = $manager->read(public_path('images/certificate-template.jpeg'));
        $eventFound = Event::find($event);
        //$user = User::find($user);
        //dd($event);

        $image->text('PRESENTED TO', 420, 250, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(12);
            $font->align('center');
            $font->valign('middle');
        });

        $image->text($user->name, 420, 300, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#b01735');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text('FOR ATTENDING THE', 420, 340, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(12);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        //add event name
        $image->text($event->name, 420, 370, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#008000');
            $font->size(22);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });


        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);

        if ($startDate->month === $endDate->month) {
            $x = 420;
            // Dates are in the same month
            $formattedRange = $startDate->format('jS') . ' - ' . $endDate->format('jS F Y');
        } else {
            $x = 480;
            // Dates are in different months
            $formattedRange = $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');
        }


        $image->text('Organized by the Institute of Procurement Professionals of Uganda on ' . $formattedRange, $x, 400, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#405189');
            $font->size(12);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        //add membership number
        $image->text('MembershipNumber: ' . $user->membership_number ?? "N/A", 450, 483, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(12);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->toPng();
        //let file name be certificate-generated_user_id.png
        $file_name = 'certificate-generated_' . $user->id . '.png';

        //save the image to the public folder
        $image->save(public_path('images/' . $file_name));

        //download the image
        return response()->download(public_path('images/' . $file_name))->deleteFileAfterSend(true);
    }

    public function direct_cpd_attendance_certificate_parser(User $user, $event, $eventType)
    {
        try {
            // Call CpdCertificateGeneration job to generate the certificate
            //CpdCertificateGeneration::dispatch($event, $user);
            return response()->json(['success' => true, 'message' => 'Thank you for your attendance. The certificate will be sent to your email.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while generating the certificate: ' . $e->getMessage()]);
        }
    }


    public function device_attended()
    {
        // Check if the session variable exists
        $value = Session::has('device_attended');

        if (!$value) {
            // Set the session variable with a 4-hour expiration time
            Session::put(['device_attended' => true, 'expires' => now()->addMinutes(240)]);
            return false; // Signal first attendance
        }

        return true; // Indicate if the device has already been attended
    }

    //new
    public function emailCertificate($event_id, $user_id)
    {
        try {
            $manager = new ImageManager(new Driver());
            $event = Event::find($event_id);
            $user = \App\Models\User::find($user_id);
            $name = $user->name;
            $membership_number = $user->membership_number;
            $id = $user->id;



            $formattedRange = Carbon::parse($event->start_date)->format('jS F Y') . ' - ' . Carbon::parse($event->end_date)->format('jS F Y');

            // Determine the template based on event type
            $templatePath = public_path('images/event_annual_certificate.jpeg');
            if (!file_exists($templatePath)) {
                throw new \Exception('Certificate template not found.');
            }

            $image = $manager->read($templatePath);

            // Customize certificate details based on event type
            if ($event->event_type == 'Annual') {
                $this->customizeAnnualCertificate($image, $event, $name, $membership_number);
            } else {
                $this->customizeRegularCertificate($image, $event, $name, $membership_number);
            }

            $file_name = 'certificate-generated_' . $id . '.png';
            $image->save(public_path('images/' . $file_name));

            // Send the certificate via email
            $path = public_path('images/' . $file_name);
            Mail::to($user->email)->send(new EventCertificate($name, $event, $path, $formattedRange));

            // Optionally, delete the certificate after sending the email
            unlink($path);

            return redirect()->back()->with('success', 'Certificate generated and emailed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while generating the certificate: ' . $e->getMessage());
        }
    }


    public function downloadCertificate($event_id, $user_id)
    {
        try {
            $manager = new ImageManager(new Driver());
            $event = Event::find($event_id);
            $user = User::find($user_id);
            $name = $user->name;
            $membership_number = $user->membership_number;
            $id = $user->id;



            $formattedRange = Carbon::parse($event->start_date)->format('jS F Y') . ' - ' . Carbon::parse($event->end_date)->format('jS F Y');

            // Determine the template based on event type
            $templatePath = public_path('images/event_annual_certificate.jpeg');
            if (!file_exists($templatePath)) {
                throw new \Exception('Certificate template not found.');
            }

            $image = $manager->read($templatePath);

            // Customize certificate details based on event type
            if ($event->event_type == 'Annual') {
                $this->customizeAnnualCertificate($image, $event, $name, $membership_number);
            } else {
                $this->customizeRegularCertificate($image, $event, $name, $membership_number);
            }

            $file_name = 'certificate-generated_' . $id . '.png';
            $image->save(public_path('images/' . $file_name));

            // Send the certificate via email
            $path = public_path('images/' . $file_name);

            //check if the file exists and is readable and send the file
            if (file_exists($path) && is_readable($path)) {
                return response([
                    "success" => true,
                    "message" => "Certificate generated successfully.",
                    "url" => url('images/' . $file_name),
                    "name"=>$name
                ]);
            } else {
                return redirect()->back()->with('error', 'An error occurred while downloading the certificate.');
            }

            // return redirect()->back()->with('success', 'Certificate generated and emailed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while generating the certificate: ' . $e->getMessage());
        }
    }





    public function sendBulkEmail(Request $request)
    {
        $eventId = $request->input('event_id');

        // Dispatch the job to handle sending emails asynchronously
        SendBulkEmailEventJob::dispatch($eventId);

        return redirect()->back()->with('success', 'Certificates are being processed and will be emailed shortly.');
    }

    // Helper method for customizing an annual event certificate
    private function customizeAnnualCertificate($image, $event, $name, $membership_number)
    {
        // Additional information for Annual events
        $place = $event->place ?? 'Not specified';
        $theme = $event->theme ?? 'Not specified';
        $annual_event_date = Carbon::parse($event->annual_event_date)->format('jS F Y');
        $organizing_committee = $event->organizing_committee ?? 'Institute of Procurement Professionals of Uganda (IPPU)';

        // Name Placement
        $image->text($name, 800, 500, function ($font) {
            // $font->file(public_path('fonts/Roboto-Bold.ttf'));
            $font->filename(public_path('fonts/GreatVibes-Regular.ttf'));
            $font->color('#b01735'); // Dark red color
            $font->size(50); // Increased size for better visibility
            $font->align('center');
            $font->valign('middle');
        });



        // Event Name Placement
        $image->text($event->name, 800, 620, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#008000'); // Green color
            $font->size(30); // Increased size
            $font->align('center');
            $font->valign('middle');
        });

        // Theme Text Placement
        // Theme Text Placement (split into two lines)
        $theme = wordwrap('THEME: ' . $theme, 50, "\n", true); // Adjust the 50 to fit the length you want per line

        $image->text($theme, 800, 680, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189'); // Blue color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });


        // Organizer and Date Text Placement
        $image->text('Organised by ' . $organizing_committee, 800, 740, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#405189'); // Blue color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        // Date and Place Text Placement
        $image->text('on ' . $annual_event_date . ' at ' . $place . '.', 800, 780, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#405189'); // Blue color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        // CPD Points Text Placement
        $image->text('This activity was awarded ' . $event->points . ' CPD Credit Points (Hours) of IPPU', 800, 830, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#008000'); // Green color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        // convert the image to png
        $image->toPng();
    }

    // Helper method for customizing a regular event certificate

    private function customizeRegularCertificate($image, $event, $name, $membership_number)
    {
        // Additional information for Annual events
        $place = $event->location ?? 'Not specified';
        $theme = $event->theme ?? 'Not specified';
        $annual_event_date = Carbon::parse($event->annual_event_date)->format('jS F Y');
        $organizing_committee = $event->organizing_committee ?? 'Institute of Procurement Professionals of Uganda (IPPU)';

        // Name Placement
        $image->text($name, 800, 500, function ($font) {
            // $font->file(public_path('fonts/Roboto-Bold.ttf'));
            $font->filename(public_path('fonts/GreatVibes-Regular.ttf'));
            $font->color('#b01735'); // Dark red color
            $font->size(50); // Increased size for better visibility
            $font->align('center');
            $font->valign('middle');
        });



        // Event Name Placement
        $image->text($event->name, 800, 620, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#008000'); // Green color
            $font->size(30); // Increased size
            $font->align('center');
            $font->valign('middle');
        });

        // Theme Text Placement
        // Theme Text Placement (split into two lines)
        // $theme = wordwrap('THEME: ' . $theme, 50, "\n", true); // Adjust the 50 to fit the length you want per line

        // $image->text($theme, 800, 680, function ($font) {
        //     $font->filename(public_path('fonts/Roboto-Bold.ttf'));
        //     $font->color('#405189'); // Blue color
        //     $font->size(30);
        //     $font->align('center');
        //     $font->valign('middle');
        // });


        // Organizer and Date Text Placement
        $image->text('Organised by ' . $organizing_committee, 800, 700, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#405189'); // Blue color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);

        $x = ($startDate->month === $endDate->month) ? 720 : 780;
        $formattedRange = ($startDate->month === $endDate->month)
            ? $startDate->format('jS') . ' - ' . $endDate->format('jS F Y')
            : $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');


        // Date and Place Text Placement
        $image->text('on ' . $formattedRange, 800, 780, function ($font) {
            $font->filename(public_path('fonts/Roboto-Regular.ttf'));
            $font->color('#405189'); // Blue color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        // CPD Points Text Placement
        $image->text('This activity was awarded ' . $event->points . ' CPD Credit Points (Hours) of IPPU', 800, 830, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#008000'); // Green color
            $font->size(30);
            $font->align('center');
            $font->valign('middle');
        });

        // convert the image to png
        $image->toPng();
    }



    public function downloadBulkCertificates(Request $request)
    {
        $event_id = $request->input('event_id');

        // Queue the job for downloading bulk certificates
        DownloadBulkCertificatesJob::dispatch($event_id);

        return redirect()->back()->with('success', 'The bulk download process has been queued. You will be notified when it is ready.');
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




    //new

}
