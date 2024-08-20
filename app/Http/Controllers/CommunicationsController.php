<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Communication;
use AfricasTalking\SDK\AfricasTalking;
use App\Models\Newsletter;

class CommunicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::user()->user_type == "Admin") {
            $communications = Communication::all();
        }else{
            $communications = Communication::where('target','*')
                                ->orWhere('target',\Auth::user()->account_type_id)
                                ->get();
        }
        
        return view('communications.index',compact('communications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accountTypes = \App\Models\AccountType::all();
        return view('communications.create',compact('accountTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'target' => 'required'
        ]);

        try{
            $communication = new Communication;
            $communication->title = $request->title;
            $communication->target = $request->target;
            $communication->message = $request->message;
            $communication->user_id = \Auth::user()->id;
            $communication->save();

            $to = 'nsengiyumvawilberforce@gmail.com';

            $target = \App\Models\User::where('account_type_id',$request->target)->get();
         
            \Mail::send([], [], function ($message) use ($to, $communication, $target) {
                $message->to($to)
                    ->bcc($target)
                    ->subject($communication->title)
                    ->setBody($communication->message, 'text/plain');
            });

            return redirect('communications')->with('success','Communication has been successfully published!');
        }catch(\Throwable $ex){
            return redirect()->back()->withErrors(['error'=>$ex->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $communication = Communication::find($id);

        return view('communications.details',compact('communication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $communication = Communication::find($id);

        $accountTypes = \App\Models\AccountType::all();

        return view('communications.edit',compact('communication','accountTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'target' => 'required'
        ]);

        $communication = Communication::find($id);
        $communication->title = $request->title;
        $communication->target = $request->target;
        $communication->message = $request->message;
        $communication->user_id = \Auth::user()->id;
        $communication->save();

        return redirect('communications')->with('success','Communication has been successfully published!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        try{
            $communication = Communication::find($id);
            $communication->delete();

            return redirect()->back()->with('success','Communication has been deleted!');
        }catch(\Throwable $e){
            return redirect()->back()->with('error',$e->getMessage()); 
        }
    }

    public function sms_view($value='')
    {
        $accountTypes = \App\Models\AccountType::all();
        return view('communications.sms',compact('accountTypes'));
    }

    public function post_sms(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'target' => 'required',
        ]);
        try{

            $username = 'MUSISI';
            $apiKey   = '3efc0562783b8617ef83bf427ab30afe9da377630c80278af38bc5c2ca358f5e';
            $AT       = new AfricasTalking($username, $apiKey);

            $sms      = $AT->sms();

            $result   = $sms->send([
                'to'      => '+256781014607',
                'message' => $request->message,
            ]);

            return redirect()->back()->with('success','SMS have been sent!');

        }catch(\Throwable $ex){
            return redirect()->back()->withErrors(['error' => $ex->getMessage()])->withInput();
        }
    }

    public function newsletter_view()
    {
        $communications = \App\Models\Newsletter::all();
        return view('communications.newsletter', compact('communications'));
    }

    public function post_newsletter(Request $request)
    {
        //check if request has file attached
        if ($request->hasFile('newsletter_file')) {
            //validate file
            $request->validate([
                'newsletter_file' => 'required|mimes:pdf|max:10000',
            ]);

            //get the file
            $newsletterFile = $request->file('newsletter_file');

            try {
                //generate the file name from time and random number
                $fileNameToStore = time() . rand(100, 1000) . '.' . $request->file('newsletter_file')->extension();
                //upload the file to the public folder
                $newsletterFile->move(public_path('newsletters'), $fileNameToStore);
                //check if file was uploaded successfully
                if (!$newsletterFile) {
                    return response()->json(['error' => 'File not uploaded!']);
                }
                $newsletter = new \App\Models\Newsletter;
                $newsletter->title = $request->title;
                $newsletter->sub_title = $request->sub_title;
                $newsletter->description = $request->description;
                $newsletter->newsletter_file_url = $fileNameToStore;
                $newsletter->save();

                return response()->json(['success' => 'Newsletter has been successfully published!']);
            } catch (\Throwable $ex) {
                return response()->json(['error' => $ex->getMessage()]);
            }
        } else {
            return response()->json(['error' => 'No file attached!']);
        }
    }

    public function newsletter_details(Newsletter $newsletter)
    {
        //check if the $newsletter is not null
        if (!$newsletter) {
            return redirect()->back()->with('error', 'Newsletter not found!');
        }

        return view('communications.newsletter_details', compact('newsletter'));
    }
    public function download_newsletter_file(Newsletter $newsletter)
    {
        //check if the $newsletter is not null
        if (!$newsletter) {
            return redirect()->back()->with('error', 'Newsletter not found!');
        }

        //get the file path
        $filePath = public_path('newsletters/' . $newsletter->newsletter_file_url);

        //check if the file exists
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found!');
        }

        //download the file
        return response()->download($filePath);
    }

    public function delete_newsletter(Newsletter $newsletter)
    {
        //check if the $newsletter is not null
        if (!$newsletter) {
            return redirect()->back()->with('error', 'Newsletter not found!');
        }

        //get the file path
        $filePath = public_path('newsletters/' . $newsletter->newsletter_file_url);

        //check if the file exists
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found!');
        }

        //delete the file
        unlink($filePath);

        //delete the newsletter
        $newsletter->delete();

        //redirect to newsletter url not redirect back
        return redirect('admin/newsletter')->with('success', 'Newsletter has been deleted!');
    }
    public function update_newsletter(Request $request, Newsletter $newsletter)
    {
        //check if the $newsletter is not null
        if (!$newsletter) {
            return redirect()->back()->with('error', 'Newsletter not found!');
        }

        //validate the request
        $request->validate([
            'title' => 'required',
            'sub_title' => 'required',
            'description' => 'required',
        ]);

        //check if the request has file attached
        if ($request->hasFile('newsletter_file')) {
            //validate file
            $request->validate([
                'newsletter_file' => 'required|mimes:pdf|max:10000',
            ]);

            //get the file
            $newsletterFile = $request->file('newsletter_file');

            try {
                //generate the file name from time and random number
                $fileNameToStore = time() . rand(100, 1000) . '.' . $request->file('newsletter_file')->extension();
                //upload the file to the public folder
                $newsletterFile->move(public_path('newsletters'), $fileNameToStore);
                //check if file was uploaded successfully
                if (!$newsletterFile) {
                    return redirect()->back()->with('error', 'File not uploaded!');
                }

                //delete the old file
                unlink(public_path('newsletters/' . $newsletter->newsletter_file_url));

                //update the newsletter
                $newsletter->title = $request->title;
                $newsletter->sub_title = $request->sub_title;
                $newsletter->description = $request->description;
                $newsletter->newsletter_file_url = $fileNameToStore;
                $newsletter->save();

                return response()->json(['success' => 'Newsletter has been successfully updated!']);
            } catch (\Throwable $ex) {
                return response()->json(['error' => $ex->getMessage()]);
            }
        } else {
            //update the newsletter
            $newsletter->title = $request->title;
            $newsletter->sub_title = $request->sub_title;
            $newsletter->description = $request->description;
            $newsletter->save();

            return response()->json(['success' => 'Newsletter has been successfully updated!']);
        }
    }

       public function share_newsletter(Newsletter $newsletter)
    {
        // Check if the $newsletter is not null
        if (!$newsletter) {
            return redirect()->back()->with('error', 'Newsletter not found!');
        }

        $users = \App\Models\User::all();

        $emails = $users->filter(function ($user) {
            return $user->email_verified_at != null;
        })->pluck('email')->toArray();

        // Send the email with BCC
        \Mail::to('nsengiyumvawilberforce@gmail.com')->bcc($emails)->send(new \App\Mail\Newsletter($newsletter->title, $newsletter->newsletter_file_url, $newsletter->description));

        return redirect()->back()->with('success', 'Newsletter has been shared!');
    }
}
