<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content','image','user_id'];

    
    public function getBlogId(){
        return $this->hasMany('App\Models\like','blog_id');
    } 
}
