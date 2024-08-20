<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserFcmDeviceToken;
use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Notification;



class UserFcmDeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fcm_device_token' => 'required',
        ]);

        $user = User::find($request->user_id);

        $user->fcmDeviceTokens()->updateOrCreate(
            ['fcm_device_token' => $request->fcm_device_token],
            ['fcm_device_token' => $request->fcm_device_token]
        );

        return response()->json([
            'success' => true,
            'message' => 'FCM device token stored successfully.'
        ]);
    }

    public function sendNotification(Request $request)
    {
        //get the user with id 1
        $devices = UserFcmDeviceToken::all();

        //send notification to users
        Notification::send($devices, new PushNotification("https://cdn-icons-png.flaticon.com/512/6828/6828737.png", "Testing notifications"));

        return response()->json([
            'success' => true,
            'message' => 'Notifications sent successfully.'
        ]);
    }
}
