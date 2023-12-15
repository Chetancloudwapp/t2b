<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Entities\Region;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['is_show'];
    
    protected static function newFactory()
    {
        return \Modules\Admin\Database\factories\CountryFactory::new();
    }

    public function region(){
        return $this->hasMany('Modules\Admin\Entities\Region', 'country', 'country_id');
    }
}
