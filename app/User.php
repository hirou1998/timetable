<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    //use Notifiable, HasApiTokens;
    use Notifiable;

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function($model){
            $model->id = Str::uuid();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    public function setting()
    {
        return $this->hasOne(Setting::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function colors()
    {
        return $this->hasMany(Color::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }
}
