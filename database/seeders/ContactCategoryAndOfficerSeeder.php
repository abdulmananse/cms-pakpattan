<?php

namespace Database\Seeders;

use App\Models\ContactCategory;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ContactCategoryAndOfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Initial Contact Categories
        // $defaultCategories = [
        //     [
        //         'name' => 'District Administration',
        //         'description' => 'Deputy Commissioner, Assistant Commissioners, and district admin staff',
        //         'is_active' => 1,
        //     ],
        //     [
        //         'name' => 'Police',
        //         'description' => 'DPO, SDPO, SHOs and district police officials',
        //         'is_active' => 1,
        //     ],
        //     [
        //         'name' => 'Health',
        //         'description' => 'CEO Health, MS DHQ, THQ doctors and health officers',
        //         'is_active' => 1,
        //     ],
        //     [
        //         'name' => 'Revenue',
        //         'description' => 'Tehsildars, Naib Tehsildars, and revenue field staff',
        //         'is_active' => 1,
        //     ],
        //     [
        //         'name' => 'Local Government',
        //         'description' => 'Municipal Committee officers and union council secretaries',
        //         'is_active' => 1,
        //     ],
        //     [
        //         'name' => 'Emergency Contacts',
        //         'description' => 'Rescue 1122, Disaster Management, Fire Brigade',
        //         'is_active' => 1,
        //     ],
        //     [
        //         'name' => 'General Contacts',
        //         'description' => 'General public dealing officers and desks',
        //         'is_active' => 1,
        //     ],
        // ];

        // foreach ($defaultCategories as $cat) {
        //     ContactCategory::firstOrCreate(
        //         ['name' => $cat['name']],
        //         [
        //             'description' => $cat['description'],
        //             'is_active' => $cat['is_active'],
        //         ]
        //     );
        // }

        // 2. Seed Permissions & Permission Groups
        $groups = [
            'Officer Contacts' => [
                'Officer Contacts Index',
                'Officer Contacts Create',
                'Officer Contacts Update',
                'Officer Contacts Delete',
            ],
            'Contact Categories' => [
                'Contact Categories Index',
                'Contact Categories Create',
                'Contact Categories Update',
                'Contact Categories Delete',
            ],
        ];

        $lastOrdering = PermissionGroup::orderBy('ordering', 'desc')->first();
        $ordering = $lastOrdering ? $lastOrdering->ordering + 1 : 1;

        $allNewPermissions = [];

        foreach ($groups as $groupName => $permissionList) {
            $group = PermissionGroup::firstOrCreate(
                ['name' => $groupName],
                ['ordering' => $ordering++]
            );

            foreach ($permissionList as $permName) {
                $permission = Permission::firstOrCreate(
                    ['name' => $permName],
                    ['permission_group_id' => $group->id]
                );
                $allNewPermissions[] = $permission;
            }
        }

        // 3. Assign to Super Admin and Admin roles if they exist
        $adminRoles = Role::whereIn('name', ['Super Admin', 'Admin'])->get();
        foreach ($adminRoles as $role) {
            foreach ($allNewPermissions as $perm) {
                if (!$role->hasPermissionTo($perm->name)) {
                    $role->givePermissionTo($perm);
                }
            }
        }
    }
}
