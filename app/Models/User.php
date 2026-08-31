<?php

namespace App\Models;

use App\Notifications\ApiResetPasswordNotification;
use Laravel\Passport\HasApiTokens; // ✅ Add this
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, Notifiable; // ✅ Include it here

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'status',
        'user_id',
        'is_supper_admin',
        'created_by',
        'updated_by',
        'deleted_by',
        'company_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


     public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdUsers()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function getPermissions()
    {
        $permissions = array();
        $role_ids = $this->roles()
            ->pluck('role_id')
            ->toArray();
        if (!empty($role_ids)) {
            $permission_ids = RolePermission::whereIn('role_id', $role_ids)
                ->pluck('permission_id')
                ->toArray();

            if (!empty($permission_ids)) {
                $permissions = Permission::whereIn('id', $permission_ids)
                    ->pluck('permission')
                    ->toArray();
            }
        }

        return $permissions;
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ApiResetPasswordNotification($token, $this->email));
    }
}
