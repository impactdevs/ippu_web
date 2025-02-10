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

            $filename = time() . rand(100, 1000) . '.' . $extension;

            $storage = \Storage::disk('public')->putFileAs(
                'profiles/',
                $file,
                $filename
            );

            if (!$storage) {
                return response()->json(['message' => 'Unable to upload profile pic!']);
            } else {
                $user->profile_pic = $filename;
            }
        }
        $user->save();

        return redirect('profile')->with('success', 'Profile Details have been updated!');
    }

    //create a generate certificate helper function
    public function generate_certificate_helper(User $user = null)
    {
        $manager = new ImageManager(new Driver());

        $image = $manager->read(public_path('images/membership_template.jpeg'));
        //get this year's 01/01
        $yearStart = Carbon::now()->startOfYear()->format('dS F, Y');

        //get this year's 31/12
        $yearEnd = Carbon::now()->endOfYear()->format('dS F, Y');

        $membershipProcessingDate = $yearStart;

        //add 12 months to the processing date to get expiry date
        $expiryDate = $yearEnd;

        $image->text(strtoupper($user->name), 890, 1150, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(50);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text($user->membership_number ?? "N/A", 1230, 1268, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(50);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text(strtoupper($user->account_type->name), 900, 1490, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(50);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text($membershipProcessingDate, 1050, 1812, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(50);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        $image->text($expiryDate, 1050, 1932, function ($font) {
            $font->filename(public_path('fonts/Roboto-Bold.ttf'));
            $font->color('#405189');
            $font->size(50);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
        });

        // Save the image with explicit format
        $imagePath = public_path('images/certificate-generated' . $user->id . '.png');
        $image->save($imagePath, 'png'); // Explicitly set format

        return $imagePath;
    }

    public function generate_membership_certificate()
    {
        $user = User::find(\Auth::user()->id);
        $certificate = $this->generate_certificate_helper($user);
        return response()->download($certificate)->deleteFileAfterSend(true);
    }
    public function email_membership_certificate(Request $request)
    {
        try {
            $memberId = $request->input('member_id');
            $user = User::find($memberId);
            $certificate = $this->generate_certificate_helper($user);
            \Mail::to($user->email)->send(new MembershipCertificate($certificate));
            return redirect()->back()->with('success', __('Membership certificate has been sent to ' . $user->email));
        } catch (TransportException $exception) {
            return redirect()->back()->withInput($request->input())->with('error', "Failed to email the certificate! Please try again later.");
        }
    }
}
