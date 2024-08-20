<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class EducationBackgroundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($userId)
    {
        $experiences = Experience::where('user_id',$userId)
                                    ->where('type','Education')
                                    ->get();
        return response()->json([
            'data' => $experiences,
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
        $request->validate(['title' => 'required', 'start_date' => 'required']);

        try {
            $experience = new Experience;
            $experience->title = $request->title;
            $experience->type = "Education";
            $experience->start_date = $request->start_date;
            $experience->end_date = $request->end_date;
            $experience->points = str_replace(',', '', $request->points);
            $experience->field = $request->field;
            $experience->user_id = $request->user_id;
            $experience->save();

            return response()->json([
                'data' => $experience,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request)
    {
        $userId = $request->user_id;
        $educationBackgroundId = $request->education_background_id;

        $educationBackground = Experience::where('user_id',$userId)
                                            ->where('id',$educationBackgroundId)
                                            ->first();

        //check if the education background exists
        if(!$educationBackground){
            return response()->json([
                'message' => 'Education background not found'
            ],404);
        }

        $educationBackground->title = $request->title;
        $educationBackground->start_date = $request->start_date;
        $educationBackground->end_date = $request->end_date;
        $educationBackground->points = str_replace(',', '', $request->points);
        $educationBackground->field = $request->field;
        $educationBackground->save();

        return response()->json([
            'data' => $educationBackground,
            'message' => 'Education background updated successfully'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}
