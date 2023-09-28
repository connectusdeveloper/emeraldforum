<?php

namespace App\Models;

use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    public function updatePermissions(array $permissions)
    {
        if (! empty($permissions)) {
            $permissions = collect($permissions)->filter()->keys()->all();
            foreach ($permissions as $permission) {
                Permission::updateOrCreate(['name' => $permission], ['name' => $permission, 'guard_name' => 'web']);
            }
            $this->syncPermissions($permissions);
        }
    }
}
