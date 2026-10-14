<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserMultipleRolesTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Role $adminRole;
    protected Role $departmentRole;
    protected Role $revenueRole;
    protected Department $sampleDepartment;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Roles
        $this->adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $this->departmentRole = Role::firstOrCreate(['name' => 'Department', 'guard_name' => 'web']);
        $this->revenueRole = Role::firstOrCreate(['name' => 'Revenue Department', 'guard_name' => 'web']);

        // Create Permissions
        $adminPerms = ['Users Index', 'Users Create', 'Users Update', 'Users Delete', 'Complaints Index', 'Dashboard'];
        foreach ($adminPerms as $p) {
            $perm = Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
            $this->adminRole->givePermissionTo($perm);
        }

        $revenuePerms = [
            'Revenue Matters Access',
            'Revenue Matters Create',
            'Revenue Matters Forward',
            'Revenue Matters Send Back',
            'Revenue Matters Comment',
        ];
        foreach ($revenuePerms as $p) {
            $perm = Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
            $this->revenueRole->givePermissionTo($perm);
        }

        $deptPerms = ['Complaints Index', 'Dashboard'];
        foreach ($deptPerms as $p) {
            $perm = Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
            $this->departmentRole->givePermissionTo($perm);
        }

        // Admin User
        $this->adminUser = User::create([
            'name' => 'Administrator',
            'username' => 'admin_user',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);
        $this->adminUser->assignRole($this->adminRole);

        // Sample department
        $this->sampleDepartment = Department::create([
            'name' => 'Health Department',
            'code' => 'HLTH',
            'is_active' => 1,
        ]);
    }

    public function test_can_create_user_with_multiple_roles(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('users.store'), [
            'name' => 'Multi Role User',
            'username' => '3520211111111',
            'email' => 'multi@example.com',
            'mobile' => '0300-1234567',
            'roles' => [$this->departmentRole->id, $this->revenueRole->id],
            'department_ids' => [$this->sampleDepartment->id],
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $user = User::where('username', '3520211111111')->first();
        $this->assertNotNull($user);

        // Verify both roles are assigned in Spatie
        $this->assertTrue($user->hasRole('Department'));
        $this->assertTrue($user->hasRole('Revenue Department'));
        $this->assertCount(2, $user->roles);

        // Verify permissions calculate from both roles
        $this->assertTrue($user->can('Revenue Matters Access'));
        $this->assertTrue($user->can('Complaints Index'));
    }

    public function test_edit_user_view_contains_assigned_roles(): void
    {
        $user = User::create([
            'name' => 'Officer ADC',
            'username' => 'adc_revenue',
            'email' => 'adc@example.com',
            'password' => bcrypt('Password@123'),
        ]);
        $user->assignRole([$this->departmentRole, $this->revenueRole]);

        $response = $this->actingAs($this->adminUser)->get(route('users.edit', $user->uuid));
        $response->assertOk();

        // Selected roles array passed to view
        $response->assertViewHas('selectedRoles', function ($selected) use ($user) {
            return in_array($this->departmentRole->id, $selected)
                && in_array($this->revenueRole->id, $selected)
                && count($selected) === 2;
        });
    }

    public function test_can_add_revenue_department_role_to_existing_department_user(): void
    {
        // User originally with only Department role
        $user = User::create([
            'name' => 'Existing Dept Officer',
            'username' => 'existing_dept',
            'email' => 'dept@example.com',
            'password' => bcrypt('Password@123'),
        ]);
        $user->assignRole($this->departmentRole);
        $user->departments()->sync([$this->sampleDepartment->id]);

        $this->assertTrue($user->hasRole('Department'));
        $this->assertFalse($user->hasRole('Revenue Department'));
        $this->assertFalse($user->can('Revenue Matters Access'));

        // Admin updates user to add Revenue Department role alongside Department
        $response = $this->actingAs($this->adminUser)->put(route('users.update', $user->uuid), [
            'id' => $user->id,
            'name' => 'Existing Dept Officer',
            'username' => 'existing_dept',
            'email' => 'dept@example.com',
            'roles' => [$this->departmentRole->id, $this->revenueRole->id],
            'department_ids' => [$this->sampleDepartment->id],
        ]);

        $response->assertRedirect(route('users.index'));

        $user->refresh();

        // Confirm BOTH roles are assigned
        $this->assertTrue($user->hasRole('Department'));
        $this->assertTrue($user->hasRole('Revenue Department'));
        $this->assertCount(2, $user->roles);

        // Confirm Revenue Matters access works
        $this->assertTrue($user->can('Revenue Matters Access'));
        $this->assertTrue($user->can('Revenue Matters Create'));

        // Confirm existing CMS access still works
        $this->assertTrue($user->can('Complaints Index'));
        $this->assertTrue($user->departments->contains($this->sampleDepartment));
    }

    public function test_can_remove_role_during_update(): void
    {
        $user = User::create([
            'name' => 'Dual Officer',
            'username' => 'dual_officer',
            'email' => 'dual@example.com',
            'password' => bcrypt('Password@123'),
        ]);
        $user->assignRole([$this->departmentRole, $this->revenueRole]);
        $this->assertCount(2, $user->roles);

        // Update to keep ONLY Revenue Department
        $response = $this->actingAs($this->adminUser)->put(route('users.update', $user->uuid), [
            'id' => $user->id,
            'name' => 'Dual Officer',
            'username' => 'dual_officer',
            'email' => 'dual@example.com',
            'roles' => [$this->revenueRole->id],
        ]);

        $response->assertRedirect(route('users.index'));

        $user->refresh();
        $this->assertFalse($user->hasRole('Department'));
        $this->assertTrue($user->hasRole('Revenue Department'));
        $this->assertCount(1, $user->roles);
    }

    public function test_validation_requires_at_least_one_role_and_valid_ids(): void
    {
        // Empty roles array
        $response = $this->actingAs($this->adminUser)->post(route('users.store'), [
            'name' => 'Test',
            'username' => 'testuser1',
            'roles' => [],
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);
        $response->assertSessionHasErrors('roles');

        // Non-existent role ID
        $response2 = $this->actingAs($this->adminUser)->post(route('users.store'), [
            'name' => 'Test',
            'username' => 'testuser2',
            'roles' => [99999],
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);
        $response2->assertSessionHasErrors('roles.0');
    }
}
