<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Attendence;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Dompdf\Options;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Cpd;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Exception\TransportException;
use Auth;



class EventsController extends Controller
{
    public function index()
    {
        $events = Event::all();

        return view('members.events.index',compact('events'));
    }

    public function upcoming()
    {
        $events = Event::where('start_date','>=',date('Y-m-d'))->get();

        return view('members.events.index',compact('events'));
    }

    public function attend($id='')
    {
       
        $event = Event::find($id);
        return view('members.events.confirmation',compact('event'));
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
                    'redirect_url' => url('redirect_url_events').'?event_id='.$event->id,
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
         try{
            $attendence = new Attendence;
            $attendence->user_id = \Auth::user()->id;
            $attendence->event_id = $request->event_id;
            $attendence->type = "Event";
            $attendence->status = "Pending";
            $attendence->save();

            return redirect()->back()->with('success','Attendence has been recorded!');
        }catch(\Throwable $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function attended()
    {
        $events = Event::whereHas('attended')->get();

        return view('members.events.index',compact('events'));
    }

    public function details($id)
    {
        $event = Event::find($id);

        return view('members.events.details',compact('event'));
    }

    public function certificate($event)
    {
        $event = Event::find($event);

        return view('members.events.certificate',compact('event'));
        
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
        $dompdf->stream($event->name.'.pdf');
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
            $x=420;
            // Dates are in the same month
            $formattedRange = $startDate->format('jS') . ' - ' . $endDate->format('jS F Y');
        } else {
            $x=480;
            // Dates are in different months
            $formattedRange = $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');
        }

        $image->text('Organized by the Institute of Procurement Professionals of Uganda on '.$formattedRange, $x, 400, function ($font) {
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
        $image->text('Membership Number: '.$membership_number, 450, 483, function ($font) {
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
        if ($this->device_attended()) {
            return redirect()->back()->with('error', 'You have already registered');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);


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

        \Auth::login($user);

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
        $attendence->save();

        if ($request->type == "event") {
            //get the logged in user
            $event = Event::find($request->id);
            if($event!=null){
                return $this->direct_event_attendance_certificate_parser($user, $request->id, "event");
            } else {
                return redirect()->back()->with('error', 'Event not found');
            }
        } else {
            $event = Cpd::find($request->id);
            if($event!=null){
                return $this->direct_cpd_attendance_certificate_parser($user, $request->id, "cpd");
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
        $user = User::find($user);

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
            $x=420;
            // Dates are in the same month
            $formattedRange = $startDate->format('jS') . ' - ' . $endDate->format('jS F Y');
        } else {
            $x=480;
            // Dates are in different months
            $formattedRange = $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');
        }


        $image->text('Organized by the Institute of Procurement Professionals of Uganda on '.$formattedRange, $x, 400, function ($font) {
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

        $eventFound = Cpd::find($event);


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
        $image->text('"'.$eventFound->topic.'"', 730, 690, function ($font) {
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
        $x=720;
        // Dates are in the same month
        $formattedRange = $startDate->format('jS') . ' - ' . $endDate->format('jS F Y');
        } else {
        $x=780;
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

        $image->text($eventFound->hours."CPD HOURS", 1400, 945, function ($font) {
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

}
