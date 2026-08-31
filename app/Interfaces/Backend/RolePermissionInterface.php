<?php

namespace App\Interfaces\Backend;

interface RolePermissionInterface
{
    public function getPermissions($request);
    public function create($request);
}