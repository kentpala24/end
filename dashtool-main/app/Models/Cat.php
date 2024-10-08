<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    use HasFactory;
    protected $table = 'cats';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id', 'status', 'nom', 'desc', 'level', 'icon', 'color', 'slug', 'filter_on', 'from_id'
    ];
}
