<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    use HasFactory;

    public static $status = [
        'Pending',
        'Confirmed',
        'Attended',
    ];

    /**
     * Attendence belongs to User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        // belongsTo(RelatedModel, foreignKey = user_id, keyOnRelatedModel = id)
        return $this->belongsTo(User::class);
    }

    /**
     * Attendence belongs to Cpd.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cpd()
    {
        // belongsTo(RelatedModel, foreignKey = cpd_id, keyOnRelatedModel = id)
        return $this->belongsTo(Cpd::class)->withTrashed();
    }

    /**
     * Attendence belongs to Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        // belongsTo(RelatedModel, foreignKey = event_id, keyOnRelatedModel = id)
        return $this->belongsTo(Event::class)->withTrashed();
    }

    /**
     * Attendence has one Cpd_payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cpd_payment()
    {
        // hasOne(RelatedModel, foreignKeyOnRelatedModel = attendence_id, localKey = id)
        return $this->hasOne(Payment::class,'cpd_id');
    }

    /**
     * Attendence has one Event_payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function event_payment()
    {
        // hasOne(RelatedModel, foreignKeyOnRelatedModel = attendence_id, localKey = id)
        return $this->hasOne(Payment::class,'event_id');
    }

    /**
     * Query scope cpds.
     *
     * @param  \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCpds($query)
    {
        return $query->whereNotNull('cpd_id');
    }

    /**
     * Query scope events.
     *
     * @param  \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEvents($query)
    {
        return $query->whereNotNull('event_id');
    }
}
