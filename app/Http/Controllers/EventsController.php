<?php

namespace App\Http\Controllers;

use App\Mail\CertificateMail;
use App\Mail\EventCertificate;
use App\Models\Attendence;
use App\Models\Cpd;
use App\Models\Event;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            if ($payment_details['status'] == 'successful') {
                $register_attendance = $this->confirm_attendence(request());
                if ($register_attendance) {
                    return view('members.dashboard')->with('success', 'registration successful!');
                } else {
                    return view('members.dashboard')->with('error', 'registration failed!');
                }
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

            $response = $client->post('https://api.flutterwave.com/v3/payments', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('FLW_SECRET_KEY'),
                ],
                'json' => [
                    'tx_ref' => Str::uuid(),
                    'amount' => $event->rate,
                    'currency' => 'UGX',
                    'redirect_url' => url('redirect_url_events') . '?event_id=' . $event->id,
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
            ]);

            $responseBody = json_decode($response->getBody(), true);
            //check if the request was successful
            if ($responseBody['status'] == 'success') {
                return redirect()->away($responseBody['data']['link']);
            } else {
                return redirect()->back()->with('error', 'Payment request failed!');
            }
        } catch (RequestException $e) {
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

    // public function record_direct_attendence(Request $request)
    // {
    //     // if ($this->device_attended()) {
    //     //     return redirect()->back()->with('error', 'You have already registered');
    //     // }
    //     //dd($request->all());

    //     $request->validate([
    //         'name' => 'required',
    //         // 'email' => 'required|email|unique:users,email',
    //         'email' => 'required|email',
    //     ]);


    //     $user = \App\Models\User::where('email', $request->email)->first();

    //     if (!$user) {
    //         $password = \Str::random(9);

    //         $user = new \App\Models\User;
    //         $user->name = $request->name;
    //         $user->email = $request->email;
    //         $user->password = Hash::make($password);
    //         $user->account_type_id = \App\Models\AccountType::first()->id;
    //         $user->save();
    //     }

    //     \Auth::login($user);

    //     $attendence = new Attendence;
    //     $attendence->user_id = \Auth::user()->id;
    //     if ($request->type == "event") {
    //         $attendence->event_id = $request->id;
    //         $attendence->type = "Event";
    //     } else {
    //         $attendence->cpd_id = $request->id;
    //         $attendence->type = "CPD";
    //     }
    //     $attendence->status = "Attended";
    //     $attendence->membership_number = $request->membership_number;
    //     $attendence->save();

    //     if ($request->type == "event") {
    //         //get the logged in user
    //         $event = Event::find($request->id);
    //         if ($event != null) {
    //             return $this->direct_event_attendance_certificate_parser($user, $event, "event");
    //         } else {
    //             return redirect()->back()->with('error', 'Event not found');
    //         }
    //     } else {
    //         $event = Cpd::find($request->id);
    //         if ($event != null) {
    //             return $this->direct_cpd_attendance_certificate_parser($user, $event, "cpd");
    //         } else {
    //             return redirect()->back()->with('error', 'CPD not found');
    //         }
    //     }
    // }

    public function record_direct_attendence(Request $request)
{
    // Validate the request data
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
    ]);

    // Find user by email or create a new user
    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user) {
        $password = \Str::random(9);

        $user = new \App\Models\User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($password);
        $user->account_type_id = \App\Models\AccountType::first()->id;
        $user->save();
    }

    // Log in the user
    \Auth::login($user);

    // Record attendance
    $attendence = new Attendence;
    $attendence->user_id = \Auth::user()->id;

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

    // Redirect to the "Thank You" page after successful registration
    return redirect()->route('thank.you.page')->with('success', 'Thank you for registering. Your attendance has been recorded.');
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
        $manager = new ImageManager(new Driver());
        //read the image from the public folder
        $image = $manager->read(public_path('images/cpd-certificate-template.jpg'));

        $eventFound = $event;


        $user = auth()->user();

        $image->text($eventFound->code, 173, 27, function ($font) {
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
        $image->text('"' . $eventFound->topic . '"', 730, 690, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#000000');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });


        $startDate = Carbon::parse($eventFound->start_date);
        $endDate = Carbon::parse($eventFound->end_date);

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

        $image->text($eventFound->hours . "CPD HOURS", 1400, 945, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#000000');
            $font->size(17);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->toPng();

        //let file name be cpd-certificate-generated_user_id.png
        $file_name = 'cpd-certificate-generated_' . $user->id . '.png';

        //save the image to the public folder
        $image->save(public_path('images/' . $file_name));

        //download the image
        return response()->download(public_path('images/' . $file_name))->deleteFileAfterSend(true);
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
        $templatePath = $event->event_type == 'Annual' ? public_path('images/event_annual_certificate.jpeg') : public_path('images/certificate-template.jpeg');
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
        $user = \App\Models\User::find($user_id);
        $name = $user->name;
        $membership_number = $user->membership_number;
        $id = $user->id;

        // Determine the template based on event type
        $templatePath = $event->event_type == 'Annual' ? public_path('images/event_annual_certificate.jpeg') : public_path('images/certificate-template.jpeg');
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

        // Download the generated certificate
        return response()->download(public_path('images/' . $file_name))->deleteFileAfterSend(true);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while generating the certificate: ' . $e->getMessage());
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

// Helper method for customizing an annual event certificate
private function customizeAnnualCertificate($image, $event, $name, $membership_number)
{
    // Additional information for Annual events
    $place = $event->place ?? 'Not specified';
    $theme = $event->theme ?? 'Not specified';
    $annual_event_date = Carbon::parse($event->annual_event_date)->format('jS F Y');
    $organizing_committee = $event->organizing_committee ?? 'Institute of Procurement Professionals of Uganda (IPPU)';

    // Name Placement
    $image->text($name, 1800, 1150, function ($font) {
        // $font->filename(public_path('fonts/Roboto-Bold.ttf'));
        $font->file(public_path('fonts/GreatVibes-Regular.ttf'));
        $font->color('#b01735'); // Dark red color
        $font->size(100); // Increased size for better visibility
        $font->align('center');
        $font->valign('middle');
    });



    // Event Name Placement
    $image->text($event->name, 1650, 1350, function ($font) {
        $font->filename(public_path('fonts/Roboto-Bold.ttf'));
        $font->color('#008000'); // Green color
        $font->size(60); // Increased size
        $font->align('center');
        $font->valign('middle');
    });

    // Theme Text Placement
    // Theme Text Placement (split into two lines)
    $theme = wordwrap('THEME: ' . $theme, 50, "\n", true); // Adjust the 50 to fit the length you want per line

    $image->text($theme, 1700, 1500, function ($font) {
        $font->filename(public_path('fonts/Roboto-Bold.ttf'));
        $font->color('#405189'); // Blue color
        $font->size(60);
        $font->align('center');
        $font->valign('middle');
    });


    // Organizer and Date Text Placement
    $image->text('Organised by ' . $organizing_committee, 1700, 1650, function ($font) {
        $font->filename(public_path('fonts/Roboto-Regular.ttf'));
        $font->color('#405189'); // Blue color
        $font->size(50);
        $font->align('center');
        $font->valign('middle');
    });

    // Date and Place Text Placement
    $image->text('on ' . $annual_event_date . ' at ' . $place . '.', 1700, 1720, function ($font) {
        $font->filename(public_path('fonts/Roboto-Regular.ttf'));
        $font->color('#405189'); // Blue color
        $font->size(45);
        $font->align('center');
        $font->valign('middle');
    });

    // CPD Points Text Placement
    $image->text('This activity was awarded ' . $event->points . ' CPD Credit Points (Hours) of IPPU', 1700, 1800, function ($font) {
        $font->filename(public_path('fonts/Roboto-Bold.ttf'));
        $font->color('#008000'); // Green color
        $font->size(45);
        $font->align('center');
        $font->valign('middle');
    });

    // Membership Number Placement
    $image->text(($membership_number ?? 'N/A'), 1900, 2000, function ($font) {
        $font->filename(public_path('fonts/Roboto-Bold.ttf'));
        $font->color('#405189'); // Blue color
        $font->size(45);
        $font->align('center');
        $font->valign('middle');
    });
}

// Helper method for customizing a regular event certificate
private function customizeRegularCertificate($image, $event, $name, $membership_number)
{

   $image->text('PRESENTED TO', 420, 250, function ($font) {
       $font->filename(public_path('fonts/Roboto-Bold.ttf'));
       $font->color('#405189');
       $font->size(12);
       $font->align('center');
       $font->valign('middle');
   });

   //dd($user->name);

   $image->text($name, 420, 300, function ($font) {
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
   $image->text('MembershipNumber: ' . $membership_number ?? "N/A", 450, 483, function ($font) {
       $font->filename(public_path('fonts/Roboto-Bold.ttf'));
       $font->color('#405189');
       $font->size(12);
       $font->align('center');
       $font->valign('middle');
       $font->lineHeight(1.6);
   });

}


public function downloadBulkCertificates(Request $request)
{
    $event_id = $request->input('event_id');

    // Find the event
    $event = Event::find($event_id);
    if (!$event) {
        return redirect()->back()->with('error', 'Event not found.');
    }

    // Get all users who attended the event
    $userIds = \App\Models\Attendence::where('event_id', $event_id)
        ->where('status', 'Attended')
        ->pluck('user_id')->toArray();

    // Create a unique name for the ZIP file
    $zipFileName = 'bulk_events_certificates_' . $event_id . '.zip';
    $zipFilePath = public_path('certificates/' . $zipFileName);

    // Ensure the directory for storing certificates exists
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
                $file_name = 'certificate_generated_' . $user->id . '.png';
                $file_path = public_path('certificates/' . $file_name);

                $this->downloadCertificate($event_id, $userId);

                // Add the certificate to the ZIP file
                if (file_exists($file_path)) {
                    $zip->addFile($file_path, $user->name . '_certificate.png');
                    $certificatePaths[] = $file_path; // Track the path for cleanup
                } else {
                    Log::error('Certificate file not found for user: ' . $user->name);
                }
            } catch (\Exception $e) {
                Log::error('Error generating certificate for user: ' . $user->name . ' - ' . $e->getMessage());
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


    //new

}
