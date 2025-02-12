<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\Cpd;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CpdsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($userId = null)
    {
        $cpds = Cpd::all();
        $cpdsWithAttendance = [];

        foreach ($cpds as $cpd) {
            $attendanceRequest = $userId ? Attendence::where('cpd_id', $cpd->id)
                ->where('user_id', $userId)
                ->exists() : false;

            if ($attendanceRequest) {
                // Use first() to get a single instance instead of a collection
                $attendance = Attendence::where('cpd_id', $cpd->id)
                    ->where('user_id', $userId)
                    ->first();

                if (!is_null($attendance->booking_fee)) {
                    // Now you can access the booking_fee property
                    $cpd->balance = ($cpd->normal_rate) - ($attendance->booking_fee);
                } else {
                    $cpd->balance = null;
                }
            } else {
                $cpd->balance = null;
            }

            $cpd->attendance_request = $attendanceRequest;

            array_push($cpdsWithAttendance, $cpd);
        }

        return response()->json([
            'data' => $cpdsWithAttendance,
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
        $resource = Cpd::find($id);

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
        $cpds = Cpd::where('start_date', '>=', date('Y-m-d'))->get();

        $cpdsWithAttendance = [];

        foreach ($cpds as $cpd) {
            $attendanceRequest = Attendence::where('cpd_id', $cpd->id)
                ->where('user_id', $userId)
                ->exists();

            $cpd->attendance_request = $attendanceRequest;

            array_push($cpdsWithAttendance, $cpd);
        }

        return response()->json([
            'data' => $cpdsWithAttendance,
        ]);
    }

    public function attended()
    {
        // Query events associated with the specified user and where 'type' is 'Event'
        $cpds = Cpd::whereHas('attended')->get();

        //attach attendance details
        foreach ($cpds as $cpd) {
            $cpd->attendance_status = Attendence::where('cpd_id', $cpd->id)->first()->status;
        }

        return response()->json([
            'data' => $cpds,
        ]);
    }

    public function confirm_attendence(Request $request)
    {
        try {
            // Check if the attendance record already exists for the current user and event
            $attendance = Attendence::where('user_id', $request->user_id)
                ->where('cpd_id', $request->cpd_id)
                ->first();

            if ($attendance) {
                // If record exists, update the booking fee by adding the new amount to the existing one
                $attendance->booking_fee += $request->amount; // Add the new booking fee to the existing one
                $attendance->save();

                return  response()->json([
                    'success' => true,
                    'message' => 'CPD has been recorded!',
                    'balance' => (Cpd::find($request->cpd_id)->normal_rate) - ($attendance->booking_fee),
                ]);
            } else {
                $attendence = new Attendence;
                $attendence->user_id = $request->user_id;
                $attendence->cpd_id = $request->cpd_id;
                $attendence->type = "CPD";
                $attendence->status = "Pending";
                $attendence->booking_fee = $request->amount;
                $attendence->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'CPD has been recorded!',
                'balance' => (Cpd::find($request->cpd_id)->normal_rate) - ($attendence->booking_fee),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                //response code be 500
                400
            ]);
        }
    }

    public function generate_certificate($event)
    {
        $manager = new ImageManager(new Driver());
        //read the image from the public folder
        // Load the certificate template
        $image = $manager->read(public_path('images/cpd_template.jpeg'));
        $event = Cpd::find($event);
        $user = auth()->user();



        // Add details to the certificate
        $image->text($event->code, 180, 85, function ($font) {
            $font->file(public_path('fonts/Roboto-Bold.ttf'));
            $font->size(20);
            $font->color('#000000');
            $font->align('center');
        });

        $image->text($user->name, 780, 625, function ($font) {
            $font->file(public_path('fonts/GreatVibes-Regular.ttf'));
            $font->size(45);
            $font->color('#1F45FC');
            $font->align('center');
        });

        $image->text($event->topic, 850, 770, function ($font) {
            $font->file(public_path('fonts/Roboto-Bold.ttf'));
            $font->size(25);
            $font->color('#000000');
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
