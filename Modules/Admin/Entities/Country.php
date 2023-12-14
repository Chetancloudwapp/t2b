<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['is_show'];
    
    protected static function newFactory()
    {
        return \Modules\Admin\Database\factories\CountryFactory::new();
    }
}
