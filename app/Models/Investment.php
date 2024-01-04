<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Investment extends Model
{
    use HasFactory, SoftDeletes;

    public function get_investments(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id')->with(['get_region','country']);
    }
}
