<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pipeline;
use App\Models\LeadActivityLog;
use App\Models\LeadStage;
use App\Models\Lead;
use App\Models\User;
use App\Models\UserLead;
use App\Models\Label;
use App\Models\Source;
use App\Models\ProductService;
use App\Models\Deal;

class LeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(\Auth::user()->default_pipeline)
        {
            $pipeline = Pipeline::where('id', '=', \Auth::user()->default_pipeline)->first();
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
        return view('admin.leads.index', compact('pipelines', 'pipeline'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('id', '!=', \Auth::user()->id)->get()->pluck('name', 'id');
        $users->prepend(__('Select User'), '');

        return view('admin.leads.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
           'subject' => 'required',
           'name' => 'required',
           'email' => 'required|unique:leads,email',
       ]);
        $usr = \Auth::user();
        try{
            \DB::beginTransaction();
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

            $stage = LeadStage::where('pipeline_id', '=', $pipeline->id)->first();
            // End Default Field Value

            if(empty($stage))
            {
                return redirect()->back()->with('error', __('Please Create Stage for This Pipeline.'));
            }
            else
            {
                $lead              = new Lead();
                $lead->name        = $request->name;
                $lead->email       = $request->email;
                $lead->phone       = $request->phone;
                $lead->subject     = $request->subject;
                $lead->user_id     = $request->user_id;
                $lead->pipeline_id = $pipeline->id;
                $lead->stage_id    = $stage->id;
                $lead->created_by  = $usr->id;
                $lead->date        = date('Y-m-d');
                $lead->save();


                if($request->user_id!=\Auth::user()->id){
                    $usrLeads = [
                        $usr->id,
                        $request->user_id,
                    ];
                }else{
                    $usrLeads = [
                        $request->user_id,
                    ];
                }

                foreach($usrLeads as $usrLead)
                {
                    UserLead::create(
                        [
                            'user_id' => $usrLead,
                            'lead_id' => $lead->id,
                        ]
                    );
                }

                $leadArr = [
                    'lead_id' => $lead->id,
                    'name' => $lead->name,
                    'updated_by' => $usr->id,
                ];
                $lArr    = [
                    'lead_name' => $lead->name,
                    'lead_email' => $lead->email,
                    'lead_pipeline' => $pipeline->name,
                    'lead_stage' => $stage->name,
                ];

                $usrEmail = User::find($request->user_id);

                $lArr    = [
                    'lead_name' => $lead->name,
                    'lead_email' => $lead->email,
                    'lead_pipeline' => $pipeline->name,
                    'lead_stage' => $stage->name,
                ];
                \DB::commit();

                activity()->performedOn($lead)->log('created lead:'.$lead->name);
                return redirect()->back()->with('success', __('Lead successfully created!') );
            }
        }catch(\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage() );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        if($lead->is_active)
        {
            $calenderTasks = [];
            $deal          = Deal::where('id', '=', $lead->is_converted)->first();
            $stageCnt      = LeadStage::where('pipeline_id', '=', $lead->pipeline_id)->get();
            $i             = 0;
            foreach($stageCnt as $stage)
            {
                $i++;
                if($stage->id == $lead->stage_id)
                {
                    break;
                }
            }
            $precentage = number_format(($i * 100) / count($stageCnt));

            return view('admin.leads.show', compact('lead', 'calenderTasks', 'deal', 'precentage'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // if(\Auth::user()->can('edit lead'))
        // {
        //     if($lead->created_by == \Auth::user()->creatorId())
        //     {
            $lead = Lead::find($id);
                $pipelines = Pipeline::get()->pluck('name', 'id');
                $pipelines->prepend(__('Select Pipeline'), '');
                $sources        = Source::get()->pluck('name', 'id');
                $products       = ProductService::get()->pluck('name', 'id');
                $users          = User::where('user_type', '!=', 'Member')->where('id', '!=', \Auth::user()->id)->get()->pluck('name', 'id');
                $lead->sources  = explode(',', $lead->sources);
                $lead->products = explode(',', $lead->products);

                return view('admin.leads.edit', compact('lead', 'pipelines', 'sources', 'products', 'users'));
        //     }
        //     else
        //     {
        //         return response()->json(['error' => __('Permission Denied.')], 401);
        //     }
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission Denied.')], 401);
        // }
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

    public function order(Request $request)
    {
        $usr        = \Auth::user();
        $post       = $request->all();
        $lead       = Lead::find($post['lead_id']);
        $lead_users = $lead->users->pluck('email', 'id')->toArray();

        if($lead->stage_id != $post['stage_id'])
        {
            $newStage = LeadStage::find($post['stage_id']);

            LeadActivityLog::create(
                [
                    'user_id' => \Auth::user()->id,
                    'lead_id' => $lead->id,
                    'log_type' => 'Move',
                    'remark' => json_encode(
                        [
                            'title' => $lead->name,
                            'old_status' => $lead->stage->name,
                            'new_status' => $newStage->name,
                        ]
                    ),
                ]
            );

            $leadArr = [
                'lead_id' => $lead->id,
                'name' => $lead->name,
                'updated_by' => $usr->id,
                'old_status' => $lead->stage->name,
                'new_status' => $newStage->name,
            ];

            $lArr = [
                'lead_name' => $lead->name,
                'lead_email' => $lead->email,
                'lead_pipeline' => $lead->pipeline->name,
                'lead_stage' => $lead->stage->name,
                'lead_old_stage' => $lead->stage->name,
                'lead_new_stage' => $newStage->name,
            ];

                // Send Email
                // Utility::sendEmailTemplate('Move Lead', $lead_users, $lArr);
        }

        foreach($post['order'] as $key => $item)
        {
            $lead           = Lead::find($item);
            $lead->order    = $key;
            $lead->stage_id = $post['stage_id'];
            $lead->save();
        }

    }

    public function lead_list()
    {
        $usr = \Auth::user();

        
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
        $leads     = Lead::select('leads.*')->join('user_leads', 'user_leads.lead_id', '=', 'leads.id')->where('leads.pipeline_id', '=', $pipeline->id)->orderBy('leads.order')->get();

        return view('admin.leads.list', compact('pipelines', 'pipeline', 'leads'));
        
    }

    public function labels($id)
    {
        // if(\Auth::user()->can('edit lead'))
        // {
            $lead = Lead::find($id);
            // if($lead->created_by == \Auth::user()->creatorId())
            // {
                $labels   = Label::where('pipeline_id', '=', $lead->pipeline_id)->get();
                $selected = $lead->labels();
                if($selected)
                {
                    $selected = $selected->pluck('name', 'id')->toArray();
                }
                else
                {
                    $selected = [];
                }

                return view('admin.leads.labels', compact('lead', 'labels', 'selected'));
            // }
            // else
            // {
            //     return response()->json(['error' => __('Permission Denied.')], 401);
            // }
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission Denied.')], 401);
        // }
    }

    public function labelStore($id, Request $request)
    {
        // if(\Auth::user()->can('edit lead'))
        // {
            $leads = Lead::find($id);
            // if($leads->created_by == \Auth::user()->creatorId())
            // {
                if($request->labels)
                {
                    $leads->labels = implode(',', $request->labels);
                }
                else
                {
                    $leads->labels = $request->labels;
                }
                $leads->save();

                activity()->performedOn($lead)->log('updated lead:'.$lead->name);

                return redirect()->back()->with('success', __('Labels successfully updated!'));
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission Denied.'));
            // }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission Denied.'));
        // }
    }

    public function json(Request $request)
    {
        $lead_stages = new LeadStage();
        if($request->pipeline_id && !empty($request->pipeline_id))
        {


            $lead_stages = $lead_stages->where('pipeline_id', '=', $request->pipeline_id);
            $lead_stages = $lead_stages->get()->pluck('name', 'id');
        }
        else
        {
            $lead_stages = [];
        }

        return response()->json($lead_stages);
    }

    public function sourceEdit($id)
    {
        // if(\Auth::user()->can('edit lead'))
        // {
            $lead = Lead::find($id);
            // if($lead->created_by == \Auth::user()->creatorId())
            // {
                $sources = Source::get();

                $selected = $lead->sources();
                if($selected)
                {
                    $selected = $selected->pluck('name', 'id')->toArray();
                }

                return view('admin.leads.sources', compact('lead', 'sources', 'selected'));
            // }
            // else
            // {
            //     return response()->json(['error' => __('Permission Denied.')], 401);
            // }
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission Denied.')], 401);
        // }
    }

     public function sourceUpdate($id, Request $request)
    {
        // if(\Auth::user()->can('edit lead'))
        // {
            $usr        = \Auth::user();
            $lead       = Lead::find($id);
            $lead_users = $lead->users->pluck('id')->toArray();

            // if($lead->created_by == \Auth::user()->creatorId())
            // {
                if(!empty($request->sources) && count($request->sources) > 0)
                {
                    $lead->sources = implode(',', $request->sources);
                }
                else
                {
                    $lead->sources = "";
                }

                $lead->save();

                LeadActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'lead_id' => $lead->id,
                        'log_type' => 'Update Sources',
                        'remark' => json_encode(['title' => 'Update Sources']),
                    ]
                );

                $leadArr = [
                    'lead_id' => $lead->id,
                    'name' => $lead->name,
                    'updated_by' => $usr->id,
                ];

                activity()->performedOn($lead)->log('update lead:'.$lead->name);

                return redirect()->back()->with('success', __('Sources successfully updated!'))->with('status', 'sources');
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'sources');
            // }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission Denied.'))->with('status', 'sources');
        // }
    }

    public function showConvertToDeal($id)
    {

        $lead         = Lead::findOrFail($id);
        $exist_client = User::where('email', '=', $lead->email)->first();
        $clients      = User::get();

        return view('admin.leads.convert', compact('lead', 'exist_client', 'clients'));
    }
}
