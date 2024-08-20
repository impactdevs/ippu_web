<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pipeline;

class PipelinesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pipelines = Pipeline::all();

        return view('admin.pipelines.index')->with('pipelines', $pipelines);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pipelines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        try{
            $pipeline             = new Pipeline();
            $pipeline->name       = $request->name;
            $pipeline->created_by = \Auth::user()->id;
            $pipeline->save();
            activity()->performedOn($pipeline)->log('created pipeline:'.$pipeline->name);
            return redirect()->back()->with('success','Pipeline has been saved successfully');
        } catch(\Throwable $e){
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
        $pipeline = Pipeline::find($id);

        // return $pipeline;

        return view('admin.pipelines.edit', compact('pipeline'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        try{
            $pipeline             = Pipeline::find($id);
            $pipeline->name       = $request->name;
            $pipeline->save();
            activity()->performedOn($pipeline)->log('updated pipeline:'.$pipeline->name);
            return redirect()->back()->with('success','Pipeline has been updated successfully');
        } catch(\Throwable $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
