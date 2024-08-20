<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormBuilder;
use App\Models\FormField;
use App\Models\FormFieldResponse;
use App\Models\User;
use App\Models\Pipeline;
use App\Models\FormResponse;
use Auth;

use Dompdf\Options;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Writer;

class FormBuildersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usr = \Auth::user();
        $forms = FormBuilder::get();

        return view('admin.form_builder.index', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.form_builder.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('form_builder.index')->with('error', $messages->first());
            }

            $form_builder             = new FormBuilder();
            $form_builder->name       = $request->name;
            $form_builder->code       = uniqid() . time();
            $form_builder->is_active  = $request->is_active;
            $form_builder->created_by = \Auth::user()->id;
            $form_builder->save();

            activity()->performedOn($form_builder)->log('created form builder:'.$form_builder->name);

            return redirect()->back()->with('success', __('Form successfully created.'));
        }  catch(\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }  
    }

    /**
     * Display the specified resource.
     */
    public function show(FormBuilder $formBuilder)
    {
         // if($formBuilder->created_by == \Auth::user()->id)
         //    {
                return view('admin.form_builder.show', compact('formBuilder'));
            // }
            // else
            // {
            //     return response()->json(['error' => __('Permission Denied.')], 401);
            // }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormBuilder $formBuilder)
    {
        // if($formBuilder->created_by == Auth::user()->id)
        //     {
                return view('admin.form_builder.edit', compact('formBuilder'));
            // }
            // else
            // {
            //     return response()->json(['error' => __('Permission Denied.')], 401);
            // }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FormBuilder $formBuilder)
    {
        $usr = \Auth::user();
        // if($usr->can('edit form builder'))
        // {
            // if($formBuilder->created_by == $usr->id)
            // {
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

                $formBuilder->name           = $request->name;
                $formBuilder->is_active      = $request->is_active;
                $formBuilder->is_lead_active = 0;
                $formBuilder->save();

                activity()->performedOn($formBuilder)->log('updated form builder:'.$formBuilder->name);

                return redirect()->back()->with('success', __('Form successfully updated.'));
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission Denied.'));
            // }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function formFieldBind($form_id)
    {
        $usr = \Auth::user();
        // if($usr->type == 'company')
        // {
            $form = FormBuilder::find($form_id);

            // if($form->created_by == $usr->id)
            // {
                $types = $form->form_field->pluck('name', 'id');

                $formField = FormFieldResponse::where('form_id', '=', $form_id)->first();

                // Get Users
                $users = User::get()->pluck('name', 'id');

                // Pipelines
                $pipelines = Pipeline::where('created_by', '=', $usr->id)->get()->pluck('name', 'id');

                return view('admin.form_builder.form_field', compact('form', 'types', 'formField', 'users', 'pipelines'));
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission Denied.'));
            // }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    // For Response
    public function viewResponse($form_id)
    {
        // if(Auth::user()->can('view form response'))
        // {
            $form = FormBuilder::find($form_id);
            // if($form->created_by == \Auth::user()->id)
            // {
                return view('admin.form_builder.response', compact('form'));
            // }
            // else
            // {
            //     return response()->json(['error' => __('Permission Denied . ')], 401);
            // }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    // For Response Detail
    public function responseDetail($response_id)
    {
        // if(Auth::user()->can('view form response'))
        // {
            $formResponse = FormResponse::find($response_id);
            $form         = FormBuilder::find($formResponse->form_id);
            // if($form->created_by == \Auth::user()->id)
            // {
                $response = json_decode($formResponse->response, true);

                return view('admin.form_builder.response_detail', compact('response'));
            // }
            // else
            // {
            //     return response()->json(['error' => __('Permission Denied . ')], 401);
            // }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    // For Front Side View
    public function formView($code)
    {

        if(!empty($code))
        {
            $form = FormBuilder::where('code', 'LIKE', $code)->first();

            if(!empty($form))
            {
                if($form->is_active == 1)
                {
                    $objFields = $form->form_field;

                    return view('admin.form_builder.form_view', compact('objFields', 'code', 'form'));
                }
                else
                {
                    return view('admin.form_builder.form_view', compact('code', 'form'));
                }
            }
            else
            {
                return redirect()->route('login')->with('error', __('Form not found please contact to admin.'));
            }
        }
        else
        {
            return redirect()->route('login')->with('error', __('Permission Denied.'));
        }
    }

    // For Front Side View Store
    public function formViewStore(Request $request)
    {
        // Get form
        $form = FormBuilder::where('code', 'LIKE', $request->code)->first();

        if(!empty($form))
        {
            $arrFieldResp = [];
            foreach($request->field as $key => $value)
            {
                $arrFieldResp[FormField::find($key)->name] = (!empty($value)) ? $value : '-';
            }

            // store response
            FormResponse::create(
                [
                    'form_id' => $form->id,
                    'response' => json_encode($arrFieldResp),
                ]
            );

            // in form convert lead is active then creat lead
            if($form->is_lead_active == 1)
            {
                $objField = $form->fieldResponse;

                // validation
                $email = User::where('email', 'LIKE', $request->field[$objField->email_id])->first();

                if(!empty($email))
                {
                    return redirect()->back()->with('error', __('Email already exist in our record.!'));
                }

                $usr   = User::find($form->created_by);
                $stage = LeadStage::where('pipeline_id', '=', $objField->pipeline_id)->where('created_by',$form->created_by)->first();

                if(!empty($stage))
                {
                    $lead              = new Lead();
                    $lead->name        = $request->field[$objField->name_id];
                    $lead->email       = $request->field[$objField->email_id];
                    $lead->subject     = $request->field[$objField->subject_id];
                    $lead->user_id     = $objField->user_id;
                    $lead->pipeline_id = $objField->pipeline_id;
                    $lead->stage_id    = $stage->id;
                    $lead->created_by  = $usr->creatorId();
                    $lead->date        = date('Y-m-d');
                    $lead->save();

                    $usrLeads = [
                        $usr->id,
                        $objField->user_id,
                    ];

                    foreach($usrLeads as $usrLead)
                    {
                        UserLead::create(
                            [
                                'user_id' => $usrLead,
                                'lead_id' => $lead->id,
                            ]
                        );
                    }
                }
            }

            return redirect()->back()->with('success', __('Data submit successfully.'));
        }
        else
        {
            return redirect()->route('login')->with('error', __('Something went wrong.'));
        }

    }

    // Store convert into lead modal
    public function bindStore(Request $request, $id)
    {

        $usr = Auth::user();
        // if($usr->type == 'company')
        // {
            $form                 = FormBuilder::find($id);
            $form->is_lead_active = $request->is_lead_active;
            $form->save();

            // if($form->created_by == $usr->creatorId())
            // {
                if($form->is_lead_active == 1)
                {
                    $validator = \Validator::make(
                        $request->all(), [
                                           'subject_id' => 'required',
                                           'name_id' => 'required',
                                           'email_id' => 'required',
                                           'user_id' => 'required',
                                           'pipeline_id' => 'required',
                                       ]
                    );

                    if($validator->fails())
                    {
                        $messages = $validator->getMessageBag();

                        // if validation failed then make status 0
                        $form->is_lead_active = 0;
                        $form->save();

                        return redirect()->back()->with('error', $messages->first());
                    }

                    if(!empty($request->form_response_id))
                    {
                        // if record already exists then update it.
                        $field_bind = FormFieldResponse::find($request->form_response_id);
                        $field_bind->update(
                            [
                                'subject_id' => $request->subject_id,
                                'name_id' => $request->name_id,
                                'email_id' => $request->email_id,
                                'user_id' => $request->user_id,
                                'pipeline_id' => $request->pipeline_id,
                            ]
                        );
                    }
                    else
                    {
                        // Create Field Binding record on form_field_responses tbl
                        FormFieldResponse::create(
                            [
                                'form_id' => $request->form_id,
                                'subject_id' => $request->subject_id,
                                'name_id' => $request->name_id,
                                'email_id' => $request->email_id,
                                'user_id' => $request->user_id,
                                'pipeline_id' => $request->pipeline_id,
                            ]
                        );
                    }
                }

                return redirect()->back()->with('success', __('Setting saved successfully!'));
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission Denied.'));
            // }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    // Field curd
    public function fieldCreate($id)
    {
        $usr = \Auth::user();
        // if($usr->can('create form field'))
        // {
            $formbuilder = FormBuilder::find($id);
            // if($formbuilder->created_by == $usr->id)
            // {
                $types = FormBuilder::$fieldTypes;

                return view('admin.form_builder.field_create', compact('types', 'formbuilder'));
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission Denied.'));
            // }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function fieldStore($id, Request $request)
    {
        $usr = \Auth::user();
        // if($usr->can('create form field'))
        // {
        \DB::beginTransaction();
            $formbuilder = FormBuilder::find($id);
            // if($formbuilder->created_by == $usr->id)
            // {
                $names = $request->name;
                $types = $request->type;

                foreach($names as $key => $value)
                {
                    if(!empty($value))
                    {
                        // create form field
                        FormField::create(
                            [
                                'form_id' => $formbuilder->id,
                                'name' => $value,
                                'type' => $types[$key],
                                'created_by' => $usr->id,
                            ]
                        );
                    }
                }
                \DB::commit();
                return redirect()->back()->with('success', __('Field successfully created.'));
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission Denied.'));
            // }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function fieldEdit($id, $field_id)
    {
        $usr = \Auth::user();
        // if($usr->can('edit form field'))
        // {
            $form = FormBuilder::find($id);
            // if($form->created_by == $usr->creatorId())
            // {
                $form_field = FormField::find($field_id);

                if(!empty($form_field))
                {
                    $types = FormBuilder::$fieldTypes;

                    return view('form_builder.field_edit', compact('form_field', 'types', 'form'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Field not found.'));
                }
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission Denied.'));
            // }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

    public function fieldUpdate($id, $field_id, Request $request)
    {
        $usr = \Auth::user();
        // if($usr->can('edit form field'))
        // {
            $form = FormBuilder::find($id);
            // if($form->created_by == $usr->creatorId())
            // {
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

                $field = FormField::find($field_id);
                $field->update(
                    [
                        'name' => $request->name,
                        'type' => $request->type,
                    ]
                );

                return redirect()->back()->with('success', __('Form successfully updated.'));
            // }
            // else
            // {
            //     return redirect()->back()->with('error', __('Permission Denied.'));
            // }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission denied.'));
        // }
    }

            public function generate_form_qr(Request $request, formField $formField)
        {
          
            $formField = FormField::where('form_id', $request->form_id)->get();

            if($formField -> isNotEmpty()){
            //dd($formField, $id );
            $url = $request->url;

            
            //decode the url
            $url = urldecode($url);
           
            // Create options for QR code generation
            $options = new Options();
            $options->set('defaultFont', 'Courier');
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);

            // Create renderer for PNG image output
            $renderer = new ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(100),
                new ImagickImageBackEnd()
            );

            // Create writer to generate QR code
            $writer = new Writer($renderer);

            // Generate the QR code as a PNG image
            $qrCode = $writer->writeString($url);

            // Prompt the user to save the image
            header('Content-Disposition: attachment; filename="qr_code.png"');
            header('Content-Type: image/png');
            echo $qrCode;
            } else{
                 //echo"missing some fields....";
                // dd($formField, $id);
                return redirect()->back()->with('error',' Missing fields detected, please add fields.');
                
            }
        }

}
