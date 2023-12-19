<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Database\factories\EventFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\EventImage;

class Event extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    public $translatable = ['name', 'description'];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];
    
    protected static function newFactory(): EventFactory
    {
        //return EventFactory::new();
    }

    public function galleryimages(){
        return $this->hasMany('Modules\Admin\Entities\EventImage')->select('event_id','images');

    }
    
}
