<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    use HasFactory;
    protected $table = 'permits';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id', 'status', 'level', 'url_module', 'module_id', 'sub_module_id', 'user_id',
    ];    

    public function module()
    {
        return $this->belongsTo(Module::class, 'sub_module_id', 'id');
    }

    public function parentModule()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function subModules($sec, $user)
    {
        return $this->hasMany(Permit::class, 'user_id', 'id');
    }

    public function back()
    {
        return $this->belongsTo(Module::class, 'back_module_id', 'id');
    }
}
