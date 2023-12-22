<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use App\Models\NewsImage;

class News extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $table = "news";
    protected $fillable = ['title', 'description'];

    protected $translatable = ['title', 'description'];


    public function galleryimages(){
        return $this->hasMany('App\Models\NewsImage')->select('news_id','images','id');

    }


}
