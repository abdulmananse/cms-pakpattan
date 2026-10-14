<?php

use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Ensure / update Permission Group 'Land Revenue Matters'
        $group = PermissionGroup::where('name', 'Revenue Department Matters')->first();
        if ($group) {
            $group->update(['name' => 'Land Revenue Matters']);
        } else {
            $lastOrdering = PermissionGroup::orderBy('ordering', 'desc')->first();
            $ordering = $lastOrdering ? $lastOrdering->ordering + 1 : 1;
            $group = PermissionGroup::firstOrCreate(
                ['name' => 'Land Revenue Matters'],
                ['ordering' => $ordering]
            );
        }

        // 2. Rename existing 6 permissions if present, otherwise create under new names
        $existingRenameMap = [
            'Revenue Matters Access' => 'Land Revenue Matters Access',
            'Revenue Matters Create' => 'Land Revenue Matters Create',
            'Revenue Matters Forward' => 'Land Revenue Matters Forward',
            'Revenue Matters Send Back' => 'Land Revenue Matters Send Back',
            'Revenue Matters Comment' => 'Land Revenue Matters Comment',
            'Revenue Matters View All' => 'Land Revenue Matters View All',
        ];

        $allPermissions = [];
        foreach ($existingRenameMap as $oldName => $newName) {
            $perm = Permission::where('name', $oldName)->first();
            if ($perm) {
                $perm->update([
                    'name' => $newName,
                    'permission_group_id' => $group->id,
                ]);
                $allPermissions[$newName] = $perm;
            } else {
                $allPermissions[$newName] = Permission::firstOrCreate(
                    ['name' => $newName],
                    [
                        'guard_name' => 'web',
                        'permission_group_id' => $group->id,
                    ]
                );
            }
        }

        // 3. Create the 4 new permissions
        $additionalPermissions = [
            'Land Revenue Matters Search',
            'Land Revenue Matters Resolve',
            'Land Revenue Matters Reopen',
            'Land Revenue Matters Dashboard',
        ];

        foreach ($additionalPermissions as $permName) {
            $allPermissions[$permName] = Permission::firstOrCreate(
                ['name' => $permName],
                [
                    'guard_name' => 'web',
                    'permission_group_id' => $group->id,
                ]
            );
        }

        // 4. Update permissions for Role: Revenue Department (operational officer)
        $revenueRole = Role::firstOrCreate(['name' => 'Revenue Department', 'guard_name' => 'web']);
        $revenueOperationalPerms = [
            'Land Revenue Matters Access',
            'Land Revenue Matters Create',
            'Land Revenue Matters Forward',
            'Land Revenue Matters Send Back',
            'Land Revenue Matters Comment',
            'Land Revenue Matters Search',
            'Land Revenue Matters Dashboard',
        ];

        foreach ($revenueOperationalPerms as $permName) {
            if (isset($allPermissions[$permName]) && !$revenueRole->hasPermissionTo($permName)) {
                $revenueRole->givePermissionTo($allPermissions[$permName]);
            }
        }

        // Ensure Revenue Department does NOT have View All, Resolve, Reopen
        $disallowedPerms = [
            'Land Revenue Matters View All',
            'Land Revenue Matters Resolve',
            'Land Revenue Matters Reopen',
        ];
        foreach ($disallowedPerms as $permName) {
            if ($revenueRole->hasPermissionTo($permName)) {
                $revenueRole->revokePermissionTo($permName);
            }
        }

        // 5. Create Role: Revenue Department Admin
        $revenueAdminRole = Role::firstOrCreate(['name' => 'Revenue Department Admin', 'guard_name' => 'web']);

        // Revenue Department Admin receives the full Land Revenue Matters permission set
        $adminPerms = [
            'Land Revenue Matters Access',
            'Land Revenue Matters Dashboard',
            'Land Revenue Matters Create',
            'Land Revenue Matters Forward',
            'Land Revenue Matters Send Back',
            'Land Revenue Matters Comment',
            'Land Revenue Matters Search',
            'Land Revenue Matters View All',
            'Land Revenue Matters Resolve',
            'Land Revenue Matters Reopen',
        ];

        foreach ($adminPerms as $permName) {
            if (isset($allPermissions[$permName]) && !$revenueAdminRole->hasPermissionTo($permName)) {
                $revenueAdminRole->givePermissionTo($allPermissions[$permName]);
            }
        }

        // 6. Super Admin role receives all new Land Revenue permissions
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            foreach ($allPermissions as $perm) {
                if (!$superAdminRole->hasPermissionTo($perm->name)) {
                    $superAdminRole->givePermissionTo($perm);
                }
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Detach and delete Revenue Department Admin role
        $revenueAdminRole = Role::where('name', 'Revenue Department Admin')->first();
        if ($revenueAdminRole) {
            $revenueAdminRole->permissions()->detach();
            $revenueAdminRole->delete();
        }

        // Remove new permissions
        $additionalPermissions = [
            'Land Revenue Matters Search',
            'Land Revenue Matters Resolve',
            'Land Revenue Matters Reopen',
            'Land Revenue Matters Dashboard',
        ];

        foreach ($additionalPermissions as $permName) {
            $perm = Permission::where('name', $permName)->first();
            if ($perm) {
                $perm->roles()->detach();
                $perm->delete();
            }
        }

        // Revert permission renames
        $existingRenameMap = [
            'Land Revenue Matters Access' => 'Revenue Matters Access',
            'Land Revenue Matters Create' => 'Revenue Matters Create',
            'Land Revenue Matters Forward' => 'Revenue Matters Forward',
            'Land Revenue Matters Send Back' => 'Revenue Matters Send Back',
            'Land Revenue Matters Comment' => 'Revenue Matters Comment',
            'Land Revenue Matters View All' => 'Revenue Matters View All',
        ];

        $group = PermissionGroup::where('name', 'Land Revenue Matters')->first();
        if ($group) {
            $group->update(['name' => 'Revenue Department Matters']);
        }

        foreach ($existingRenameMap as $newName => $oldName) {
            $perm = Permission::where('name', $newName)->first();
            if ($perm) {
                $perm->update([
                    'name' => $oldName,
                    'permission_group_id' => $group?->id,
                ]);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
