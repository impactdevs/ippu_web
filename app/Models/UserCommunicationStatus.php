<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCommunicationStatus extends Model
{
    use HasFactory;

    protected $table = 'user_communication_status';

    protected $fillable = ['user_id', 'communication_id', 'status', 'created_at', 'updated_at'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function communication()
    {
        return $this->belongsTo(Communication::class);
    }
}
