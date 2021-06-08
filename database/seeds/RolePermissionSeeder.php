<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleInDatabase = Role::where('name', config('permission.default_roles')[0]);
        if ($roleInDatabase->count() < 1) {
            foreach (config('permission.default_roles') as $role) {
                Role::create([
                    'name' => $role
                ]);
            }
        }

        $permissionInDatabase = Permission::where('name', config('permission.default_permissions')[0]);
        if ($permissionInDatabase->count() < 1) {
            foreach (config('permission.default_permissions') as $permission) {
                Permission::create([
                    'name' => $permission
                ]);
            }
        } 
    }
}
