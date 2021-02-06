<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelLike\Traits\Likeable;

class Post extends Model
{
    use HasFactory, Likeable;

    protected $fillable = ['body'];

    public function comments(){
        return $this->hasMany('App\Comment');
    } 
}
