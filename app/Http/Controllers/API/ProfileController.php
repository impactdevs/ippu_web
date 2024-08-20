<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;
use App\Models\AccountType;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->membership_amount = AccountType::find($user->account_type_id)->rate;
        
        // Check if the user has a latest membership
        $latestMembership = $user->latestMembership;

            $subscriptionStatus = \DB::table('memberships')
            ->where('expiry_date', '>', Carbon::now())
            ->where('user_id', $user->id)->orderBy('created_at', 'desc')->first()->status??false;
     

        // Update the user's subscription_status field
        $user->subscription_status = $subscriptionStatus;

        //set profile photo url in profile_pic field
        $user->profile_pic = url('storage/profiles/' . $user->profile_pic);

        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => $user
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the resource by ID
        $resource = User::find($id);

        // Check if the resource exists
        if (!$resource) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        // Check if the user has a latest membership
        if ($resource->latestMembership) {
            // If a latest membership is found, set the subscription_status field
            $resource->subscription_status = $resource->latestMembership->status;
        } else {
            // If the user has no subscription, set the subscription_status field to false
            $resource->subscription_status = false;
        }

        // Return the resource as a JSON response with the added subscription_status field
        return response()->json(['data' => $resource], 200);
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
        $user = User::find($id);
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->membership_number = $request->membership_number;
        $user->address = $request->address;
        $user->phone_no = $request->phone_no;
        $user->alt_phone_no = $request->alt_phone_no;
        $user->nok_name = $request->nok_name;
        $user->nok_address = $request->nok_email;
        $user->nok_phone_no = $request->nok_phone_no;
        $user->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //subscribe to the app
    public function subscribe(Request $request)
    {

        try {
            // get the logged in user
            $user = Auth::user();
            $membership = new \App\Models\Membership;
            $membership->status = "Pending";
            $membership->user_id = $user->id;
            $membership->save();

            

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            \Mail::to(Auth::user())->send(new \App\Mail\ApplicationReview($membership));

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully'
            ]);

        } catch (\Throwable $e) {
            print_r($e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Application failed'
            ]);
        }


    }

    public function delete_my_account($userId)
    {
        $user = User::find($userId);
        //return user not found error
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    public function updateProfilePhoto()
    {
        //get the authenticated user
        $user = Auth::user();
        // Validate the request
        request()->validate([
            'profile_photo_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = request()->file('profile_photo_path');
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
            //get the image url
            $imageUrl = url('storage/profiles/' . $filename);
        }

        $user->save();
        return response()->json([
            'message' => 'Profile photo updated successfully',
            'profile_photo_path' => $imageUrl
        ], 200);
    }
    public function generate_membership_certificate()
    {
        $manager = new ImageManager(new Driver());

        $image = $manager->read(public_path('images/membership_certificate_template.jpeg'));

        $user = auth()->user();

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

        $filePath = public_path('images/certificate-generated' . $user->id . '.png');

        //get the image url
        $imageUrl = url('images/certificate-generated' . $user->id . '.png');
        //save the image to the public folder
        $image->save($filePath);

        return response([
            'message' => 'Certificate generated successfully',
            'data' => [
                'certificate' => $imageUrl,
            ]
        ]);
    }

}
