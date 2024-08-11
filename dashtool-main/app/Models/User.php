<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'name', 'email', 'email_verified_at', 'password', 'status', 'remember_token', 'created_at', 'level_cat_id', 'updated_at', 'files', 'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function menuNavbar()
    {
        return $this->hasMany(Permit::class, 'user_id', 'id')->where(function (Builder $q) {
            $q->where('status', 1);
        })->whereHas('module', function (Builder $q) {
            $q->where('status', 1);
            $q->whereIn('show_on', ['navbar', 'all']);
            $q->where('status', 1);
            $q->orderBy('desc', 'asc');
        });
    }

    public function permits()
    {
        return $this->hasMany(Permit::class, 'user_id', 'id')
            ->where('status', 1)
            ->whereHas('module', function (Builder $q) {
                $q->where('modules.status', 1);
                $q->where('modules.type', 'widget');
            })->with(['module' => function ($q) {
                $q->orderBy('desc', 'asc');
            }]);
    }

    public function isPermitUrl($data)
    {
        return $this->hasOne(Permit::class, 'user_id', 'id')->where('url_module', $data['url'])->whereHas('module', function (Builder $q) {
            $q->where('type', 'module');
        })->first();
    }

    public function level()
    {
        return $this->belongsTo(Cat::class, 'level_cat_id', 'id')->where('filter_on', 'users');
    }

    public function permisos()
    {
        return $this->hasMany(Permit::class, 'user_id', 'id')
            ->where('status', 1)
            ->whereHas('module', function (Builder $q) {
                $q->where('modules.status', 1);
            });
    }
}
