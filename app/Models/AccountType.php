<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AccountType extends Model
{
    use HasFactory;

    /**
     * AccountType hasS many Users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        // hasMany(RelatedModel, foreignKeyOnRelatedModel = accountType_id, localKey = id)
        return $this->hasMany(User::class);
    }
}
