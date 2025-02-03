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

    // public function generate_certificate($event)
    // {
    //     $manager = new ImageManager(new Driver());
    //     //read the image from the public folder
    //     $image = $manager->read(public_path('images/certificate-template.jpeg'));

    //     $event = Event::find($event);
    //     $user = auth()->user();


    //     $image->text('PRESENTED TO', 420, 250, function ($font) {
    //         $font->filename(public_path('fonts/Roboto-Bold.ttf'));
    //         $font->color('#405189');
    //         $font->size(12);
    //         $font->align('center');
    //         $font->valign('middle');
    //     });

    //     $image->text($user->name, 420, 300, function ($font) {
    //         $font->filename(public_path('fonts/Roboto-Bold.ttf'));
    //         $font->color('#b01735');
    //         $font->size(20);
    //         $font->align('center');
    //         $font->valign('middle');
    //         $font->lineHeight(1.6);
    //     });

    //     $image->text('FOR ATTENDING THE', 420, 340, function ($font) {
    //         $font->filename(public_path('fonts/Roboto-Bold.ttf'));
    //         $font->color('#405189');
    //         $font->size(12);
    //         $font->align('center');
    //         $font->valign('middle');
    //         $font->lineHeight(1.6);
    //     });

    //     //add event name
    //     $image->text($event->name, 420, 370, function ($font) {
    //         $font->filename(public_path('fonts/Roboto-Regular.ttf'));
    //         $font->color('#008000');
    //         $font->size(22);
    //         $font->align('center');
    //         $font->valign('middle');
    //         $font->lineHeight(1.6);
    //     });


    //     $startDate = Carbon::parse($event->start_date);
    //     $endDate = Carbon::parse($event->end_date);

    //     if ($startDate->month === $endDate->month) {
    //         $x = 420;
    //         // Dates are in the same month
    //         $formattedRange = $startDate->format('jS') . ' - ' . $endDate->format('jS F Y');
    //     } else {
    //         $x = 480;
    //         // Dates are in different months
    //         $formattedRange = $startDate->format('jS F Y') . ' - ' . $endDate->format('jS F Y');
    //     }


    //     $image->text('Organized by the Institute of Procurement Professionals of Uganda on ' . $formattedRange, $x, 400, function ($font) {
    //         $font->filename(public_path('fonts/Roboto-Regular.ttf'));
    //         $font->color('#405189');
    //         $font->size(12);
    //         $font->align('center');
    //         $font->valign('middle');
    //         $font->lineHeight(1.6);
    //     });

    //     //add membership number
    //     $image->text('MembershipNumber: ' . $user->membership_number ?? "N/A", 450, 483, function ($font) {
    //         $font->filename(public_path('fonts/Roboto-Bold.ttf'));
    //         $font->color('#405189');
    //         $font->size(12);
    //         $font->align('center');
    //         $font->valign('middle');
    //         $font->lineHeight(1.6);
    //     });

    //     $image->toPng();

    //     $filePath = public_path('images/certificate-generated' . $user->id . '.png');

    //     //get the image url
    //     $imageUrl = url('images/certificate-generated' . $user->id . '.png');
    //     //save the image to the public folder
    //     $image->save($filePath);

    //     return response([
    //         'message' => 'Certificate generated successfully',
    //         'data' => [
    //             'certificate' => $imageUrl,
    //         ]
    //     ]);
    // }

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
