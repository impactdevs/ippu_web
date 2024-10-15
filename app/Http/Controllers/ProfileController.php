<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Dompdf\Options;
use Carbon\Carbon;
use App\Mail\MembershipCertificate;
use Symfony\Component\Mailer\Exception\TransportException;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function update_profile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'gender' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'phone_no' => 'required',
            'nok_name' => 'required',
            'nok_phone_no' => 'required'
        ]);

        $user = User::find(\Auth::user()->id);
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->membership_number = $request->membership_number;
        $user->address = $request->address;
        $user->phone_no = $request->phone_no;
        $user->alt_phone_no = $request->alt_phone_no;
        $user->nok_name = $request->nok_name;
        $user->nok_address = $request->nok_email;
        $user->organisation = $request->organisation;
        $user->nok_phone_no = $request->nok_phone_no;

        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $extension = $file->getClientOriginalExtension();

            $filename = time().rand(100,1000).'.'.$extension;

            $storage = \Storage::disk('public')->putFileAs(
                        'profiles/',
                        $file,
                        $filename);

            if (!$storage) {
                return response()->json(['message' => 'Unable to upload profile pic!']);
            }else{
                $user->profile_pic = $filename;
            }
        }
        $user->save();

        return redirect('profile')->with('success','Profile Details have been updated!');
    }

        //create a generate certificate helper function
        public function generate_certificate_helper(User $user = null){
            $manager = new ImageManager(new Driver());

            $image = $manager->read(public_path('images/membership_certificate_template.jpeg'));
            //get this year's 01/01
            $yearStart = Carbon::now()->startOfYear()->format('dS F, Y');

            //get this year's 31/12
            $yearEnd = Carbon::now()->endOfYear()->format('dS F, Y');

            $membershipProcessingDate = $yearStart;

            //add 12 months to the processing date to get expiry date
            $expiryDate = $yearEnd;

            $image->text(strtoupper($user->name), 700, 550, function ($font) {
                $font->filename(public_path('fonts/Roboto-Bold.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->text('Membership number ', 500, 650, function ($font) {
                $font->filename(public_path('fonts/Roboto-Regular.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->text($user->membership_number ?? "N/A", 770, 650, function ($font) {
                $font->filename(public_path('fonts/Roboto-Bold.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->text('is a registered', 620, 720, function ($font) {
                $font->filename(public_path('fonts/Roboto-Regular.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->text(strtoupper($user->account_type->name), 500, 790, function ($font) {
                $font->filename(public_path('fonts/Roboto-Bold.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->text('member of', 680, 790, function ($font) {
                $font->filename(public_path('fonts/Roboto-Regular.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->text('The Institute of Procurement Professionals of Uganda', 560, 860, function ($font) {
                $font->filename(public_path('fonts/Roboto-Regular.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->text("(IPPU)", 990, 860, function ($font) {
                $font->filename(public_path('fonts/Roboto-Bold.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });


            $image->text('from today ', 400, 930, function ($font) {
                $font->filename(public_path('fonts/Roboto-Regular.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->text($membershipProcessingDate, 620, 930, function ($font) {
                $font->filename(public_path('fonts/Roboto-Bold.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->text('until ', 400, 1000, function ($font) {
                $font->filename(public_path('fonts/Roboto-Regular.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->text($expiryDate, 600, 1070, function ($font) {
                $font->filename(public_path('fonts/Roboto-Bold.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->text('and agrees to abide by regulations and ', 600, 1140, function ($font) {
                $font->filename(public_path('fonts/Roboto-Regular.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->text('Ethical code of conduct ', 580, 1210, function ($font) {
                $font->filename(public_path('fonts/Roboto-Regular.ttf'));
                $font->color('#405189');
                $font->size(30);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.6);
            });

            $image->toPng();

            //save the image to the public folder
            $image->save(public_path('images/certificate-generated.png'));

            return public_path('images/certificate-generated.png');
        }

    public function generate_membership_certificate()
    {
        $user = User::find(\Auth::user()->id);
        $certificate = $this->generate_certificate_helper($user);
        return response()->download($certificate)->deleteFileAfterSend(true);
    }
    public function email_membership_certificate(Request $request){
        try{
        $memberId = $request->input('member_id');
        $user = User::find($memberId);
        $certificate = $this->generate_certificate_helper($user);
        \Mail::to($user->email)->send(new MembershipCertificate($certificate));
        return redirect()->back()->with('success', __('Membership certificate has been sent to '.$user->email));
        } catch(TransportException $exception){
            return redirect()->back()->withInput($request->input())->with('error', "Failed to email the certificate! Please try again later.");
        }
    }
}
