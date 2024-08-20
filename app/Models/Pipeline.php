<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pipeline extends Model
{
    use HasFactory,softDeletes;

    public function stages()
    {
        return $this->hasMany('App\Models\Stage', 'pipeline_id', 'id')->orderBy('order');
    }

    public function leadStages()
    {
        return $this->hasMany('App\Models\LeadStage', 'pipeline_id', 'id')->orderBy('order');
    }
}
