<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeadStage;
use App\Models\Pipeline;

class LeadStagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lead_stages = LeadStage::select('lead_stages.*', 'pipelines.name as pipeline')->join('pipelines', 'pipelines.id', '=', 'lead_stages.pipeline_id')->orderBy('lead_stages.pipeline_id')->orderBy('lead_stages.order')->get();
            $pipelines   = [];

            foreach($lead_stages as $lead_stage)
            {
                if(!array_key_exists($lead_stage->pipeline_id, $pipelines))
                {
                    $pipelines[$lead_stage->pipeline_id]                = [];
                    $pipelines[$lead_stage->pipeline_id]['name']        = $lead_stage['pipeline'];
                    $pipelines[$lead_stage->pipeline_id]['lead_stages'] = [];
                }
                $pipelines[$lead_stage->pipeline_id]['lead_stages'][] = $lead_stage;
            }

            return view('admin.lead_stages.index')->with('pipelines', $pipelines);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pipelines = Pipeline::get()->pluck('name', 'id');

        return view('admin.lead_stages.create')->with('pipelines', $pipelines);
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
            $lead_stage              = new LeadStage();
            $lead_stage->name        = $request->name;
            $lead_stage->pipeline_id = $request->pipeline_id;
            $lead_stage->created_by  = \Auth::user()->id;
            $lead_stage->save();

            activity()->performedOn($lead_stage)->log('created lead stage:'.$lead_stage->name);
            return redirect()->back()->with('success',"Lead stage has been saved successfully");
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
        $leadStage = LeadStage::find($id);
        $pipelines = Pipeline::get()->pluck('name', 'id');

        return view('admin.lead_stages.edit', compact('leadStage', 'pipelines'));
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
            $lead_stage              = LeadStage::find($id);
            $lead_stage->name        = $request->name;
            $lead_stage->pipeline_id = $request->pipeline_id;
            $lead_stage->save();
            activity()->performedOn($lead_stage)->log('updated lead stage:'.$lead_stage->name);
            return redirect()->back()->with('success',"Lead stage has been updated successfully");
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
            $lead_stage        = LeadStage::where('id', '=', $item)->first();
            $lead_stage->order = $key;
            $lead_stage->save();
        }
    }
}
