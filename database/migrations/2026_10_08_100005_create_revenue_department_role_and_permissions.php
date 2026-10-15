<?php

use App\Models\PermissionGroup;
use App\Models\Permission;
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

        // 1. Permission Group
        $lastOrdering = PermissionGroup::orderBy('ordering', 'desc')->first();
        $ordering = $lastOrdering ? $lastOrdering->ordering + 1 : 1;

        $group = PermissionGroup::firstOrCreate(
            ['name' => 'Revenue Department Matters'],
            ['ordering' => $ordering]
        );

        // 2. Permissions
        $permissions = [
            'Revenue Matters Access',
            'Revenue Matters Create',
            'Revenue Matters Forward',
            'Revenue Matters Send Back',
            'Revenue Matters Comment',
            'Revenue Matters View All',
        ];

        $createdPermissions = [];
        foreach ($permissions as $permName) {
            $createdPermissions[$permName] = Permission::firstOrCreate(
                ['name' => $permName],
                [
                    'guard_name' => 'web',
                    'permission_group_id' => $group->id,
                ]
            );
        }

        // 3. Revenue Department Role
        $revenueRole = Role::firstOrCreate(['name' => 'Revenue Department', 'guard_name' => 'web']);

        // Assign standard operational permissions to Revenue Department role (exclude View All)
        $operationalPermissions = [
            'Revenue Matters Access',
            'Revenue Matters Create',
            'Revenue Matters Forward',
            'Revenue Matters Send Back',
            'Revenue Matters Comment',
        ];

        foreach ($operationalPermissions as $permName) {
            if (isset($createdPermissions[$permName]) && !$revenueRole->hasPermissionTo($permName)) {
                $revenueRole->givePermissionTo($createdPermissions[$permName]);
            }
        }

        // 4. Super Admin role receives all permissions (Ordinary Admin is strictly excluded)
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            foreach ($createdPermissions as $perm) {
                if (!$superAdminRole->hasPermissionTo($perm->name)) {
                    $superAdminRole->givePermissionTo($perm);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'Revenue Matters Access',
            'Revenue Matters Create',
            'Revenue Matters Forward',
            'Revenue Matters Send Back',
            'Revenue Matters Comment',
            'Revenue Matters View All',
        ];

        foreach ($permissions as $permName) {
            $perm = Permission::where('name', $permName)->first();
            if ($perm) {
                $perm->roles()->detach();
                $perm->delete();
            }
        }

        $revenueRole = Role::where('name', 'Revenue Department')->first();
        if ($revenueRole) {
            $revenueRole->delete();
        }

        PermissionGroup::where('name', 'Revenue Department Matters')->delete();
    }
};
