<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Source;

class SourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sources = Source::all();

        return view('admin.sources.index')->with('sources', $sources);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sources.create');
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
            $source             = new Source();
            $source->name       = $request->name;
            $source->created_by = \Auth::user()->id;
            $source->save();

            activity()->performedOn($source)->log('created source:'.$source->name);

            return redirect()->back()->with('success',"Source has been saved successfully");
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
        $source = Source::find($id);
        return view('admin.sources.edit', compact('source'));
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
            $source             = Source::find($id);
            $source->name       = $request->name;
            $source->save();

            activity()->performedOn($source)->log('updated source:'.$source->name);

            return redirect()->back()->with('success',"Source has been updated successfully");
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
}
