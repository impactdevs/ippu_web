<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pipeline;
use App\Models\Stage;

class StagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stages    = Stage::select('stages.*', 'pipelines.name as pipeline')->join('pipelines', 'pipelines.id', '=', 'stages.pipeline_id')->orderBy('stages.pipeline_id')->orderBy('stages.order')->get();
        $pipelines = [];

        foreach($stages as $stage)
        {
            if(!array_key_exists($stage->pipeline_id, $pipelines))
            {
                $pipelines[$stage->pipeline_id]           = [];
                $pipelines[$stage->pipeline_id]['name']   = $stage['pipeline'];
                $pipelines[$stage->pipeline_id]['stages'] = [];
            }
            $pipelines[$stage->pipeline_id]['stages'][] = $stage;
        }

        // return $pipelines;
        return view('admin.stages.index')->with('pipelines', $pipelines);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pipelines = Pipeline::all()->pluck('name', 'id');

        return view('admin.stages.create')->with('pipelines', $pipelines);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'pipeline_id' => 'required'
        ]);

        try{
            $stage              = new Stage();
            $stage->name        = $request->name;
            $stage->pipeline_id = $request->pipeline_id;
            $stage->created_by  = \Auth::user()->id;
            $stage->save();

            activity()->performedOn($stage)->log('created stage:'.$stage->name);

            return redirect()->back()->with('success',"Stage has been saved successfully");
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
        $stage = Stage::find($id);
        $pipelines = Pipeline::all()->pluck('name', 'id');

        return view('admin.stages.edit', compact('stage', 'pipelines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'pipeline_id' => 'required'
        ]);

        try{
            $stage              = Stage::find($id);
            $stage->name        = $request->name;
            $stage->pipeline_id = $request->pipeline_id;
            $stage->save();

            activity()->performedOn($stage)->log('updated stage:'.$stage->name);

            return redirect()->back()->with('success',"Stage has been updated successfully");
        }catch(\Throwable $e){
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

    public function order(Request $request)
    {
        $post = $request->all();
        foreach($post['order'] as $key => $item)
        {
            $stage        = Stage::where('id', '=', $item)->first();
            $stage->order = $key;
            $stage->save();
        }

        return $request;
    }
}
