<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\CreditNote;
use App\Models\Customer;

class CreditNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if(\Auth::user()->can('manage credit note'))
        // {
            $invoices = Invoice::where('created_by', \Auth::user()->creatorId())->get();

            return view('admin.creditNote.index', compact('invoices'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function create($invoice_id)
    {
        // if(\Auth::user()->can('create credit note'))
        // {

            $invoiceDue = Invoice::where('id', $invoice_id)->first();

            return view('admin.creditNote.create', compact('invoiceDue', 'invoice_id'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function store(Request $request, $invoice_id)
    {
        // if(\Auth::user()->can('create credit note'))
        // {
            $validator = \Validator::make(
                $request->all(), [
                                   'amount' => 'required|numeric',
                                   'date' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $invoiceDue = Invoice::where('id', $invoice_id)->first();
            if($request->amount > $invoiceDue->getDue())
            {
                return redirect()->back()->with('error', 'Maximum ' . \Auth::user()->priceFormat($invoiceDue->getDue()) . ' credit limit of this invoice.');
            }
            $invoice = Invoice::where('id', $invoice_id)->first();

            $credit              = new CreditNote();
            $credit->invoice     = $invoice_id;
            $credit->customer    = $invoice->customer_id;
            $credit->date        = $request->date;
            $credit->amount      = $request->amount;
            $credit->description = $request->description;
            $credit->save();

            // Utility::userBalance('customer', $invoice->customer_id, $request->amount, 'debit');

        //     if($users == 'customer')
        // {
            $user = Customer::find($invoice->customer_id);
        // }
        // else
        // {
        //     $user = Vender::find($invoice->customer_id);
        // }

        if(!empty($user))
        {
            // if($type == 'credit')
            // {
            //     $oldBalance    = $user->balance;
            //     $user->balance = $oldBalance + $amount;
            //     $user->save();
            // }
            // elseif($type == 'debit')
            // {
                $oldBalance    = $user->balance;
                $user->balance = $oldBalance - $request->amount;
                $user->save();
            // }
        }

            activity()->performedOn($credit)->log('Created credit note with ID:'.$credit->id);
            return redirect()->back()->with('success', __('Credit Note successfully created.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }


    public function edit($invoice_id, $creditNote_id)
    {
        if(\Auth::user()->can('edit credit note'))
        {

            $creditNote = CreditNote::find($creditNote_id);

            return view('creditNote.edit', compact('creditNote'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, $invoice_id, $creditNote_id)
    {

        if(\Auth::user()->can('edit credit note'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'amount' => 'required|numeric',
                                   'date' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $invoiceDue = Invoice::where('id', $invoice_id)->first();

            if($request->amount > $invoiceDue->getDue())
            {
                return redirect()->back()->with('error', 'Maximum ' . \Auth::user()->priceFormat($invoiceDue->getDue()) . ' credit limit of this invoice.');
            }

            $credit = CreditNote::find($creditNote_id);
            Utility::userBalance('customer', $invoiceDue->customer_id, $credit->amount, 'credit');
            $credit->date        = $request->date;
            $credit->amount      = $request->amount;
            $credit->description = $request->description;
            $credit->save();

            Utility::userBalance('customer', $invoiceDue->customer_id, $request->amount, 'debit');
            activity()->performedOn($credit)->log('edited credit note with ID:'.$credit->id);

            return redirect()->back()->with('success', __('Credit Note successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy($invoice_id, $creditNote_id)
    {
        if(\Auth::user()->can('delete credit note'))
        {

            $creditNote = CreditNote::find($creditNote_id);
            $creditNote->delete();

            Utility::userBalance('customer', $creditNote->customer, $creditNote->amount, 'credit');

            activity()->performedOn($credit)->log('deleted credit note with ID:'.$credit->id);

            return redirect()->back()->with('success', __('Credit Note successfully deleted.'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customCreate()
    {
        // if(\Auth::user()->can('create credit note'))
        // {

            $invoices = Invoice::where('created_by', \Auth::user()->creatorId())->get()->pluck('invoice_id', 'id');

            return view('admin.creditNote.custom_create', compact('invoices'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function customStore(Request $request)
    {
        // if(\Auth::user()->can('create credit note'))
        // {
            $validator = \Validator::make(
                $request->all(), [
                                   'invoice' => 'required|numeric',
                                   'amount' => 'required|numeric',
                                   'date' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $invoice_id = $request->invoice;
            $invoiceDue = Invoice::where('id', $invoice_id)->first();

            if($request->amount > $invoiceDue->getDue())
            {
                return redirect()->back()->with('error', 'Maximum ' . \Auth::user()->priceFormat($invoiceDue->getDue()) . ' credit limit of this invoice.');
            }
            $invoice             = Invoice::where('id', $invoice_id)->first();
            $credit              = new CreditNote();
            $credit->invoice     = $invoice_id;
            $credit->customer    = $invoice->customer_id;
            $credit->date        = $request->date;
            $credit->amount      = $request->amount;
            $credit->description = $request->description;
            $credit->save();

            // Utility::userBalance('customer', $invoice->customer_id, $request->amount, 'debit');

           $user = Customer::find($invoice->customer_id);

            $oldBalance    = $user->balance;
            $user->balance = $oldBalance - $request->amount;
            $user->save();

            activity()->performedOn($credit)->log('Created credit note with ID:'.$credit->id);

            return redirect()->back()->with('success', __('Credit Note successfully created.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function getinvoice(Request $request)
    {
        $invoice = Invoice::where('id', $request->id)->first();

        echo json_encode($invoice->getDue());
    }
}
