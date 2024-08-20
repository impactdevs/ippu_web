<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContractType;

class ContractTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = ContractType::get();
        return view('admin.contractType.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.contractType.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        try{
             $types            = new ContractType();
            $types->name       = $request->name;
            $types->created_by = \Auth::user()->id;
            $types->save();
            activity()->performedOn($types)->log('Created contract type '.$types->name);
            return redirect()->back()->with('success', __('Contract Type successfully created.'));
        }catch(\Throwable $e){
            return redirect()->back()->with('success', $e->getMessage());
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
        $contractType = ContractType::find($id);
        return view('admin.contractType.edit', compact('contractType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        try{
             $types            = ContractType::find($id);
            $types->name       = $request->name;
            $types->created_by = \Auth::user()->id;
            $types->save();
            activity()->performedOn($types)->log('Created contract type '.$types->name);
            return redirect()->back()->with('success', __('Contract Type successfully updated.'));
        }catch(\Throwable $e){
            return redirect()->back()->with('success', $e->getMessage());
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
