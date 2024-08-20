<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;

class TaxesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if(\Auth::user()->can('manage constant tax'))
        // {
            $taxes = Tax::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('admin.taxes.index')->with('taxes', $taxes);
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


    public function create()
    {
        // if(\Auth::user()->can('create constant tax'))
        // {
            return view('admin.taxes.create');
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
    }

    public function store(Request $request)
    {
        // if(\Auth::user()->can('create constant tax'))
        // {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'rate' => 'required|numeric',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            try{
                $tax             = new Tax();
                $tax->name       = $request->name;
                $tax->rate       = $request->rate;
                $tax->created_by = \Auth::user()->creatorId();
                $tax->save();

                activity()->performedOn($tax)->log('created tax:'.$tax->name);

                return redirect()->back()->with('success', __('Tax rate successfully created.'));
            }catch(\Throwable $e){
                return redirect()->back()->with('error', $e->getMessage());
            }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function show(Tax $tax)
    {
        return redirect()->route('taxes.index');
    }


    public function edit(Tax $tax)
    {
        // if(\Auth::user()->can('edit constant tax'))
        // {
            if($tax->created_by == \Auth::user()->creatorId())
            {
                return view('admin.taxes.edit', compact('tax'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
    }


    public function update(Request $request, Tax $tax)
    {
        // if(\Auth::user()->can('edit constant tax'))
        // {
            if($tax->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                       'rate' => 'required|numeric',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $tax->name = $request->name;
                $tax->rate = $request->rate;
                $tax->save();

                activity()->performedOn($tax)->log('updated tax:'.$tax->name);
                return redirect()->route('taxes.index')->with('success', __('Tax rate successfully updated.'));
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

    public function destroy(Tax $tax)
    {
        if(\Auth::user()->can('delete constant tax'))
        {
            if($tax->created_by == \Auth::user()->creatorId())
            {
                $proposalData = ProposalProduct::whereRaw("find_in_set('$tax->id',tax)")->first();
                $billData     = BillProduct::whereRaw("find_in_set('$tax->id',tax)")->first();
                $invoiceData  = InvoiceProduct::whereRaw("find_in_set('$tax->id',tax)")->first();

                if(!empty($proposalData) || !empty($billData) || !empty($invoiceData))
                {
                    return redirect()->back()->with('error', __('this tax is already assign to proposal or bill or invoice so please move or remove this tax related data.'));
                }

                $tax->delete();

                activity()->performedOn($tax)->log('deleted tax:'.$tax->name);

                return redirect()->route('taxes.index')->with('success', __('Tax rate successfully deleted.'));
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
