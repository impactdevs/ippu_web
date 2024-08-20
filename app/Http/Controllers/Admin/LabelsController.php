<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Label;
use App\Models\Pipeline;

class LabelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labels   = Label::select('labels.*', 'pipelines.name as pipeline')->join('pipelines', 'pipelines.id', '=', 'labels.pipeline_id')->orderBy('labels.pipeline_id')->get();
            $label = Label::get();
            $pipelines = [];

            foreach($labels as $label)
            {
                if(!array_key_exists($label->pipeline_id, $pipelines))
                {
                    $pipelines[$label->pipeline_id]           = [];
                    $pipelines[$label->pipeline_id]['name']   = $label['pipeline'];
                    $pipelines[$label->pipeline_id]['labels'] = [];
                }
                $pipelines[$label->pipeline_id]['labels'][] = $label;
            }

            return view('admin.labels.index')->with('pipelines', $pipelines);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pipelines = Pipeline::get()->pluck('name', 'id');
            $colors = Label::$colors;

        return view('admin.labels.create')->with('pipelines', $pipelines)->with('colors', $colors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'color' => 'required',
            'pipeline_id' => 'required',
        ]);

        try{
            $label              = new Label();
            $label->name        = $request->name;
            $label->color       = $request->color;
            $label->pipeline_id = $request->pipeline_id;
            $label->created_by  = \Auth::user()->id;
            $label->save();

            activity()->performedOn($label)->log('created lable:'.$label->name);

            return redirect()->back()->with('success', __('Label successfully saved!'));
        }catch(\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
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
        $label = Label::find($id);
        $pipelines = Pipeline::get()->pluck('name', 'id');
        $colors    = Label::$colors;

        return view('admin.labels.edit', compact('label', 'pipelines', 'colors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'color' => 'required',
            'pipeline_id' => 'required',
        ]);

        try{
            $label              = Label::find($id);
            $label->name        = $request->name;
            $label->color       = $request->color;
            $label->pipeline_id = $request->pipeline_id;
            $label->save();

            activity()->performedOn($label)->log('updated lable:'.$label->name);

            return redirect()->back()->with('success', __('Label successfully updated!'));
        }catch(\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
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
