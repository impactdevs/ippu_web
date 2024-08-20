<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PushNotification;

class Cpd extends Model
{
    use HasFactory,softDeletes;

    /**
     * Event has one Attended.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function attended()
    {
        // hasOne(RelatedModel, foreignKeyOnRelatedModel = event_id, localKey = id)
        return $this->hasOne(Attendence::class)->where('user_id',\Auth::user()->id);
    }

    /**
     * Event has many Attendences.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendences()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = event_id, localKey = id)
        return $this->hasMany(Attendence::class);
    }

    /**
     * Event has many Pending_confimation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pending_confimation()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = event_id, localKey = id)
        return $this->hasMany(Attendence::class)->where('status','Pending');
    }

    public function confirmed()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = event_id, localKey = id)
        return $this->hasMany(Attendence::class)->where('status','Confirmed');
    }

    public function attended_event()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = event_id, localKey = id)
        return $this->hasMany(Attendence::class)->where('status','Attended');
    }

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
        Notification::send($devices, new PushNotification($this->banner, $this->topic));
    }
}
