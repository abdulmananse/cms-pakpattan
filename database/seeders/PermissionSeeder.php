<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'Super Admin',
            'Admin',
            'Complainant',
        ];

        foreach($roles as $role) {
            Role::updateOrCreate(['name' => $role]);
        }
        
        $permissions = [
            'Group:Dashboard',
            'Dashboard',
            'Group:Users',
            'Users Index',
            'Users Create',
            'Users Update',
            'Users Update Status',
            'Users Delete',
            'Group:ACL',
            'Roles Index',
            'Roles Create',
            'Roles Update',
            'Roles Update Status',
            'Roles Delete',
            'Permissions Index',
            'Permissions Create',
            'Permissions Update',
            'Permissions Update Status',
            'Permissions Delete',
            'Group:Settings',
            'Settings Index',
            'Settings Update',
            'Group:Reports'
        ];

        $permissionGroupId = 0;
        $ordering = 1;
        $lastOrdering = PermissionGroup::orderby('ordering', 'desc')->first();
        if ($lastOrdering) {
            $ordering = $lastOrdering->ordering+1;
        }
        foreach($permissions as $permission) {
            $permissionGroup = explode(':', $permission);
            if ($permissionGroup[0] == 'Group') {
                $permissionGroup = PermissionGroup::updateOrCreate([
                    'name' => $permissionGroup[1]
                ], [
                    'ordering' => $ordering
                ]);
                $permissionGroupId = $permissionGroup->id;
                $ordering++;
            } else {
                Permission::updateOrCreate([
                    'name' => $permission
                ], [
                    'permission_group_id' => $permissionGroupId
                ]);
            }
            
        }
    }
}
