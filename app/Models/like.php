<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class like extends Model
{
    protected $fillable=[
        'like',
        'blog_id',
        'user_id',
        'count',
    ];
    use HasFactory;
}
