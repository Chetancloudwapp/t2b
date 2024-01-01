<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class EventFeedback extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'event_feedbacks';

    public function Events(){
        return $this->belongsTo('Modules\Admin\Entities\Event','event_id','id');
    }

}
