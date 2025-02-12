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
    public function index($userId = null)
    {
        $events = Event::all();
        $eventsWithAttendance = [];

        foreach ($events as $event) {
            $attendanceRequest = $userId ? Attendence::where('event_id', $event->id)
                ->where('user_id', $userId)
                ->exists() : false;

                if ($attendanceRequest) {
                    // Use first() to get a single instance instead of a collection
                    $attendance = Attendence::where('event_id', $event->id)
                        ->where('user_id', $userId)
                        ->first();

                    if ($attendance) {
                        // Now you can access the booking_fee property
                        $event->balance = ($event->rate) - ($attendance->booking_fee);
                    } else {
                        $event->balance = null;
                    }
                } else {
                    $event->balance = null;
                }


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

        foreach ($events as $event) {
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

            // Check if the attendance record already exists for the current user and event
            $attendance = Attendence::where('user_id', $request->user_id)
                ->where('event_id', $request->event_id)
                ->first();

            if ($attendance) {
                // If record exists, update the booking fee by adding the new amount to the existing one
                $attendance->booking_fee += $request->amount; // Add the new booking fee to the existing one
                $attendance->save();

                return  response()->json([
                    'success' => true,
                    'message' => 'event has been recorded!',
                ]);
            } else {
                $attendence = new Attendence;
                $attendence->user_id = $request->user_id;
                $attendence->event_id = $request->event_id;
                $attendence->type = "Event";
                $attendence->status = "Pending";
                $attendence->booking_fee = $request->amount;

                $attendence->save();

                return response()->json([
                    'message' => 'Attendence Confirmed',
                ]);
            }

        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function generate_certificate($event_id)
    {
        try {
            $manager = new ImageManager(new Driver());
            $event = Event::find($event_id);
            $user = auth()->user();
            $name = $user->name;
            $membership_number = $user->membership_number;
            $id = $user->id;
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

            //get the image url
            $imageUrl = url('images/' . $file_name);

            return response([
                'message' => 'Certificate generated successfully',
                'data' => [
                    'certificate' => $imageUrl,
                ]
            ]);

            // return redirect()->back()->with('success', 'Certificate generated and emailed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while generating the certificate: ' . $e->getMessage());
        }
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
}
