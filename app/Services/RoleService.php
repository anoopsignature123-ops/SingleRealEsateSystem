<?php

namespace App\Services;

use App\Models\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    private $actions = ['list', 'create', 'edit', 'delete', 'view'];

    private $hiddenModules = ['roles', 'permissions'];

    public function getActions()
    {
        return $this->actions;
    }

    public function getRoles()
    {
        $loggedInRole = auth()->user()->roles->first()?->name;

        return Role::where('name', '!=', $loggedInRole)->latest()->get();
    }

    public function getModules()
    {
        return Module::whereNull('parent_id')->whereNotIn('slug', $this->hiddenModules)->with(['children' => function ($query) {
            $query->whereNotIn('slug', $this->hiddenModules);
        },
        ])
            ->orderBy('sort_order')->get();
    }

    public function createRole(array $data)
    {
        $role = Role::create(['name' => $data['name']]);
        if (! empty($data['permissions'])) {
            foreach (
                $data['permissions'] as $permissionName
            ) {
                Permission::firstOrCreate(['name' => $permissionName]);
            }
            $role->syncPermissions($data['permissions']);
        }

        return $role;
    }

    public function findRole($id)
    {
        return Role::findOrFail($id);
    }

    public function getRolePermissions($role)
    {
        return $role->permissions->pluck('name')->toArray();
    }

    public function updateRole($id, array $data)
    {
        $role = Role::findOrFail($id);
        $role->update(['name' => $data['name']]);
        if (! empty($data['permissions'])) {
            foreach (
                $data['permissions'] as $permissionName
            ) {
                Permission::firstOrCreate(['name' => $permissionName]);
            }
        }

        $role->syncPermissions($data['permissions'] ?? []);

        return $role;
    }

    public function deleteRole($id)
    {
        return Role::findOrFail($id)->delete();
    }
}
