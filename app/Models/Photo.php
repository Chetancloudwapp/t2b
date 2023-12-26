<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use App\Models\PhotoGallery;

class Photo extends Model
{
    use HasFactory, SoftDeletes;
    use HasTranslations;

    protected $fillable = ['title'];

    protected $translatable = ['title'];

    public function photosgallery()
    {
        return $this->hasMany('App\Models\PhotoGallery')->select('id','photo_id', 'images'); 
    }

}
