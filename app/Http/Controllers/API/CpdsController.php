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
    public function index($userId)
    {
        $cpds = Cpd::all();

        $cpdsWithAttendance = [];

        foreach ($cpds as $cpd){
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

        foreach ($cpds as $cpd){
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
        foreach ($cpds as $cpd){
            $cpd->attendance_status = Attendence::where('cpd_id', $cpd->id)->first()->status;
        }

        return response()->json([
            'data' => $cpds,
        ]);
    }

    public function confirm_attendence(Request $request)
    {
        try {
            $attendence = new Attendence;
            $attendence->user_id = $request->user_id;
            $attendence->cpd_id = $request->cpd_id;
            $attendence->type = "CPD";
            $attendence->status = "Pending";
            $attendence->save();

            return response()->json([
                'success' => true,
                'message' => 'CPD has been recorded!',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }

        public function generate_certificate($event){
        $manager = new ImageManager(new Driver());
        //read the image from the public folder
        $image = $manager->read(public_path('images/cpd-certificate-template.jpg'));

        $event = Cpd::find($event);
        $user = auth()->user();

        $image->text($event->code, 173, 27, function ($font) {
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
        $image->text('"'.$event->topic.'"', 730, 690, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#000000');
            $font->size(20);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });


        $startDate = Carbon::parse($event->start_date);
        $endDate = Carbon::parse($event->end_date);

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

        $image->text($event->hours."CPD HOURS", 1400, 945, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#000000');
            $font->size(17);
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
