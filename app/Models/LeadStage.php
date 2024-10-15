<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadStage extends Model
{
    use HasFactory,softDeletes;

    protected $fillable = [
        'name',
        'pipeline_id',
        'created_by',
        'order',
    ];

    public function lead()
    {
        if(\Auth::user()->type=='company'){
            return Lead::select('leads.*')->where('leads.stage_id', '=', $this->id)->orderBy('leads.order')->get();
        }else{
            return Lead::select('leads.*')->join('user_leads', 'user_leads.lead_id', '=', 'leads.id')->where('user_leads.user_id', '=', \Auth::user()->id)->where('leads.stage_id', '=', $this->id)->orderBy('leads.order')->get();

        }

    }
}
