<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['title', 'content', 'slug', 'image', 'status', 'fc', 'user_id'];


    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
