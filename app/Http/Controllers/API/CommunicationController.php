<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Communication;
use App\Models\User;
use App\Models\UserCommunicationStatus;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $userId = null)
    {
        // If userId is provided, find the user; otherwise, use the authenticated user
        if ($userId) {
            $user = User::find($userId);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            auth()->login($user);
        } else {
            // If no userId, use the currently authenticated user
            $user = auth()->user();

            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }
        }

        // Fetch all communications and iterate over them to check if they are read or unread
        $communications = Communication::all();

        foreach ($communications as $communication) {
            $status = UserCommunicationStatus::where('user_id', $user->id)
                ->where('communication_id', $communication->id)
                ->first();

            // Set status field based on whether the user has read the communication
            $communication->status = $status ? true : false;
        }

        // Arrange the communications according to the latest
        $communications = $communications->sortByDesc('created_at');

        // Logout the user if a specific userId was used
        if ($userId) {
            auth()->logout();
        }

        // Return the resource as a JSON response
        return response()->json(['data' => $communications], 200);
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
        $resource = Communication::find($id);

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

    public function markAsRead(Request $request)
    {

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        auth()->login($user);

        // Find the corresponding record in user_communication_status and update its status to 'read'
        $status = UserCommunicationStatus::where('user_id', auth()->user()->id)
            ->where('communication_id', $request->message_id)
            ->first();

        if ($status) {
            $status->status = 'read';
            $status->save();
        } else {
            //just create a new record
            UserCommunicationStatus::create([
                'user_id' => auth()->user()->id,
                'communication_id' => $request->message_id,
                'status' => 'read'
            ]);
        }

        auth()->logout();

        return response()->json(['message' => 'Message marked as read'], 200);
    }

}
//gideonhasendo
