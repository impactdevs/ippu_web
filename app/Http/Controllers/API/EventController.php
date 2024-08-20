<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($userId)
    {
        $events = Event::all();

        $eventsWithAttendance = [];

        foreach ($events as $event){
            $attendanceRequest = Attendence::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->exists();

            $event->attendance_request = $attendanceRequest;

            array_push($eventsWithAttendance, $event);
        }

        return response()->json([
            'data' => $eventsWithAttendance,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the resource by ID
        $resource = Event::find($id);

        // Check if the resource exists
        if (!$resource) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        // Return the resource as a JSON response
        return response()->json(['data' => $resource], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function upcoming($userId)
    {
        $events = Event::where('start_date', '>=', date('Y-m-d'))->get();

        $eventsWithAttendance = [];

        foreach ($events as $event){
            $attendanceRequest = Attendence::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->exists();

            $event->attendance_request = $attendanceRequest;

            array_push($eventsWithAttendance, $event);
        }

        return response()->json([
            'data' => $events,
        ]);
    }

    public function attended(string $userId)
    {
        // Query events associated with the specified user and where 'type' is 'Event'
        $events = Event::whereHas('attendedEvents', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('type', 'Event');
        })->get();

        //get the first element of attended_events property and get status attribute of it, create a status property for events and assign the value of the status attribute to it
        foreach ($events as $event) {
            $event->status = $event->attendedEvents->first()->status;
        }

        return response()->json([
            'data' => $events,
        ]);
    }

    public function confirm_attendence(Request $request)
    {
        try {
            $attendence = new Attendence;
            $attendence->user_id = $request->user_id;
            $attendence->event_id = $request->event_id;
            $attendence->type = "Event";
            $attendence->status = "Pending";
            $attendence->save();

            return response()->json([
                'message' => 'Attendence Confirmed',
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
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
        $image->text('MembershipNumber: ' . $user->membership_number ?? "N/A", 450, 483, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(12);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->toPng();

        $filePath = public_path('images/certificate-generated' . $user->id . '.png');

        //get the image url
        $imageUrl = url('images/certificate-generated' . $user->id . '.png');
        //save the image to the public folder
        $image->save($filePath);

        return response([
            'message' => 'Certificate generated successfully',
            'data' => [
                'certificate' => $imageUrl,
            ]
        ]);
    }
}
