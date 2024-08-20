<?php

namespace App\Http\Controllers\API;

use App\Mail\ResetPasswordEmail;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register', 'verifyEmail', 'resendVerificationCode', 'resetPasswordCode', 'resetPassword', 'verifyPasswordResetEmail']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'user' => $user,
                'authorization' => [
                    'token' => $user->createToken('ApiToken')->plainTextToken,
                    'type' => 'bearer',
                ]
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required'],
            'account_type_id' => ['required'],
        ]);

        //check if the validation failed due to email already existing
        if (User::where('email', $request->email)->first()) {
            return response()->json([
                'message' => 'Email already exists',
            ], 409);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_type_id' => $request->account_type_id,
            'user_type' => 'Member',
        ]);

        $notification = new \App\Models\MemberReminder;
        $notification->title = "A new user has signed up to IPPU";
        $notification->member_id = $user->id;
        $notification->reminder_date = date('Y-m-d');
        $notification->status = "Unread";
        $notification->save();

        //first check if user creation was successful
        if (!$user) {
            return response()->json([
                'message' => 'Account creation failed, try again',
            ], 500);
        }

        //send a verification code to the user
        try{
        //generate random code
        $code = rand(100000, 999999);

        //save code to database
        $verificationCode = new VerificationCode();
        $verificationCode->email = $user->email;
        $verificationCode->code = $code;
        $verificationCode->save();

        if(!$verificationCode){
            return response()->json([
                'message' => 'Account creation failed',
            ], 500);
        }

        $emailSent = Mail::to($user->email)->send(new VerifyEmail($code, $user->name));

        // Check if sending the email was successful/failed
        if ($emailSent === 0) {
            return response()->json([
                'message' => 'Email sending failed',
            ], 500);
        }

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);

    } catch (\Exception $e) {
        //check if the user was created and send email failed
        if ($user) {
            return response()->json([
                'message' => 'User created successfully, email verification code sending failed, continue to login',
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Account creation failed',
            ], 500);
        }
    }
}

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    //verify email using the code
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'code' => ['required', 'string'],
        ]);

        $verificationCode = VerificationCode::where('email', $request->email)->where('code', (int)$request->code)->first();

        if (!$verificationCode) {
            return response()->json([
                'message' => 'Invalid verification code',
            ], 401);

            }

        //update user email_verified_at column
        $user = User::where('email', $request->email)->first();
        $user->email_verified_at = now();

        if (!$user->save()) {
            return response()->json([
                'message' => 'Verification failed',
            ], 500);
        }

        //delete verification code from database
        $verificationCode->delete();

        return response()->json([
            'message' => 'Email verified successfully',
        ], 200);
    }

    public function resendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = VerificationCode::where('email', $request->email)->first();

        //regenerate code and update database
        $code = rand(100000, 999999);

        $user->code = $code;

        if (!$user->save()) {
            return response()->json([
                'message' => 'Failed to resend verification code',
            ], 500);
        }

        //temporarily auth the user to get the name
        $user = User::where('email', $request->email)->first();
        auth()->login($user);

        $name = auth()->user()->name;
        Mail::to($user->email)->send(new VerifyEmail($code, $name));
        auth()->logout();

        return response()->json([
            'message' => 'Verification code resent successfully',
        ], 200);
    }

    public function resetPasswordCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        //check if user exists
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        //check if the user already has a verification code and delete it
        $verificationCode = VerificationCode::where('email', $request->email)->first();

        if ($verificationCode) {
            $verificationCode->delete();
        }

        //generate code and update verification code table
        $code = rand(100000, 999999);

        $verificationCode = new VerificationCode();

        $verificationCode->email = $request->email;

        $verificationCode->code = $code;

        if (!$verificationCode->save()) {
            return response()->json([
                'message' => 'Failed to send reset code',
            ], 500);
        }

        //send email to the user
        $user = User::where('email', $request->email)->first();
        auth()->login($user);

        $name = auth()->user()->name;
        Mail::to($user->email)->send(new ResetPasswordEmail($code, $name));
        auth()->logout();

        return response()->json([
            'message' => 'Reset code sent successfully',
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string'],
            'password' => ['required']]
        );

        //check if user exists in the verification code table
        $verificationCode = VerificationCode::where('email', $request->email)->where('code', (int)$request->code)->first();

        if (!$verificationCode) {
            return response()->json([
                'message' => 'Invalid verification code',
            ], 401);
        }

        //update user password

        $user = User::where('email', $request->email)->first();

        $user->password = Hash::make($request->password);

        if (!$user->save()) {
            return response()->json([
                'message' => 'Failed to reset password',
            ], 500);
        }

        //delete verification code from database

        $verificationCode->delete();

        return response()->json([
            'message' => 'Password reset successfully',
        ], 200);

    }

        //verify email using the code
        public function verifyPasswordResetEmail(Request $request)
        {
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'code' => ['required', 'string'],
            ]);

            $verificationCode = VerificationCode::where('email', $request->email)->where('code', (int)$request->code)->first();

            if (!$verificationCode) {
                return response()->json([
                    'message' => 'Invalid verification code',
                ], 401);

                }

            return response()->json([
                'message' => 'Code verified successfully',
            ], 200);
        }

    public function VerifyPhoneNumber(Request $request)
    {
        $phone_number = str_replace('+256', '0', $request->phone_number);
        $real_phone_number = str_replace(' ', '', $request->phone_number);

        try {
            //check either of the phone numbers
            $user = User::where('phone_no', $phone_number)->orWhere('phone_no', $real_phone_number)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Phone number not found',
                ], 404);
            }

            return response()->json(['status' => true, 'message' => 'Phone number exists, sending the code soon!'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while verifying the phone number, try again',
            ], 500);
        }
    }
    public function PhoneLogin(Request $request)
    {
        $phone_number = str_replace('+256', '0', $request->phone_number);
        $real_phone_number = str_replace(' ', '', $request->phone_number);

        try {
            //check either of the phone numbers
            $user = User::where('phone_no', $phone_number)->orWhere('phone_no', $real_phone_number)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Phone number not found',
                ], 404);
            }

            auth()->login($user);

            return response()->json([
                'user' => $user,
                'authorization' => [
                    'token' => $user->createToken('ApiToken')->plainTextToken,
                    'type' => 'bearer',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while loging in, try again',
            ], 500);
        }
    }

    public function loginByGoogle(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->userFromToken($request->token);
            $user = User::where('email', $googleUser->getEmail())->first();
            if(!$user){
                //download the profile picture and save it to the server
                $profilePic = file_get_contents($googleUser->getAvatar());
                //generate a random filename
                $filename = time().'_'.rand(1000, 9999).'.png';

                $storage = \Storage::disk('public')->put(
                    'profiles/'.$filename,
                    $profilePic
                );

                if(!$storage){
                    return response()->json([
                        'message' => 'Failed to save profile picture',
                    ], 500);
                }
                //create a new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'profile_pic' => $filename,
                    'account_type_id' => 8,
                    'password' => Hash::make('password'),
                ]);
            }

            Auth::login($user);

            //get the auth token to use for subsequent requests

            return response()->json([
                'user' => $user,
                'authorization' => [
                    'token' => $user->createToken('ApiToken')->plainTextToken,
                    'type' => 'bearer',
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

        public function loginByApple(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if(!$user){
                //create a new user
                $user = User::create([
                    'name' => $request->fullName,
                    'email' => $request->email,
                    'account_type_id' => 8,
                    'password' => Hash::make('password'),
                ]);
            }

            Auth::login($user);

            //get the auth token to use for subsequent requests

            return response()->json([
                'user' => $user,
                'authorization' => [
                    'token' => $user->createToken('ApiToken')->plainTextToken,
                    'type' => 'bearer',
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
