<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deal;
use App\Models\DealCall;
use App\Models\DealDiscussion;
use App\Models\DealEmail;
use App\Models\DealFile;
use App\Models\DealTask;
use App\Models\Label;
use App\Models\Pipeline;
use App\Models\ProductService;
use App\Models\Source;
use App\Models\Stage;
use App\Models\User;
use App\Models\UserDeal;
use App\Models\ClientDeal;

class DealsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usr      = \Auth::user();
        $pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();
        if($usr->default_pipeline)
            {
                $pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();
                if(!$pipeline)
                {
                    $pipeline = Pipeline::first();
                }
            }
            else
            {
                $pipeline = Pipeline::first();
            }

            $pipelines = Pipeline::get()->pluck('name', 'id');

            if($usr->type == 'client')
            {
                $id_deals = $usr->clientDeals->pluck('id');
            }
            else
            {
                $id_deals = $usr->deals->pluck('id');
            }

            $deals       = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->get();
            $curr_month  = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereMonth('created_at', '=', date('m'))->get();
            $curr_week   = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereBetween(
                'created_at', [
                                \Carbon\Carbon::now()->startOfWeek(),
                                \Carbon\Carbon::now()->endOfWeek(),
                            ]
            )->get();
            $last_30days = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30))->get();
            // Deal Summary
            $cnt_deal                = [];
            $cnt_deal['total']       = Deal::getDealSummary($deals);
            $cnt_deal['this_month']  = Deal::getDealSummary($curr_month);
            $cnt_deal['this_week']   = Deal::getDealSummary($curr_week);
            $cnt_deal['last_30days'] = Deal::getDealSummary($last_30days);

            return view('admin.deals.index', compact('pipelines', 'pipeline', 'cnt_deal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // if(\Auth::user()->can('create deal'))
        // {
            $clients      = User::where('user_type', 'Member')->get()->pluck('name', 'id');
            // $customFields = CustomField::where('module', '=', 'deal')->get();
            $customFields = [];

            return view('admin.deals.create', compact('clients', 'customFields'));
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission Denied.')], 401);
        // }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \DB::beginTransaction();
        $usr = \Auth::user();
        // if($usr->can('create deal'))
        // {
            $countDeal = Deal::all()->count();
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            // Default Field Value
            if($usr->default_pipeline)
            {
                $pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();
                if(!$pipeline)
                {
                    $pipeline = Pipeline::first();
                }
            }
            else
            {
                $pipeline = Pipeline::first();
            }

            $stage = Stage::where('pipeline_id', '=', $pipeline->id)->first();
            // End Default Field Value

            // Check if stage are available or not in pipeline.
            if(empty($stage))
            {
                return redirect()->back()->with('error', __('Please Create Stage for This Pipeline.'));
            }
            else
            {
                $deal        = new Deal();
                $deal->name  = $request->name;
                $deal->phone = $request->phone;
                if(empty($request->price))
                {
                    $deal->price = 0;
                }
                else
                {
                    $deal->price = $request->price;
                }
                $deal->pipeline_id = $pipeline->id;
                $deal->stage_id    = $stage->id;
                $deal->status      = 'Active';
                $deal->created_by  = $usr->id;
                $deal->save();

                //send email
                $clients = User::whereIN('id', array_filter($request->clients))->get()->pluck('email', 'id')->toArray();

                $dealArr = [
                    'deal_id' => $deal->id,
                    'name' => $deal->name,
                    'updated_by' => $usr->id,
                ];

                $dArr = [
                    'deal_name' => $deal->name,
                    'deal_pipeline' => $pipeline->name,
                    'deal_stage' => $stage->name,
                    'deal_status' => $deal->status,
                    'deal_price' => $deal->price,
                ];

                foreach(array_keys($clients) as $client)
                {
                    ClientDeal::create(
                        [
                            'deal_id' => $deal->id,
                            'client_id' => $client,
                        ]
                    );
                }

                if($usr->type=='company'){
                    $usrDeals = [
                        $usr->id,

                    ];
                }else{
                    $usrDeals = [
                        $usr->id,
                        $usr->ownerId()
                    ];
                }
                
                foreach($usrDeals as $usrDeal)
                {

                    UserDeal::create(
                        [
                            'user_id' => $usrDeal,
                            'deal_id' => $deal->id,
                        ]
                    );
                }

                // CustomField::saveData($deal, $request->customField);

                // Send Email
                // $setings = Utility::settings();
                // if($setings['deal_assigned'] == 1)
                // {
                //     $clients = User::whereIN('id', array_filter($request->clients))->get()->pluck('email', 'id')->toArray();
                //     $dealAssignArr = [
                //         'deal_name' => $deal->name,
                //         'deal_pipeline' => $pipeline->name,
                //         'deal_stage' => $stage->name,
                //         'deal_status' => $deal->status,
                //         'deal_price' => $deal->price,
                //     ];
                //     $resp = Utility::sendEmailTemplate('deal_assigned',  $clients, $dealAssignArr);
                //     return redirect()->back()->with('success', __('Deal successfully created!')  .(($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

                // }

                // //Slack Notification
                // // $setting  = Utility::settings(\Auth::user()->creatorId());
                // if(isset($setting['deal_notification']) && $setting['deal_notification'] ==1){
                //     $msg = __("New Deal created by").' '.\Auth::user()->name.'.';
                //     Utility::send_slack_msg($msg);
                // }

                // //Telegram Notification
                // $setting  = Utility::settings(\Auth::user()->creatorId());
                // if(isset($setting['telegram_deal_notification']) && $setting['telegram_deal_notification'] ==1){
                //     $msg = __("New Deal created by").' '.\Auth::user()->name.'.';
                //     Utility::send_telegram_msg($msg);
                // }
                \DB::commit();

                activity()->performedOn($deal)->log('created deal:'.$deal->name);

                return redirect()->back()->with('success', __('Deal successfully created!') );

            }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission Denied.'));
        // }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function changePipeline(Request $request)
    {
        $user                   = \Auth::user();
        $user->default_pipeline = $request->default_pipeline_id;
        $user->save();

        return redirect()->back();
    }

    public function deal_list()
    {
        $usr = \Auth::user();
        // if($usr->can('manage deal'))
        // {
            if($usr->default_pipeline)
            {
                $pipeline = Pipeline::where('id', '=', $usr->default_pipeline)->first();
                if(!$pipeline)
                {
                    $pipeline = Pipeline::first();
                }
            }
            else
            {
                $pipeline = Pipeline::first();
            }

            $pipelines = Pipeline::get()->pluck('name', 'id');

            if($usr->type == 'client')
            {
                $id_deals = $usr->clientDeals->pluck('id');
            }
            else
            {
                $id_deals = $usr->deals->pluck('id');
            }

            $deals       = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->get();
            $curr_month  = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereMonth('created_at', '=', date('m'))->get();
            $curr_week   = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereBetween(
                'created_at', [
                                \Carbon\Carbon::now()->startOfWeek(),
                                \Carbon\Carbon::now()->endOfWeek(),
                            ]
            )->get();
            $last_30days = Deal::whereIn('id', $id_deals)->where('pipeline_id', '=', $pipeline->id)->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30))->get();

            // Deal Summary
            $cnt_deal                = [];
            $cnt_deal['total']       = Deal::getDealSummary($deals);
            $cnt_deal['this_month']  = Deal::getDealSummary($curr_month);
            $cnt_deal['this_week']   = Deal::getDealSummary($curr_week);
            $cnt_deal['last_30days'] = Deal::getDealSummary($last_30days);

            // Deals
            if($usr->type == 'client')
            {
                $deals = Deal::select('deals.*')->join('client_deals', 'client_deals.deal_id', '=', 'deals.id')->where('client_deals.client_id', '=', $usr->id)->where('deals.pipeline_id', '=', $pipeline->id)->orderBy('deals.order')->get();
            }
            else
            {
                $deals = Deal::select('deals.*')->join('user_deals', 'user_deals.deal_id', '=', 'deals.id')->where('user_deals.user_id', '=', $usr->id)->where('deals.pipeline_id', '=', $pipeline->id)->orderBy('deals.order')->get();
            }

            return view('admin.deals.list', compact('pipelines', 'pipeline', 'deals', 'cnt_deal'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission Denied.'));
        // }
    }
}
