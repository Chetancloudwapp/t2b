<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed','id'=>'string'
    ];

    // get coutry name
    public function country(){
        return $this->hasOne('Modules\Admin\Entities\Country', 'id', 'country_id')->select('id','name');
    }

    // get region name
    public function get_region(){
        return $this->hasOne('Modules\Admin\Entities\Region', 'id', 'region')->select('id','name');
    }

    // get offers
    public function Offers(){
        return $this->hasMany('App\Models\Offer');
    }

    // get events
    public function Eventfeedback(){
        return $this->hasMany('App\Models\EventFeedback');
    }

    public function Investments(){
        return $this->hasMany('App\Models\Investment');
    }
}
