<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountType;

class AccountTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ) {

        $accountTypes = AccountType::query();

        if(!empty($request->search)) {
            $accountTypes->where('name', 'like', '%' . $request->search . '%');
        }

        $accountTypes = $accountTypes->paginate(10);

        return view('admin.account_types.index', compact('accountTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        return view('admin.account_types.create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ) {

        $request->validate(["name" => "required",'rate'=>'required','description'=>'required']);

        try {

            $accountType = new AccountType();
            $accountType->name = $request->name;
            $accountType->rate = str_replace(",", "", $request->rate);
            $accountType->is_active = !!$request->is_active;
            $accountType->description = $request->description;
            $accountType->save();

            activity()->performedOn($accountType)->log('Created Account type '.$accountType->name);

            return redirect()->back()->with('success', __('Account Type created successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AccountType $accountType
     *
     * @return \Illuminate\Http\Response
     */
    public function show(AccountType $accountType,) {

        return view('admin.account_types.show', compact('accountType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\AccountType $accountType
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $accountType = AccountType::find($id);
        return view('admin.account_types.edit', compact('accountType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountType $accountType,) {

        $request->validate(["name" => "required",'rate'=>'required','description'=>'required']);

        try {
            $accountType->name = $request->name;
            $accountType->rate = $request->rate;
            $accountType->is_active = !!$request->is_active;
            $accountType->description = $request->description;
            $accountType->save();

            activity()->performedOn($accountType)->log('edited Account type '.$accountType->name);

            return redirect()->back()->with('success', __('Account Type edited successfully.'));
        } catch (\Throwable $e) {
            return redirect()->back()->withInput($request->input())->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AccountType $accountType
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountType $accountType,) {

        try {

            $members = \App\Models\User::where('account_type_id',$accountType->id)->get();

            if ($members->count() > 0) {
                return redirect()->route('account_types.index', [])->with('error', 'Cannot delete Account Type with existing members');
            }
            
            $accountType->delete();
            activity()->performedOn($accountType)->log('deleted Account type '.$accountType->name);

            return redirect()->route('account_types.index', [])->with('success', __('Account Type deleted successfully'));
        } catch (\Throwable $e) {
            return redirect()->route('account_types.index', [])->with('error', 'Cannot delete Account Type: ' . $e->getMessage());
        }
    }
}
