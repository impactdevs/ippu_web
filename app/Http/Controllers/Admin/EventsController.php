<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use App\Models\Attendence;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ) {

        $events = Event::query();

        if(!empty($request->search)) {
            $events->where('name', 'like', '%' . $request->search . '%');
        }

        $events = $events->get();

        //dd($events);

        return view('admin.events.index', compact('events'));
    }

    public function create() {

        return view('admin.events.create', []);
    }


    public function store(Request $request) {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            // 'rate' => 'required',
            // 'member_rate' => 'required',
            'points' => 'required|integer',
            'event_type' => 'required|string|in:Normal,Annual', // Ensuring event_type is either 'Normal' or 'Annual'
            'theme' => 'nullable|string|max:255', // Only required if event_type is 'Annual'
            'organizing_committee' => 'nullable|string|max:255',
            'annual_event_date' => 'nullable|date',
            'place' => 'nullable|string|max:255',
            'attachment_name' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx', // File validation
            'banner_name' => 'nullable|file|mimes:jpeg,png,jpg'
        ]);



        try {
            $event = new Event();

            // Handle attachment upload
            if ($request->hasFile('attachment_name')) {
                $file = $request->file('attachment_name');
                $extension = $file->extension();
                $filename = time().rand(100, 1000).'.'.$extension;
                $storage = \Storage::disk('public')->putFileAs('attachments/', $file, $filename);

                if (!$storage) {
                    return redirect()->back()->with('error', 'Unable to upload Attachment');
                }

                $event->attachment_name = $filename;
            }

            // Handle banner upload
            if ($request->hasFile('banner_name')) {
                $file = $request->file('banner_name');
                $extension = $file->extension();
                $filename = time().rand(100, 1000).'.'.$extension;
                $storage = \Storage::disk('public')->putFileAs('banners/', $file, $filename);

                if (!$storage) {
                    return redirect()->back()->with('error', 'Unable to upload Banner');
                }

                $event->banner_name = $filename;
            }

            // Assign event details
            $event->name = $request->name;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->details = $request->details;
            $event->points = $request->points;
            $event->rate = is_null($request->rate)?"0":str_replace(',', '', $request->rate);
            $event->member_rate = is_null($request->member_rate)?"0":str_replace(',', '', $request->member_rate);
            $event->event_type = $request->event_type;
            $event->theme = $request->event_type === 'Annual' ? $request->theme : null; // Only set theme for Annual events
            $event->organizing_committee = $request->organizing_committee;
            $event->annual_event_date = $request->event_type === 'Annual' ? $request->annual_event_date : null; // Only set date for Annual events
            $event->place = $request->place;

            $event->save();

            activity()->performedOn($event)->log('created event:'.$event->name);

            return redirect()->route('events.index')->with('success', __('Event created successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('events.create')->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function show(Event $event,) {

        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event,) {

        return view('admin.events.edit', compact('event'));
    }


    public function update(Request $request, Event $event,) {

        $request->validate([]);

        try {

            if ($request->hasFile('attachment_name')) {
                $file =  $request->file('attachment_name');
                $extension = $file->extension();

                $filename = time().rand(100,1000).'.'.$extension;

                $storage = \Storage::disk('public')->putFileAs(
                    'attachments/',
                    $file,
                    $filename
                );

                if (!$storage) {
                    return redirect()->back()->with('error','Unable to upload Attachment');
                }

                if (\Storage::disk('public')->exists('attachments/'.$event->attachment_name)) {
                    \Storage::disk('public')->delete('attachments/'.$event->attachment_name);
                }


                $event->attachment_name = $filename;
            }

            if ($request->hasFile('banner_name')) {
                $file =  $request->file('banner_name');
                $extension = $file->extension();

                $filename = time().rand(100,1000).'.'.$extension;

                $storage = \Storage::disk('public')->putFileAs(
                    'banners/',
                    $file,
                    $filename
                );

                if (!$storage) {
                    return redirect()->back()->with('error','Unable to upload Attachment');
                }

                if (\Storage::disk('public')->exists('banners/'.$event->banner_name)) {
                    \Storage::disk('public')->delete('banners/'.$event->banner_name);
                }

                $event->banner_name = $filename;
            }

            $event->name = $request->name;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->rate = str_replace(',', '', $request->rate);
            $event->member_rate = str_replace(',', '', $request->member_rate);
            $event->points = $request->points;
            $event->details = $request->details;
            $event->save();

            activity()->performedOn($event)->log('updated event:'.$event->name);

            return redirect()->route('events.index', [])->with('success', __('Event edited successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('events.edit', compact('event'))->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Event $event,) {

        try {
            $event->delete();

            return redirect()->route('events.index', [])->with('success', __('Event deleted successfully'));
        } catch (\Throwable $e) {
            return redirect()->route('events.index', [])->with('error', 'Cannot delete Event: ' . $e->getMessage());
        }
    }

    public function attendence($attendence_id,$status)
    {
        try {
            $attendence = \App\Models\Attendence::find($attendence_id);

            \DB::beginTransaction();
            $attendence->status = $status;
            $attendence->save();

            if ($status == "Attended") {
                if ($attendence->event->points > 0) {
                    $user = \App\Models\User::find($attendence->user_id);

                    $user->points +=$attendence->event->points;
                    $user->save();

                    $points = new \App\Models\Point;
                    $points->type = "Event";
                    $points->user_id = $user->id;
                    $points->points = $attendence->event->points;
                    $points->awarded_by = \Auth::user()->id;
                    $points->save();

                    $rate = ($user->subscribed == 1) ? $attendence->event->members_rate : $attendence->event->rate;

                    if ($rate > 0) {
                        $payment = new \App\Models\Payment;
                        $payment->type = "Event";
                        $payment->amount = $rate;
                        $payment->balance = 0;
                        $payment->user_id = $user->id;
                        $payment->received_by = \Auth::user()->id;
                        $payment->event_id = $attendence->event->id;
                        $payment->save();
                    }
                }
            }

            activity()->performedOn($attendence->event)->log('booked '.$attendence->user->name.' CPD attendence - '.$attendence->event->name);

            \DB::commit();

            return redirect()->back()->with('success','Attendence has been updated successfully');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }


    // In your controller method
public function storeAttendance(Request $request)
{
    // dd($request->all());
    $validated = $request->validate([
        'event_id' => 'required|exists:events,id',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'membership_number' => 'nullable'
    ]);

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
            'event_id' => $validated['event_id'],
            'status'=>"Attended",
            'membership_number' => $validated['membership_number']
        ]);

        return response()->json(['success' => true, 'message' => 'Attendee registered successfully.', 'password' => $password]);
    }
    else{

        Attendence::create([
            'user_id' => $user->id,
            'event_id' => $validated['event_id'],
            'status'=>"Attended",
            'membership_number' => $validated['membership_number']
        ]);

        return response()->json(['success' => true, 'message' => 'Attendee registered successfully.']);

    }

}
}
