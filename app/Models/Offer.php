<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Offer;


class Offer extends Model
{
    use HasFactory, SoftDeletes;

    // public function offerImage(){
    //     return $this->hasMany('App\Models\OfferImage')->select('offer_id','offer_images');
    // }
}


