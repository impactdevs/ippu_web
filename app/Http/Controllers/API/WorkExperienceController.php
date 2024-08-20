<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class WorkExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($userId)
    {
        $experiences = Experience::where('user_id',$userId)
                                    ->where('type','Employement')
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
        $request->validate(['title' => 'required', 'start_date' => 'required', 'description' => 'required', 'position' => 'required']);

        try {
            $experience = new Experience;
            $experience->title = $request->title;
            $experience->type = "Employement";
            $experience->start_date = $request->start_date;
            $experience->end_date = $request->end_date;
            $experience->description = $request->description;
            $experience->position = $request->position;
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
        //get the user id and work experience id
        $userId = $request->user_id;
        $experienceId = $request->experience_id;

        //find the work experience
        $experience = Experience::where('user_id',$userId)
                                    ->where('id',$experienceId)
                                    ->first();

        //check if the work experience exists
        if(!$experience){
            return response()->json([
                'message' => 'Work experience not found',
            ], 404);
        }

        //update the work experience
        $experience->title = $request->title;
        $experience->start_date = $request->start_date;
        $experience->end_date = $request->end_date;
        $experience->description = $request->description;
        $experience->position = $request->position;
        $experience->save();

        //return the updated work experience and a success message with a 200 status code
        return response()->json([
            'data' => $experience,
            'message' => 'Work experience updated successfully'
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
