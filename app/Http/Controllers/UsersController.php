<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    public function users(){
        $users = User::where('user_type','Admin')->get();

        return view('admin.users.index',compact('users'));
    }

    public function edit($user){
        $user = User::find($user);
        return view('admin.users.edit',compact('user'));
    }

    public function assign_permission(Request $request)
    {
        $permission = Permission::firstOrCreate(['name' => 'show CPD']);
        $permission = Permission::firstOrCreate(['name' => 'create CPD']);
        $permission = Permission::firstOrCreate(['name' => 'update CPD']);
        $permission = Permission::firstOrCreate(['name' => 'delete CPD']);
        $permission = Permission::firstOrCreate(['name' => 'approve CPD attendence']);

        $permission = Permission::firstOrCreate(['name' => 'show event']);
        $permission = Permission::firstOrCreate(['name' => 'create event']);
        $permission = Permission::firstOrCreate(['name' => 'update event']);
        $permission = Permission::firstOrCreate(['name' => 'delete event']);
        $permission = Permission::firstOrCreate(['name' => 'approve event attendence']);

        $permission = Permission::firstOrCreate(['name' => 'invoice']);
        $permission = Permission::firstOrCreate(['name' => 'expense']);
        $permission = Permission::firstOrCreate(['name' => 'inventory']);

        $permission = Permission::firstOrCreate(['name' => 'hrm']);

        $permission = Permission::firstOrCreate(['name' => 'approve members']);
        $permission = Permission::firstOrCreate(['name' => 'members']);
        $permission = Permission::firstOrCreate(['name' => 'make admin']);
        $permission = Permission::firstOrCreate(['name' => 'communications']);
        $permission = Permission::firstOrCreate(['name' => 'audit trail']);
        $permission = Permission::firstOrCreate(['name' => 'settings']);

        $permission = Permission::firstOrCreate(['name' => 'leads']);

        $permission = Permission::firstOrCreate(['name' => 'deals']);
        $permission = Permission::firstOrCreate(['name' => 'form builder']);


        $user = User::find($request->user);
        if ($request->assigned == "true") {
            $user->givePermissionTo($request->permission);
        }else{
            $user->revokePermissionTo($request->permission);
        }
        return $request;
    }
}
