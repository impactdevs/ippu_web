<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Experience;

class EducationBackgroundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $experiences = Experience::where('user_id',\Auth::user()->id)
                                    ->where('type','Education')
                                    ->get();

        return view('members.education.index',compact('experiences'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('members.education.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['institution_name'=>'required','start_date'=>'required']);

        try{
            $experience = new Experience;
            $experience->title = $request->institution_name;
            $experience->type = "Education";
            $experience->start_date = $request->start_date;
            $experience->end_date = $request->end_date;
            $experience->points = str_replace(',', '', $request->points);
            $experience->field = $request->field;
            $experience->user_id = \Auth::user()->id;
            $experience->save();

            return redirect()->back()->with('success','Education Background has been saved');
        }catch(\Throwable $e){
            return redirect()->back()->with('error',$e->getMessage());
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
        $experience = Experience::find($id);
        return view('members.education.edit',compact('experience'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['institution_name'=>'required','start_date'=>'required']);

        try{
            $experience = Experience::find($id);
            $experience->title = $request->institution_name;
            $experience->type = "Education";
            $experience->start_date = $request->start_date;
            $experience->end_date = $request->end_date;
            $experience->points = str_replace(',', '', $request->points);
            $experience->field = $request->field;
            $experience->user_id = \Auth::user()->id;
            $experience->save();

            return redirect()->back()->with('success','Education Background has been updated');
        }catch(\Throwable $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $experience = Experience::find($id);

            $experience->delete();

            return redirect()->back()->with('success',"Operation successful");
        }catch(\Throwable $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
 