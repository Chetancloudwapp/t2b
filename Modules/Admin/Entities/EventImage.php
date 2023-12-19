<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Database\factories\EventImageFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventImage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): EventImageFactory
    {
        //return EventImageFactory::new();
    }
}
