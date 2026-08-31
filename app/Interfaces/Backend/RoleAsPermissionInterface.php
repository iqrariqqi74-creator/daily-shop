<?php

namespace App\Interfaces\Backend;

interface RoleAsPermissionInterface
{
    public function getRolePermission($request);
    public function create($request);
    public function delete($request, $id);
}
