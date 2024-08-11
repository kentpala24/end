<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $table = 'modules';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id', 'status', 'type', 'nom', 'desc', 'icon', 'url_module', 'color', 'url_sub_module', 'back_module_id', 'show_on', 'module_id'
    ];

    public function parentModule()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function back()
    {
        return $this->belongsTo(Module::class, 'back_module_id', 'id');
    }

    public function isPermit($user)
    {
        return $this->hasOne(Permit::class, 'mod_id', 'id_mod')->where('user_id', $user)->first();
    }

    public function subModules()
    {
        return $this->hasMany(Module::class, 'module_id', 'id')->where('status', '>', 0);
    }
}
