<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Database\factories\LanguageFactory;

class Language extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): LanguageFactory
    {
        //return LanguageFactory::new();
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function($language){
            $language->slug = Str::slug($language->name);
        });

        // updating the slug if the name changes
        static::updating(function($language){
            if($language->isDirty('name')) {
                $language->slug = Str::slug($language->name);
            }
        });
    }
}
