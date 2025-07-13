<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        "user_id","category_id","title","slug","contant","excrept","thumpnile","status","publishd_at"
    ];
}
