<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MemberReminder;

class RemindersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     try{
    //         $reminders = MemberReminder::query();

    //         if ($request->status) {
    //             $reminders->where('status',$request->status);
    //         }

    //         $reminders = $reminders->orderBy('id','desc')->get();

    //         if ($request->response && $request->response == "json") {
    //             return json_encode(['count'=>($reminders->count() > 100) ? "99+" : $reminders->count(),'data'=>$reminders]);
    //         }
    //         // $users = \App\Models\User::all();

    //         // foreach ($users as $user) {
    //         //     $notification = new MemberReminder;
    //         //     $notification->user_id = \Auth::user()->id;
    //         //     $notification->title = "A new user has signed up to IPPU";
    //         //     $notification->member_id = $user->id;
    //         //     $notification->reminder_date = $user->created_at;
    //         //     $notification->status = "Unread";
    //         //     $notification->save();
    //         // }
    //         return view('admin.reminders.index',compact('reminders'));
    //     }catch(\Throwable $ex){
    //         return $ex;
    //     }
    // }

    public function index(Request $request)
{
    try {
        // Start the query for MemberReminder
        $reminders = MemberReminder::query();

        // Apply a filter if a status is provided in the request
        if ($request->status) {
            $reminders->where('status', $request->status);
        }

        // Order the reminders by the latest created date
        $reminders = $reminders->orderBy('created_at', 'desc')->get();

        // Return JSON response if requested
        if ($request->response && $request->response == "json") {
            return json_encode([
                'count' => ($reminders->count() > 100) ? "99+" : $reminders->count(),
                'data' => $reminders
            ]);
        }

        // Return the view with reminders
        return view('admin.reminders.index', compact('reminders'));

    } catch (\Throwable $ex) {
        return response()->json(['error' => $ex->getMessage()], 500);
    }
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
    public function update(Request $request, string $id)
    {
        try{
            $reminder = MemberReminder::find($id);
        }catch(\Throwable $ex){
            return redirect()->back()->withErrors(['error'=>$ex->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function markReminder(Request $request)
    {
        $reminder = MemberReminder::find($request->id);
        $reminder->status = "Read";
        $reminder->save();

        return json_encode(['success'=>true]);
    }
}
