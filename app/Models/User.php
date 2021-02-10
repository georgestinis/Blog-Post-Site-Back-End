<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Overtrue\LaravelLike\Traits\Liker;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Liker;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'gender',
        'city',
        'avatar',        
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    // user has many posts
    public function comments() 
    {
        return $this->hasMany('App\Comment', 'from_user');
    }
    
    // user has many comments
    public function posts() 
    {
        return $this->hasMany('App\Post', 'author_id')
    }

    public function can_post() 
    {
        $role = $this->role;

        if ($role == 'author' || $role== 'admin') {
            return true;
        }
        return false;
    }

    public function is_admin() 
    {
        $role = $this->role;

        if ($role == 'admin') {
            return true;
        }
        return false;
    }
}
