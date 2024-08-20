<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberReminder extends Model
{
    use HasFactory;

    /**
     * MemberReminder belongs to Member.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        // belongsTo(RelatedModel, foreignKey = member_id, keyOnRelatedModel = id)
        return $this->belongsTo(User::class);
    }
}
