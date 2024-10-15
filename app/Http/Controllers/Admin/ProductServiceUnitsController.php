<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductServiceUnit;

class ProductServiceUnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if(\Auth::user()->can('manage constant unit'))
        // {
            $units = ProductServiceUnit::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('admin.productServiceUnit.index', compact('units'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function create()
    {
        // if(\Auth::user()->can('create constant unit'))
        // {
            return view('admin.productServiceUnit.create');
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
    }


    public function store(Request $request)
    {
        // if(\Auth::user()->can('create constant unit'))
        // {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            try{
                $category             = new ProductServiceUnit();
                $category->name       = $request->name;
                $category->created_by = \Auth::user()->creatorId();
                $category->save();

                activity()->performedOn($category)->log('created unit:'.$category->name);

                return redirect()->back()->with('success', __('Unit successfully created.'));
            }catch(\Throwable $e){
                return redirect()->back()->with('error', $e->getMessage());
            }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


    public function edit($id)
    {
        // if(\Auth::user()->can('edit constant unit'))
        // {
            $unit = ProductServiceUnit::find($id);

            return view('admin.productServiceUnit.edit', compact('unit'));
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
    }


    public function update(Request $request, $id)
    {
        // if(\Auth::user()->can('edit constant unit'))
        // {
            $unit = ProductServiceUnit::find($id);
            if($unit->created_by == \Auth::user()->creatorId())
            {
                try{
                    $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $unit->name = $request->name;
                $unit->save();

                activity()->performedOn($unit)->log('updated unit:'.$unit->name);
                return redirect()->back()->with('success', __('Unit successfully updated.'));
            }catch(\Throwable $e){
                return redirect()->back()->with('error',$e->getMessage());
            }
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function destroy($id)
    {
        if(\Auth::user()->can('delete constant unit'))
        {
            $unit = ProductServiceUnit::find($id);
            if($unit->created_by == \Auth::user()->creatorId())
            {
                $units = ProductService::where('unit_id', $unit->id)->first();
                if(!empty($units))
                {
                    return redirect()->back()->with('error', __('this unit is already assign so please move or remove this unit related data.'));
                }
                $unit->delete();

                activity()->performedOn($unit)->log('deleted unit:'.$unit->name);

                return redirect()->route('product-unit.index')->with('success', __('Unit successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
