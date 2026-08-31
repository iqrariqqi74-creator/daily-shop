<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolePermission extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'role_id',
        'permission_id',
        'user_id',
        'created_by',
        'updated_by',
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function user(){
        return $this->belongsTo(user::class, 'user_id');
    }
}
