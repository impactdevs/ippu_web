<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomField;

class CustomFieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if(\Auth::user()->can('manage constant custom field'))
        // {
            $custom_fields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('admin.customFields.index', compact('custom_fields'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission Denied.'));
        // }
    }


    public function create()
    {
        // if(\Auth::user()->can('create constant custom field'))
        // {
            $types   = CustomField::$fieldTypes;
            $modules = CustomField::$modules;

            return view('admin.customFields.create', compact('types', 'modules'));
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission Denied.')], 401);
        // }
    }


    public function store(Request $request)
    {
        // if(\Auth::user()->can('create constant custom field'))
        // {

            try{
                $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:40',
                                   'type' => 'required',
                                   'module' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $custom_field             = new CustomField();
            $custom_field->name       = $request->name;
            $custom_field->type       = $request->type;
            $custom_field->module     = $request->module;
            $custom_field->created_by = \Auth::user()->creatorId();
            $custom_field->save();

            activity()->performedOn($custom_field)->log('created custom field :'.$custom_field->name);

            return redirect()->back()->with('success', __('Custom Field successfully created!'));
        }catch(\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission Denied.'));
        // }
    }


    public function show(CustomField $customField)
    {
        return redirect()->route('custom-field.index');
    }

    public function edit(CustomField $customField)
    {
        // if(\Auth::user()->can('edit constant custom field'))
        // {
            if($customField->created_by == \Auth::user()->creatorId())
            {
                $types   = CustomField::$fieldTypes;
                $modules = CustomField::$modules;

                return view('admin.customFields.edit', compact('customField', 'types', 'modules'));
            }
            else
            {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        // }
        // else
        // {
        //     return response()->json(['error' => __('Permission Denied.')], 401);
        // }
    }


    public function update(Request $request, CustomField $customField)
    {
        // if(\Auth::user()->can('edit constant custom field'))
        // {

            try{
                if($customField->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:40',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $customField->name = $request->name;
                $customField->save();

                activity()->performedOn($custom_field)->log('updated custom field :'.$custom_field->name);

                return redirect()->back()->with('success', __('Custom Field successfully updated!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }catch(\Throwable $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('Permission Denied.'));
        // }
    }


    public function destroy(CustomField $customField)
    {
        if(\Auth::user()->can('delete constant custom field'))
        {
            if($customField->created_by == \Auth::user()->creatorId())
            {
                $customField->delete();

                activity()->performedOn($customField)->log('updated custom field :'.$customField->name);

                return redirect()->route('custom-field.index')->with('success', __('Custom Field successfully deleted!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
