<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

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

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        return view('admin.events.create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ) {

        $request->validate([]);

        try {

            $event = new Event();

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

                $event->banner_name = $filename;
            }
            $event->name = $request->name;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->details = $request->details;
            $event->points = $request->points;
            $event->rate = str_replace(',', '', $request->rate);
            $event->member_rate = str_replace(',', '', $request->member_rate);
            $event->save();

            activity()->performedOn($event)->log('created event:'.$event->name);

            return redirect()->route('events.index', [])->with('success', __('Event created successfully.'));
        } catch (\Throwable $e) {
            return redirect()->route('events.create', [])->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event,) {

        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event,) {

        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Event $event
     *
     * @return \Illuminate\Http\Response
     */
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
}
