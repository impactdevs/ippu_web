<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PushNotification;

class Job extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        // Triggered when a new model is being created
        static::creating(function ($model) {
            $model->sendNotification();
        });
    }

    public function sendNotification()
    {
        //get the user with id 1
        $devices = UserFcmDeviceToken::all();

        //send notification to users
        Notification::send($devices, new PushNotification("default.png", $this->title));
    }
}
