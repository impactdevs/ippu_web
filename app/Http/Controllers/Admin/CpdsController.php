<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use App\Models\Attendence;
use App\Models\Cpd;
use Dompdf\Dompdf;
use App\Models\User;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer;
use Carbon\Carbon;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
class CpdsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, )
    {

        $cpds = Cpd::query();
        //dd($cpds);

        if (!empty($request->search)) {
            //dd("here in search");
            $cpds->where('code', 'like', '%' . $request->search . '%');
        }

        $cpds = $cpds->get();

        //dd(count($cpds));


        return view('admin.cpds.index', compact('cpds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.cpds.create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, )
    {

        $request->validate(["code" => "required", "topic" => "required", "content" => "required", "hours" => "required", "target_group" => "required", "location" => "required", "start_date" => "required", "end_date" => "required", "resource" => "required", "status" => "required"]);

        try {

            $cpd = new Cpd();

            if ($request->hasFile('resource')) {
                $file = $request->file('resource');
                $extension = $file->extension();

                $filename = time() . rand(100, 1000) . '.' . $extension;

                $storage = \Storage::disk('public')->putFileAs(
                    'attachments/',
                    $file,
                    $filename
                );

                if (!$storage) {
                    return redirect()->back()->with('error', 'Unable to upload resource');
                }

                $cpd->resource = $filename;
            }

            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $extension = $file->extension();

                $filename = time() . rand(100, 1000) . '.' . $extension;

                $storage = \Storage::disk('public')->putFileAs(
                    'banners/',
                    $file,
                    $filename
                );

                if (!$storage) {
                    return redirect()->back()->with('error', 'Unable to upload banner');
                    // print \Storage::disk('public')->getError();  // Uncomment this line

                }

                $cpd->banner = $filename;
            }
            $cpd->code = $request->code;
            $cpd->topic = $request->topic;
            $cpd->content = $request->content;
            $cpd->hours = $request->hours;
            $cpd->points = $request->points;
            $cpd->target_group = $request->target_group;
            $cpd->location = $request->location;
            $cpd->start_date = $request->start_date;
            $cpd->end_date = $request->end_date;
            $cpd->normal_rate = str_replace(',', '', $request->normal_rate);
            $cpd->members_rate = str_replace(',', '', $request->members_rate);
            $cpd->status = $request->status;
            $cpd->type = $request->type;
            $cpd->save();

            activity()->performedOn($cpd)->log('Created CPD:' . $cpd->topic);

            return redirect('admin/cpds')->with('success', __('Cpd created successfully.'));
        } catch (\Throwable $e) {
            //dd($e->getMessage());
            return redirect()->back()->withInput($request->input())->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Cpd $cpd
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Cpd $cpd, )
    {
        //dd($cpd->confirmed);
        return view('admin.cpds.show', compact('cpd'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Cpd $cpd
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Cpd $cpd, )
    {

        return view('admin.cpds.edit', compact('cpd'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cpd $cpd, )
    {

        $request->validate(["code" => "required", "topic" => "required", "content" => "required", "hours" => "required", "target_group" => "required", "location" => "required", "start_date" => "required", "end_date" => "required", "points" => "required", "status" => "required"]);

        try {
            if ($request->hasFile('resource')) {
                $file = $request->file('resource');
                $extension = $file->extension();

                $filename = time() . rand(100, 1000) . '.' . $extension;

                $storage = \Storage::disk('public')->putFileAs(
                    'attachments/',
                    $file,
                    $filename
                );

                if (!$storage) {
                    return redirect()->back()->with('error', 'Unable to upload resource');
                }

                if (\Storage::disk('public')->exists('attachments/' . $cpd->resource)) {
                    \Storage::disk('public')->delete('attachments/' . $cpd->resource);
                }

                $cpd->resource = $filename;
            }

            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $extension = $file->extension();

                $filename = time() . rand(100, 1000) . '.' . $extension;

                $storage = \Storage::disk('public')->putFileAs(
                    'banners/',
                    $file,
                    $filename
                );

                if (!$storage) {
                    //return redirect()->back()->with('error','Unable to upload banners');
                    // dd('Unable to upload banner: ' . $e->getMessage());

                }

                if (\Storage::disk('public')->exists('banners/' . $cpd->resource)) {
                    \Storage::disk('public')->delete('banners/' . $cpd->resource);
                }

                $cpd->banner = $filename;
            }

            $cpd->code = $request->code;
            $cpd->topic = $request->topic;
            $cpd->content = $request->content;
            $cpd->hours = $request->hours;
            $cpd->points = $request->points;
            $cpd->target_group = $request->target_group;
            $cpd->location = $request->location;
            $cpd->start_date = $request->start_date;
            $cpd->end_date = $request->end_date;
            $cpd->normal_rate = str_replace(',', '', $request->normal_rate);
            $cpd->members_rate = str_replace(',', '', $request->members_rate);
            $cpd->status = $request->status;
            $cpd->type = $request->type;
            $cpd->save();

            activity()->performedOn($cpd)->log('edited CPD:' . $cpd->topic);

            return redirect()->route('cpds.index', [])->with('success', __('Cpd edited successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('cpds.edit', compact('cpd'))->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Cpd $cpd
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cpd $cpd, )
    {

        try {
            $cpd->delete();

            activity()->performedOn($cpd)->log('deleted CPD:' . $cpd->topic);

            return redirect()->route('cpds.index', [])->with('success', __('Cpd deleted successfully'));
        } catch (\Throwable $e) {
            return redirect()->route('cpds.index', [])->with('error', 'Cannot delete Cpd: ' . $e->getMessage());
        }
    }

    public function attendence($attendence_id, $status)
    {
        try {
            $attendence = \App\Models\Attendence::find($attendence_id);
            \DB::beginTransaction();

            $attendence->status = $status;
            $attendence->save();


            if ($status == "Attended") {
                if ($attendence->cpd->points > 0) {
                    $user = \App\Models\User::find($attendence->user_id);

                    $user->points += $attendence->cpd->points;
                    $user->save();

                    $points = new \App\Models\Point;
                    $points->type = "CPD";
                    $points->user_id = $user->id;
                    $points->points = $attendence->cpd->points;
                    $points->awarded_by = \Auth::user()->id;
                    $points->save();

                    $rate = ($user->subscribed == 1) ? $attendence->cpd->members_rate : $attendence->cpd->normal_rate;

                    if ($rate > 0) {
                        $payment = new \App\Models\Payment;
                        $payment->type = "CPD";
                        $payment->amount = $rate;
                        $payment->balance = 0;
                        $payment->user_id = $user->id;
                        $payment->received_by = \Auth::user()->id;
                        $payment->cpd_id = $attendence->cpd->id;
                        $payment->save();
                    }

                    activity()->performedOn($attendence->cpd)->log('Approved ' . $user->name . ' CPD attendence - ' . $attendence->cpd->topic);
                }
            } else {
                activity()->performedOn($attendence->cpd)->log('booked ' . $attendence->user->name . ' CPD attendence - ' . $attendence->cpd->topic);
            }
            \DB::commit();

            return redirect()->back()->with('success', 'Attendence has been updated successfully');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function generate_qr($type, $id)
    {
        $url = config('app.url') . "/direct_attendence/" . $type . "/" . $id;

        // Create options for QR code generation
        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        // Create renderer for PNG image output
        $renderer = new ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(100),
            new ImagickImageBackEnd()
        );

        // Create writer to generate QR code
        $writer = new Writer($renderer);

        // Generate the QR code as a PNG image
        $qrCode = $writer->writeString($url);

        // Prompt the user to save the image
        header('Content-Disposition: attachment; filename="qr_code.png"');
        header('Content-Type: image/png');
        echo $qrCode;
    }


    public function payment_proof($name)
    {
        return view('admin.cpds.payment_proof', compact('name'));
    }

    public function calender()
    {
        return view('calender.index');
    }

    public function getcalender(Request $request)
    {

        $events = array(
            array(
                'title' => 'Event 1 343',
                'start' => '2024-02-12'
            ),
            array(
                'title' => 'Event 2',
                'start' => '2024-02-15',
                'end' => '2024-02-17'
            )
            // Add more events as needed
        );

        $events = [];

        $cpds = Cpd::all();

        foreach ($cpds as $cpd) {
            array_push($events, [
                'title' => $cpd->topic,
                'start' => $cpd->start_date,
                'end' => $cpd->end_date,
                'className' => 'bg-primary',
            ]);
        }

        $eve = \App\Models\Event::all();

        foreach ($eve as $event) {
            array_push($events, [
                'title' => $event->name,
                'start' => $event->start_date,
                'end' => $event->end_date,
                'className' => 'bg-warning',
            ]);
        }
        header('Content-Type: application/json');
        echo json_encode($events);
    }

    public function downloadCertificate(Request $request, $cpd_id, $user_id)
    {
        //  dd("am here");
        try {
            $manager = new ImageManager(new Driver());

            $event = Cpd::find($cpd_id);
            $user = \App\Models\User::find($user_id);

            // Load the certificate template
            $image = $manager->make(public_path('images/cpd-certificate-template.jpg'));

              // Add details to the certificate
              $image->text($event->code, 180, 85, function ($font) {
                $font->file(public_path('fonts/Roboto-Bold.ttf'));
                $font->size(20);
                $font->color('#000000');
                $font->align('center');
            });
            $image->text(Str::title($user->name), 780, 625, function ($font) {
                $font->file(public_path('fonts/POPPINS-BOLD.TTF'));
                $font->size(45);
                $font->color('#1F45FC');
                $font->align('center');
            });

            $image->text(Str::title(Str::lower($user->name)), 780, 625, function ($font) {
                $font->file(public_path('fonts/POPPINS-BOLD.TTF'));
                $font->size(45);
                $font->color('#1F45FC');
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

            // Return the certificate for download
            return response()->download($path)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // Handle any errors that occur during the certificate generation
            return redirect()->back()->with('error', 'An error occurred while generating the certificate: ' . $e->getMessage());
        }
    }

    // In your controller method
    public function storeAttendance(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'event_id' => 'required|exists:cpds,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'membership_number' => 'nullable'
        ]);

        //dd($validated);
        // check if the user already exists in the user table
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            $password = Str::random(9);
            $password = Hash::make($password);
            $account_type_id = AccountType::first()->id;
            // create a new user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                //'membership_number' => $validated['membership_number'],
                'account_type_id' => $account_type_id,
                'password' => $password,

            ]);

            Attendence::create([
                'user_id' => $user->id,
                'cpd_id' => $validated['event_id'],
                'status' => "Attended",
                'membership_number' => $validated['membership_number']
            ]);

            return response()->json(['success' => true, 'message' => 'Attendee registered successfully.', 'password' => $password]);
        } else {

            Attendence::create([
                'user_id' => $user->id,
                'cpd_id' => $validated['event_id'],
                'status' => "Attended",
                'membership_number' => $validated['membership_number']
            ]);

            return response()->json(['success' => true, 'message' => 'Attendee registered successfully.']);

        }

    }
}
