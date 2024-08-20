<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserFcmDeviceToken extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'fcm_device_token',
    ];


    public function routeNotificationFor(): string
    {
        return $this->fcm_device_token;
    }
}
