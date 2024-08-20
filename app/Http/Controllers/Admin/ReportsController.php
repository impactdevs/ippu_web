<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendence;
use App\Models\Cpd;
use App\Models\Event;
use App\Models\Payment;
use App\Models\AccountType;

class ReportsController extends Controller
{
    public function members(Request $request)
    {
        $members = [];

        if ($request->type) {
            if ($request->type == '*') {
                $members = User::where('user_type','Member')->get();
            }else{
                $type = ($request->type == "1") ? 1 : 0;
                $members = User::where('user_type','Member')->where('subscribed',$type)->get();
            }
        }

        return view('admin.reports.members',compact('members'));
    }

    public function points(Request $request)
    {
        $members = [];

        if ($request->year) {
            $year = $request->year;
            $members = User::where('user_type','Member')
                            ->withSum(['points_details' => function($query) use ($year){
                                $query->where( \DB::raw('YEAR(created_at)'), '=', $year );
                            }],'points')->get();
        }

        // return $members;
        return view('admin.reports.points',compact('members'));
    }

    public function cpds(Request $request){
        $cpds = Cpd::all();

        $attendences = [];

        if ($request->cpd) {
            $attendences = Attendence::query()->cpds();
            if ($request->cpd == "*") {
                $attendences->with('cpd','user','cpd_payment')->get();
            }else{
                $attendences->where('cpd_id',$request->cpd)->with('cpd','user','cpd_payment');
            }
            if ($request->status) {
                $attendences->where('status',$request->status);
            }

            $attendences = $attendences->get();
        }

        return view('admin.reports.cpds',compact('cpds','attendences'));
    }

    public function events(Request $request){
        $cpds = Event::all();

        $attendences = [];

        if ($request->cpd) {
            $attendences = Attendence::query()->events();
            if ($request->cpd == "*") {
                $attendences->with('event','user','event_payment')->get();
            }else{
                $attendences->where('event_id',$request->cpd)->with('cpd','user','event_payment');
            }

            if ($request->status) {
                $attendences->where('status',$request->status);
            }

            $attendences = $attendences->get();
        }

        return view('admin.reports.events',compact('cpds','attendences'));
    }

    public function payments(Request $request)
    {
        $payments = [];

        if ($request->type) {
            $payments = Payment::query();
            if ($request->type != '*') {
                $payments->where('type',$request->type);
            }

            $payments->whereBetween('created_at', 
                [$request->from, date("Y-m-d",strtotime($request->to."+1 day"))]);

            $payments = $payments->with('user','receiver')->get();
        }

        return view('admin.reports.payments',compact('payments'));
    }

    public function account_types(Request $request)
    {
        $account_types = AccountType::withCount('users')->get();

        return view('admin.reports.account_types',compact('account_types'));
    }
}
