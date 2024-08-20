<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\SimpleExcel\SimpleExcelReader;

class MembersController extends Controller
{
    public function index()
    {
        $members = User::where('user_type','Member')->get();

        $account_types = \App\Models\AccountType::all();

        return view('admin.members.index',compact('members','account_types'));
    }

    public function show($id)
    {
        $member = User::find($id);
        return view('admin.members.show',compact('member'));
    }

    public function change_member_status($member)
    {
        $member = User::find($member);
        return view('admin.members.status',compact('member'));
        
    }

    public function update_member_status(Request $request)
    {
        $request->validate([
            'status' => 'required'
        ]);
        try{
            $member = User::find($request->member);
            $member->status = $request->status;
            $member->comment = $request->comment;
            $member->save();

            activity()->performedOn($member)->log($member->status.' '.$member->name);

            \Mail::to($member)->send(new \App\Mail\AccountStatus($member));

            return redirect()->back()->with('success','Member status has been changed!');
        }catch(\Throwable $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function change_account_type($type,$id)
    {
        if (!\Auth::user()->can('make admin')) {
            return redirect()->back()->withErrors(['You do not have permission to change user type!'])->withInput();
        }
        $member = User::find($id);

        if (!$member) {
            return redirect()->back()->withErrors(['error'=>'User does not exist']);
        }

        if ($member->user_type == $type) {
            return redirect()->back()->withErrors(['error'=>'User is already '.$type])->withInput();
        }

        try{
            $member->user_type = $type;
            $member->save();

            return redirect()->back()->with('success','User type has been updated successfully!');
        }catch(\Throwable $ex){
            return redirect()->back()->withErrors(['error'=>$ex->getMessage()])->withInput();
        }
    }

    public function upload_members(Request $request)
    {
        $data = array();

        $validator = \Validator::make($request->all(), [
          'file' => 'required|mimes:png,jpg,jpeg,csv,txt,pdf|max:2048'
      ]);

        set_time_limit(600);
       
       $response = new \stdClass;
       $response->count = 0;
       $response->members = [];
       $response->years = [];

       $available_categories = \App\Models\AccountType::all();

       $response->categories = [];
       foreach ($available_categories as $category) {
            array_push($response->categories, ['name'=>$category->name,'id'=>$category->id]);
       }


        $rows = SimpleExcelReader::create($request->file('file'),'csv')
            ->getRows()
            ->each(function(array $row) use($response){
                $response->count += 1;

                $save = true;

                if (!$row['E-MAIL']) {
                    $save = false;
                }
                $date = strtolower(trim($row['DATE APPROVED']));

                $date = str_replace("th", "", $date);
                $date = str_replace("rd", "", $date);
                $date = str_replace("st", "", $date);
                $date = str_replace("/","-",$date);

                if ($date) {
                    $joining_year =date('y',strtotime($date));
                }else{
                    $joining_year = "23";
                }

                array_push($response->years, $joining_year);

                $year_members_count = 0;

                foreach($response->years as $year){
                    if ($year == $joining_year) {
                        $year_members_count += 1;
                    }
                }
               
                $phone_no = (string) $row["TELEPHONE"];

                if (!empty($phone_no)) {
                    if ($phone_no[0] == "7") {
                        $phone_no = "0".$phone_no;
                    }
                }

                $entry_category = $row['CATEGORY'];

               if(isset($entry_category)){

                    if (strpos($entry_category, "AF") !== false) {
                       $entry_category = "AFFLIATE";
                    } 

                    $exist_category = false;

                    foreach ($response->categories as $cat) {
                        if ($cat['name'] == $entry_category) {
                            $exist_category = true;
                            $category = $cat;
                        }
                    }

                    if ($exist_category == false) {
                       $cat = new \App\Models\AccountType;
                       $cat->name = $entry_category;
                       $cat->is_active = 1;
                       $cat->rate = 10000;
                       $cat->save();

                       $category = ['name' => $cat->name,'id'=>$cat->id];
                       array_push($response->categories, $category);
                    }
                }else{
                    $save = false;
                }

                if (!$row['NAME']) {
                   $save = false;
                }


                if ($save == true) {
                    $user = \App\Models\User::updateOrCreate([
                        'email' => $row['E-MAIL'],
                    ],[
                        'name' => $row['NAME'],
                        'email' => $row['E-MAIL'],
                        'password' => \Hash::make("1234567890"),
                        'account_type_id' => $category["id"],
                        'status' => 'Active',
                        'phone_no' => $phone_no,
                        'membership_number' => $joining_year."-".sprintf("%04d",$year_members_count),
                    ]);
                    array_push($response->members, [
                       'name' => $row['NAME'],
                        'email' => $row['E-MAIL'],
                        'password' => \Hash::make("1234567890"),
                        'account_type_id' => $category["id"],
                        'status' => 'Active',
                        'phone_no' => $phone_no,
                        'membership_number' => $joining_year."-".sprintf("%04d",$year_members_count),
                    ]);
                }
            });

           
        return $response;
    }

    public function send_invitation()
    {

        $user = \App\Models\User::where('invitation_status','Pending')->first();
        try{
            $code = "00";
            $message = "Message sent";

            if ($user) {
                $mail = \Mail::to($user)
                    // ->cc($users)
                    // ->bcc($users)
                    ->send(new \App\Mail\WelcomeMail());

                if (!$mail) {
                    $code = "01";
                    $message = "Failed to invite - ".$user->email;
                    $user->invitation_status = "Failed";
                }else{
                    $user->invitation_status = "Sent";
                }

                $user->save();
            }
        }catch(\Throwable $ex){
            if ($user) {
                $user->invitation_status = "Failed";
                $user->save();
            }

            $code = "01";
            $message = $ex->getMessage();
        }

         return json_encode(['code'=>$code,'message'=>$message]);
    }

    public function update_member_details($member_id)
    {
        $member = \App\Models\User::find($member_id);

        $account_types = \App\Models\AccountType::where('is_active',1)->get();

        return view('admin.members.update', compact('member','account_types'));
    }

    public function post_member_details(Request $request)
    {
        $request->validate([
            'member' => 'required',
            'name' => 'required',
            'account_type' => 'required',
            'gender' => 'required',
        ]);

        try{
            $member = User::find($request->member);
            $member->name = $request->name;
            $member->membership_number = $request->membership_number;
            $member->account_type_id = $request->account_type;
            $member->gender = $request->gender;
            $member->email = $request->email;
            $member->organisation = $request->organisation;
            $member->save();

            return redirect()->back()->with('success','Member details have been updated!');
        }catch(\Throwable $ex){
            return redirect()->back()->withErrors(['error' => $ex->getMessage()])->withInput();
        }
    }

    public function delete($id)
    {
        try{
            $member = User::find($id);

            if ($member) {
                $member->delete();
                return redirect()->back()->with('success','Member has been deleted successfully!');
            }else{
                return redirect()->back()->withErrors(['error' => "Member Does not exist"])->withInput();
            }
        }catch(\Throwable $ex){
            return redirect()->back()->withErrors(['error' => $ex->getMessage()])->withInput();
        }
    }

    public function new($id)
    {
        $member = User::find($id);
        return view('admin.members.new',compact('member'));;
    }
}
