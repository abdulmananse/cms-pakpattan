<?php

namespace Tests\Feature;

use App\Enums\RevenueComplaintStatus;
use App\Models\RevenueComplaint;
use App\Models\RevenueComplaintLog;
use App\Models\RevenueComplaintSequence;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RevenueDepartmentMattersTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $officerA;
    protected User $officerB;
    protected User $officerC;
    protected User $unauthorizedUser;
    protected Role $revenueRole;
    protected Role $superAdminRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Roles and Permissions
        $this->superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $this->revenueRole = Role::firstOrCreate(['name' => 'Revenue Department', 'guard_name' => 'web']);

        $permissions = [
            'Revenue Matters Access',
            'Revenue Matters Create',
            'Revenue Matters Forward',
            'Revenue Matters Send Back',
            'Revenue Matters Comment',
        ];

        foreach ($permissions as $p) {
            $perm = Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
            $this->revenueRole->givePermissionTo($perm);
        }

        // Setup Users
        $this->superAdmin = User::create([
            'name' => 'Super Administrator',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'designation' => 'System Admin',
        ]);
        $this->superAdmin->assignRole('Super Admin');

        $this->officerA = User::create([
            'name' => 'ADCR Pakpattan',
            'username' => 'adcr',
            'email' => 'adcr@example.com',
            'password' => bcrypt('password'),
            'designation' => 'Additional Deputy Commissioner (Revenue)',
        ]);
        $this->officerA->assignRole('Revenue Department');

        $this->officerB = User::create([
            'name' => 'AC Pakpattan',
            'username' => 'ac_pkn',
            'email' => 'ac@example.com',
            'password' => bcrypt('password'),
            'designation' => 'Assistant Commissioner',
        ]);
        $this->officerB->assignRole('Revenue Department');

        $this->officerC = User::create([
            'name' => 'Tehsildar Pakpattan',
            'username' => 'tehsildar_pkn',
            'email' => 'tehsildar@example.com',
            'password' => bcrypt('password'),
            'designation' => 'Tehsildar',
        ]);
        $this->officerC->assignRole('Revenue Department');

        $this->unauthorizedUser = User::create([
            'name' => 'Regular Citizen/User',
            'username' => 'regular_user',
            'email' => 'regular@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    /** @test */
    public function sequential_numbering_generates_correct_format_and_increments()
    {
        $year = (int) date('Y');

        $num1 = RevenueComplaintSequence::getNextComplaintNumber($year);
        $this->assertEquals(sprintf('REV-%04d-00001', $year), $num1);

        $num2 = RevenueComplaintSequence::getNextComplaintNumber($year);
        $this->assertEquals(sprintf('REV-%04d-00002', $year), $num2);
    }

    /** @test */
    public function complaint_can_be_lodged_with_formatted_cnic_and_initial_attachment()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->officerA)->post(route('revenue-matters.complaints.store'), [
            'citizen_name' => 'Muhammad Ali',
            'father_husband_name' => 'Ahmad Khan',
            'cnic' => '35202-1234567-1',
            'contact_number' => '03001234567',
            'address' => 'Mauza Chak 15/SP',
            'tehsil' => 'Pakpattan',
            'subject' => 'Boundary Demarcation Dispute',
            'description' => 'Dispute regarding agricultural land demarcation between adjacent owners.',
            'attachments' => [
                UploadedFile::fake()->create('aks_shajra.pdf', 500, 'application/pdf'),
            ],
        ]);

        $complaint = RevenueComplaint::first();
        $this->assertNotNull($complaint);
        $this->assertIsString($complaint->uuid);
        $this->assertNotEmpty($complaint->uuid);
        $this->assertEquals($complaint->uuid, (string) $complaint->getRouteKey());

        $expectedShowUrl = route('revenue-matters.complaints.show', $complaint);
        $response->assertRedirect($expectedShowUrl);

        // Follow the redirect and verify show page renders successfully with scalar route key
        $showResponse = $this->actingAs($this->officerA)->get($expectedShowUrl);
        $showResponse->assertStatus(200);
        $showResponse->assertSee($complaint->complaint_number);
        $showResponse->assertSee('Boundary Demarcation Dispute');

        $this->assertEquals('35202-1234567-1', $complaint->cnic);
        $this->assertEquals(RevenueComplaintStatus::OPEN, $complaint->status);
        $this->assertEquals($this->officerA->id, $complaint->creator_id);
        $this->assertEquals($this->officerA->id, $complaint->current_holder_id);
        $this->assertEquals([$this->officerA->id], $complaint->custody_stack);

        // Verify initial log
        $this->assertDatabaseHas('revenue_complaint_logs', [
            'revenue_complaint_id' => $complaint->id,
            'user_id' => $this->officerA->id,
            'action' => 'created',
        ]);

        // Verify initial attachment
        $this->assertEquals(1, $complaint->attachments()->count());
        $this->assertEquals('initial', $complaint->attachments()->first()->attachment_type);
    }

    /** @test */
    public function complaint_can_be_forwarded_and_custody_stack_updates()
    {
        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Muhammad Ali',
            'cnic' => '35202-1234567-1',
            'subject' => 'Land Issue',
            'description' => 'Test description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'custody_stack' => [$this->officerA->id],
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        // Officer A forwards to Officer B
        $response = $this->actingAs($this->officerA)->post(route('revenue-matters.complaints.forward', $complaint), [
            'recipient_id' => $this->officerB->id,
            'instructions' => 'Please conduct spot visit and report within 3 days.',
        ]);

        $response->assertRedirect(route('revenue-matters.complaints.show', $complaint));
        $complaint->refresh();

        $this->assertEquals(RevenueComplaintStatus::IN_PROCESS, $complaint->status);
        $this->assertEquals($this->officerB->id, $complaint->current_holder_id);
        $this->assertEquals([$this->officerA->id, $this->officerB->id], $complaint->custody_stack);

        $this->assertDatabaseHas('revenue_complaint_logs', [
            'revenue_complaint_id' => $complaint->id,
            'user_id' => $this->officerA->id,
            'action' => 'forwarded',
            'from_user_id' => $this->officerA->id,
            'to_user_id' => $this->officerB->id,
            'comments' => 'Please conduct spot visit and report within 3 days.',
        ]);
    }

    /** @test */
    public function complaint_can_be_sent_back_and_stack_unwinds_correctly()
    {
        // Setup complaint at Officer C via A -> B -> C
        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Muhammad Ali',
            'subject' => 'Land Issue',
            'description' => 'Test description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerC->id,
            'custody_stack' => [$this->officerA->id, $this->officerB->id, $this->officerC->id],
            'status' => RevenueComplaintStatus::IN_PROCESS,
        ]);

        // Officer C sends back to Officer B
        $response = $this->actingAs($this->officerC)->post(route('revenue-matters.complaints.sendBack', $complaint), [
            'report' => 'Inquiry conducted on spot. Submitting report to AC.',
        ]);

        $response->assertRedirect(route('revenue-matters.complaints.show', $complaint));
        $complaint->refresh();

        $this->assertEquals($this->officerB->id, $complaint->current_holder_id);
        $this->assertEquals([$this->officerA->id, $this->officerB->id], $complaint->custody_stack);

        // Officer B sends back to Creator Officer A
        $response2 = $this->actingAs($this->officerB)->post(route('revenue-matters.complaints.sendBack', $complaint), [
            'report' => 'Forwarding Tehsildar findings to ADCR for final order.',
        ]);

        $response2->assertRedirect(route('revenue-matters.complaints.show', $complaint));
        $complaint->refresh();

        $this->assertEquals($this->officerA->id, $complaint->current_holder_id);
        $this->assertEquals([$this->officerA->id], $complaint->custody_stack);

        // Officer A (creator at root) cannot send back further
        $response3 = $this->actingAs($this->officerA)->post(route('revenue-matters.complaints.sendBack', $complaint), [
            'report' => 'Attempting invalid send back',
        ]);
        $response3->assertStatus(403);
    }

    /** @test */
    public function complex_delegation_cycle_unwinds_deterministically()
    {
        // Cycle: A -> B -> C -> B -> C
        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Muhammad Ali',
            'subject' => 'Complex Cycle Dispute',
            'description' => 'Boundary re-measurement',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerC->id,
            'custody_stack' => [
                $this->officerA->id,
                $this->officerB->id,
                $this->officerC->id,
                $this->officerB->id,
                $this->officerC->id,
            ],
            'status' => RevenueComplaintStatus::IN_PROCESS,
        ]);

        // 1st Send Back: from C back to B
        $this->actingAs($this->officerC)->post(route('revenue-matters.complaints.sendBack', $complaint), [
            'report' => 'Clarification provided to AC.',
        ]);
        $complaint->refresh();
        $this->assertEquals($this->officerB->id, $complaint->current_holder_id);
        $this->assertEquals([$this->officerA->id, $this->officerB->id, $this->officerC->id, $this->officerB->id], $complaint->custody_stack);

        // 2nd Send Back: from B back to C
        $this->actingAs($this->officerB)->post(route('revenue-matters.complaints.sendBack', $complaint), [
            'report' => 'Returning to Tehsildar.',
        ]);
        $complaint->refresh();
        $this->assertEquals($this->officerC->id, $complaint->current_holder_id);
        $this->assertEquals([$this->officerA->id, $this->officerB->id, $this->officerC->id], $complaint->custody_stack);

        // 3rd Send Back: from C back to B
        $this->actingAs($this->officerC)->post(route('revenue-matters.complaints.sendBack', $complaint), [
            'report' => 'Sending again to AC.',
        ]);
        $complaint->refresh();
        $this->assertEquals($this->officerB->id, $complaint->current_holder_id);
        $this->assertEquals([$this->officerA->id, $this->officerB->id], $complaint->custody_stack);

        // 4th Send Back: from B back to A
        $this->actingAs($this->officerB)->post(route('revenue-matters.complaints.sendBack', $complaint), [
            'report' => 'Sending back to ADCR.',
        ]);
        $complaint->refresh();
        $this->assertEquals($this->officerA->id, $complaint->current_holder_id);
        $this->assertEquals([$this->officerA->id], $complaint->custody_stack);
    }

    /** @test */
    public function progress_comments_can_be_added_without_changing_custody()
    {
        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Muhammad Ali',
            'subject' => 'Land Issue',
            'description' => 'Test description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerB->id,
            'custody_stack' => [$this->officerA->id, $this->officerB->id],
            'status' => RevenueComplaintStatus::IN_PROCESS,
        ]);

        $response = $this->actingAs($this->officerB)->post(route('revenue-matters.complaints.comment', $complaint), [
            'comment' => 'Called both parties for hearing tomorrow at 10 AM.',
        ]);

        $response->assertRedirect(route('revenue-matters.complaints.show', $complaint));
        $complaint->refresh();

        // Custody stack and current holder must remain unchanged
        $this->assertEquals($this->officerB->id, $complaint->current_holder_id);
        $this->assertEquals([$this->officerA->id, $this->officerB->id], $complaint->custody_stack);

        $this->assertDatabaseHas('revenue_complaint_logs', [
            'revenue_complaint_id' => $complaint->id,
            'user_id' => $this->officerB->id,
            'action' => 'comment_added',
            'comments' => 'Called both parties for hearing tomorrow at 10 AM.',
        ]);
    }

    /** @test */
    public function closing_and_reopening_rules_are_strictly_enforced()
    {
        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Muhammad Ali',
            'subject' => 'Land Issue',
            'description' => 'Test description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerB->id,
            'custody_stack' => [$this->officerA->id, $this->officerB->id],
            'status' => RevenueComplaintStatus::IN_PROCESS,
        ]);

        // Officer B (not creator) cannot close
        $response1 = $this->actingAs($this->officerB)->post(route('revenue-matters.complaints.close', $complaint), [
            'closing_comments' => 'Attempting to close by non-creator',
        ]);
        $response1->assertStatus(403);

        // Creator Officer A cannot close while custody is with B
        $response2 = $this->actingAs($this->officerA)->post(route('revenue-matters.complaints.close', $complaint), [
            'closing_comments' => 'Attempting to close while custody is away',
        ]);
        $response2->assertStatus(403);

        // Super Admin CAN administratively close
        $responseSuper = $this->actingAs($this->superAdmin)->post(route('revenue-matters.complaints.close', $complaint), [
            'closing_comments' => 'Administratively closed by Super Admin after review.',
        ]);
        $responseSuper->assertRedirect(route('revenue-matters.complaints.show', $complaint));
        $complaint->refresh();

        $this->assertEquals(RevenueComplaintStatus::CLOSED, $complaint->status);
        $this->assertEquals($this->superAdmin->id, $complaint->closed_by);
        $this->assertNotNull($complaint->closed_at);
        $this->assertEquals('Administratively closed by Super Admin after review.', $complaint->closing_comments);

        // Creator Officer A reopens the complaint
        $responseReopen = $this->actingAs($this->officerA)->post(route('revenue-matters.complaints.reopen', $complaint), [
            'reopening_reason' => 'Citizen submitted fresh demarcation evidence.',
        ]);
        $responseReopen->assertRedirect(route('revenue-matters.complaints.show', $complaint));
        $complaint->refresh();

        $this->assertEquals(RevenueComplaintStatus::IN_PROCESS, $complaint->status);
        $this->assertEquals($this->officerA->id, $complaint->reopened_by);
        $this->assertEquals($this->officerA->id, $complaint->current_holder_id);
        $this->assertEquals([$this->officerA->id], $complaint->custody_stack);

        // Verify permanent history has all logs: closed and reopened
        $this->assertDatabaseHas('revenue_complaint_logs', [
            'revenue_complaint_id' => $complaint->id,
            'action' => 'closed',
        ]);
        $this->assertDatabaseHas('revenue_complaint_logs', [
            'revenue_complaint_id' => $complaint->id,
            'action' => 'reopened',
            'comments' => 'Citizen submitted fresh demarcation evidence.',
        ]);
    }

    /** @test */
    public function unauthorized_users_cannot_access_revenue_matters()
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('revenue-matters.dashboard'));
        $response->assertStatus(403);

        $responseIndex = $this->actingAs($this->unauthorizedUser)->get(route('revenue-matters.complaints.index'));
        $responseIndex->assertStatus(403);
    }

    /** @test */
    public function complaint_view_access_isolation_prevents_unrelated_revenue_officers()
    {
        // Another revenue officer who never held or participated in this complaint
        $officerD = User::create([
            'name' => 'Naib Tehsildar',
            'username' => 'naib_tehsildar',
            'email' => 'naib@example.com',
            'password' => bcrypt('password'),
        ]);
        $officerD->assignRole('Revenue Department');

        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Muhammad Ali',
            'subject' => 'Land Issue',
            'description' => 'Test description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerB->id,
            'custody_stack' => [$this->officerA->id, $this->officerB->id],
            'status' => RevenueComplaintStatus::IN_PROCESS,
        ]);

        // Officer D has Revenue Department role, but was neither creator, holder, nor participant
        $responseD = $this->actingAs($officerD)->get(route('revenue-matters.complaints.show', $complaint));
        $responseD->assertStatus(403);

        // Creator A can view
        $responseA = $this->actingAs($this->officerA)->get(route('revenue-matters.complaints.show', $complaint));
        $responseA->assertStatus(200);

        // Current Holder B can view
        $responseB = $this->actingAs($this->officerB)->get(route('revenue-matters.complaints.show', $complaint));
        $responseB->assertStatus(200);

        // Super Admin can view
        $responseSuper = $this->actingAs($this->superAdmin)->get(route('revenue-matters.complaints.show', $complaint));
        $responseSuper->assertStatus(200);
    }

    /** @test */
    public function dashboard_and_index_tabs_render_correctly_for_authorized_users()
    {
        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Muhammad Ali',
            'subject' => 'Land Issue',
            'description' => 'Test description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'custody_stack' => [$this->officerA->id],
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        // Dashboard
        $dashResponse = $this->actingAs($this->officerA)->get(route('revenue-matters.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee($complaint->complaint_number);

        // Index default (Assigned)
        $assignedResponse = $this->actingAs($this->officerA)->get(route('revenue-matters.complaints.index', ['tab' => 'assigned']));
        $assignedResponse->assertStatus(200);
        $assignedResponse->assertSee($complaint->complaint_number);

        // Forward to Officer B
        $this->actingAs($this->officerA)->post(route('revenue-matters.complaints.forward', $complaint), [
            'recipient_id' => $this->officerB->id,
            'instructions' => 'Take action.',
        ]);

        // Forwarded tab for Officer A
        $forwardedResponse = $this->actingAs($this->officerA)->get(route('revenue-matters.complaints.index', ['tab' => 'forwarded']));
        $forwardedResponse->assertStatus(200);
        $forwardedResponse->assertSee($complaint->complaint_number);

        // Assigned tab for Officer B
        $bResponse = $this->actingAs($this->officerB)->get(route('revenue-matters.complaints.index', ['tab' => 'assigned']));
        $bResponse->assertStatus(200);
        $bResponse->assertSee($complaint->complaint_number);
    }

    /** @test */
    public function historical_forwarded_complaints_remain_visible_in_forwarded_tab_even_when_re_forwarded()
    {
        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Kashif Ali',
            'subject' => 'Boundary Wall Dispute',
            'description' => 'Test description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'custody_stack' => [$this->officerA->id],
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        // Officer A forwards to Officer B
        $this->actingAs($this->officerA)->post(route('revenue-matters.complaints.forward', $complaint), [
            'recipient_id' => $this->officerB->id,
            'instructions' => 'Please investigate.',
        ]);

        // Officer B forwards further to Officer C
        $this->actingAs($this->officerB)->post(route('revenue-matters.complaints.forward', $complaint), [
            'recipient_id' => $this->officerC->id,
            'instructions' => 'Conduct field inquiry.',
        ]);

        // Officer A should STILL see it in their Forwarded tab (historical record)
        $respA = $this->actingAs($this->officerA)->get(route('revenue-matters.complaints.index', ['tab' => 'forwarded']));
        $respA->assertStatus(200);
        $respA->assertSee($complaint->complaint_number);

        // Officer B should also see it in their Forwarded tab
        $respB = $this->actingAs($this->officerB)->get(route('revenue-matters.complaints.index', ['tab' => 'forwarded']));
        $respB->assertStatus(200);
        $respB->assertSee($complaint->complaint_number);
    }

    /** @test */
    public function all_complaints_tab_authorization_is_enforced()
    {
        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Tariq Aziz',
            'subject' => 'Khasra Correction',
            'description' => 'Correction request',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'custody_stack' => [$this->officerA->id],
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        // Officer A without 'Revenue Matters View All' cannot access tab=all
        $respForbidden = $this->actingAs($this->officerA)->get(route('revenue-matters.complaints.index', ['tab' => 'all']));
        $respForbidden->assertStatus(403);

        // Super Admin can access tab=all
        $respSuper = $this->actingAs($this->superAdmin)->get(route('revenue-matters.complaints.index', ['tab' => 'all']));
        $respSuper->assertStatus(200);
        $respSuper->assertSee($complaint->complaint_number);

        // Officer with 'Revenue Matters View All' permission can access tab=all
        $viewAllPerm = Permission::firstOrCreate(['name' => 'Revenue Matters View All', 'guard_name' => 'web']);
        $this->officerA->givePermissionTo($viewAllPerm);

        $respAllowed = $this->actingAs($this->officerA)->get(route('revenue-matters.complaints.index', ['tab' => 'all']));
        $respAllowed->assertStatus(200);
        $respAllowed->assertSee($complaint->complaint_number);
    }

    /** @test */
    public function dedicated_search_endpoint_searches_complaints_by_criteria()
    {
        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Zahid Mahmood',
            'father_husband_name' => 'Mahmood Akhtar',
            'cnic' => '35201-9999999-1',
            'contact_number' => '03121234567',
            'tehsil' => 'Pakpattan',
            'address' => 'Mauza Pir Ghani',
            'subject' => 'Shajra Aks Request',
            'description' => 'Requesting verified copy of Shajra Aks',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'custody_stack' => [$this->officerA->id],
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        // Visit search page
        $searchPageResp = $this->actingAs($this->officerA)->get(route('revenue-matters.complaints.search'));
        $searchPageResp->assertStatus(200);
        $searchPageResp->assertSee('Land Revenue Matters');
        $searchPageResp->assertSee('Search Land Revenue Complaints');

        // Search by CNIC
        $searchQueryResp = $this->actingAs($this->officerA)->get(route('revenue-matters.complaints.search', [
            'cnic' => '35201-9999999-1',
        ]));
        $searchQueryResp->assertStatus(200);
        $searchQueryResp->assertSee($complaint->complaint_number);
        $searchQueryResp->assertSee('Zahid Mahmood');

        // Search with non-matching CNIC
        $searchNoMatch = $this->actingAs($this->officerA)->get(route('revenue-matters.complaints.search', [
            'cnic' => '35201-0000000-0',
        ]));
        $searchNoMatch->assertStatus(200);
        $searchNoMatch->assertSee('No Land Revenue complaints found matching your search criteria.');
        $searchNoMatch->assertDontSee('Zahid Mahmood');
        $searchNoMatch->assertDontSee($complaint->complaint_number);
    }

    /** @test */
    public function revenue_department_admin_role_has_complete_land_revenue_matters_permissions_only()
    {
        $adminRole = Role::where('name', 'Revenue Department Admin')->first();
        $this->assertNotNull($adminRole);

        $expectedPerms = [
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

        foreach ($expectedPerms as $perm) {
            $this->assertTrue($adminRole->hasPermissionTo($perm), "Revenue Department Admin missing {$perm}");
        }

        // Verify total permission count is exactly 10 (no unrelated CMS permissions)
        $this->assertEquals(10, $adminRole->permissions()->count());
    }

    /** @test */
    public function revenue_department_admin_can_access_all_complaints_tab_and_view_any_complaint()
    {
        $revenueAdmin = User::create([
            'name' => 'Revenue Admin User',
            'username' => 'rev_admin',
            'email' => 'revadmin@example.com',
            'password' => bcrypt('password'),
        ]);
        $revenueAdmin->assignRole('Revenue Department Admin');

        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Nawaz Sharif',
            'subject' => 'Land Measurement',
            'description' => 'Measurement inquiry',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerB->id,
            'custody_stack' => [$this->officerA->id, $this->officerB->id],
            'status' => RevenueComplaintStatus::IN_PROCESS,
        ]);

        // Revenue Admin can access 'tab=all'
        $response = $this->actingAs($revenueAdmin)->get(route('revenue-matters.complaints.index', ['tab' => 'all']));
        $response->assertStatus(200);
        $response->assertSee($complaint->complaint_number);

        // Revenue Admin can view the complaint detail
        $showResponse = $this->actingAs($revenueAdmin)->get(route('revenue-matters.complaints.show', $complaint));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($complaint->complaint_number);

        // Sidebar shows "All Complaints" for Revenue Admin
        $dashResponse = $this->actingAs($revenueAdmin)->get(route('revenue-matters.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('All Complaints');
    }

    /** @test */
    public function revenue_department_admin_has_no_unrelated_cms_administrative_powers()
    {
        $revenueAdmin = User::create([
            'name' => 'Revenue Admin User',
            'username' => 'rev_admin_unrelated',
            'email' => 'revadmin_unrelated@example.com',
            'password' => bcrypt('password'),
        ]);
        $revenueAdmin->assignRole('Revenue Department Admin');

        // Cannot access Roles management
        $rolesResponse = $this->actingAs($revenueAdmin)->get(route('roles.index'));
        $rolesResponse->assertStatus(403);

        // Cannot access Settings
        $settingsResponse = $this->actingAs($revenueAdmin)->get(route('settings.index'));
        $settingsResponse->assertStatus(403);

        // Cannot access Users
        $usersResponse = $this->actingAs($revenueAdmin)->get(route('users.index'));
        $usersResponse->assertStatus(403);
    }

    /** @test */
    public function ordinary_revenue_department_user_cannot_access_all_complaints_tab()
    {
        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Aslam Khan',
            'subject' => 'Field Boundary',
            'description' => 'Dispute description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'custody_stack' => [$this->officerA->id],
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        // Ordinary Officer A gets 403 when attempting tab=all
        $response = $this->actingAs($this->officerA)->get(route('revenue-matters.complaints.index', ['tab' => 'all']));
        $response->assertStatus(403);

        // Sidebar does NOT show "All Complaints" link for ordinary officer
        $dashResponse = $this->actingAs($this->officerA)->get(route('revenue-matters.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertDontSee(route('revenue-matters.complaints.index', ['tab' => 'all']));
    }

    /** @test */
    public function viewing_complaint_by_admin_does_not_alter_custody()
    {
        $revenueAdmin = User::create([
            'name' => 'Revenue Admin Observer',
            'username' => 'rev_observer',
            'email' => 'rev_observer@example.com',
            'password' => bcrypt('password'),
        ]);
        $revenueAdmin->assignRole('Revenue Department Admin');

        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Shahid Khan',
            'subject' => 'Custody Verification',
            'description' => 'Test',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerB->id,
            'custody_stack' => [$this->officerA->id, $this->officerB->id],
            'status' => RevenueComplaintStatus::IN_PROCESS,
        ]);

        // Revenue Admin views complaint
        $response = $this->actingAs($revenueAdmin)->get(route('revenue-matters.complaints.show', $complaint));
        $response->assertStatus(200);

        $complaint->refresh();

        // Custody must strictly remain with Officer B and stack untouched
        $this->assertEquals($this->officerB->id, $complaint->current_holder_id);
        $this->assertEquals([$this->officerA->id, $this->officerB->id], $complaint->custody_stack);
    }

    /** @test */
    public function multiple_roles_user_retains_permissions_of_both_roles()
    {
        $deptRole = Role::firstOrCreate(['name' => 'Department', 'guard_name' => 'web']);
        $deptPerm = Permission::firstOrCreate(['name' => 'Department Complaint Charts', 'guard_name' => 'web']);
        $deptRole->givePermissionTo($deptPerm);

        $multiUser = User::create([
            'name' => 'Dual Role Officer',
            'username' => 'dual_officer',
            'email' => 'dual@example.com',
            'password' => bcrypt('password'),
        ]);
        $multiUser->assignRole('Department');
        $multiUser->assignRole('Revenue Department Admin');

        // Retains Department permission
        $this->assertTrue($multiUser->can('Department Complaint Charts'));

        // Retains Revenue Department Admin permission
        $this->assertTrue($multiUser->can('Land Revenue Matters View All'));
        $this->assertTrue($multiUser->can('Land Revenue Matters Access'));
    }

    /** @test */
    public function both_revenue_department_and_revenue_department_admin_can_lodge_new_complaint()
    {
        // 1. Revenue Department officer (Officer A) can GET create
        $getA = $this->actingAs($this->officerA)->get(route('revenue-matters.complaints.create'));
        $getA->assertStatus(200);
        $getA->assertSee('Lodge Land Revenue Complaint');

        // Revenue Department officer can POST store
        $postA = $this->actingAs($this->officerA)->post(route('revenue-matters.complaints.store'), [
            'citizen_name' => 'Citizen by Officer',
            'subject' => 'Encroachment Issue',
            'description' => 'Encroachment reported on state land.',
        ]);
        $postA->assertRedirect();
        $this->assertDatabaseHas('revenue_complaints', [
            'citizen_name' => 'Citizen by Officer',
            'creator_id' => $this->officerA->id,
        ]);

        // 2. Revenue Department Admin can GET create
        $revAdmin = User::create([
            'name' => 'Rev Admin Lodger',
            'username' => 'rev_admin_lodger',
            'email' => 'lodger@example.com',
            'password' => bcrypt('password'),
        ]);
        $revAdmin->assignRole('Revenue Department Admin');

        $getAdmin = $this->actingAs($revAdmin)->get(route('revenue-matters.complaints.create'));
        $getAdmin->assertStatus(200);
        $getAdmin->assertSee('Lodge Land Revenue Complaint');

        // Revenue Department Admin can POST store
        $postAdmin = $this->actingAs($revAdmin)->post(route('revenue-matters.complaints.store'), [
            'citizen_name' => 'Citizen by Admin',
            'subject' => 'Boundary Verification',
            'description' => 'Boundary dispute reported.',
        ]);
        $postAdmin->assertRedirect();
        $this->assertDatabaseHas('revenue_complaints', [
            'citizen_name' => 'Citizen by Admin',
            'creator_id' => $revAdmin->id,
        ]);
    }

    /** @test */
    public function adc_revenue_user_with_dual_roles_can_access_and_lodge_complaint()
    {
        // User with Department and Revenue Department Admin (mirroring ADC Revenue)
        $adcRevenue = User::create([
            'name' => 'ADC Revenue Pakpattan',
            'username' => 'adcr_test',
            'email' => 'adcr_test@example.com',
            'password' => bcrypt('password'),
            'designation' => 'ADC Revenue',
        ]);
        $deptRole = Role::firstOrCreate(['name' => 'Department', 'guard_name' => 'web']);
        $adcRevenue->assignRole($deptRole);
        $adcRevenue->assignRole('Revenue Department Admin');

        // Verify sidebar shows "Lodge New Complaint"
        $dashResp = $this->actingAs($adcRevenue)->get(route('revenue-matters.dashboard'));
        $dashResp->assertStatus(200);
        $dashResp->assertSee('Lodge New Complaint');

        // GET create route must return 200 (not 403)
        $createResp = $this->actingAs($adcRevenue)->get(route('revenue-matters.complaints.create'));
        $createResp->assertStatus(200);
        $createResp->assertSee('Lodge Land Revenue Complaint');

        // POST store must successfully create complaint and redirect
        $storeResp = $this->actingAs($adcRevenue)->post(route('revenue-matters.complaints.store'), [
            'citizen_name' => 'Citizen via ADC Revenue',
            'cnic' => '35202-7654321-1',
            'contact_number' => '03017654321',
            'tehsil' => 'Pakpattan',
            'subject' => 'Official Demarcation Directive',
            'description' => 'Complaint lodged under direction of ADC Revenue.',
        ]);
        $storeResp->assertRedirect();
        $this->assertDatabaseHas('revenue_complaints', [
            'citizen_name' => 'Citizen via ADC Revenue',
            'creator_id' => $adcRevenue->id,
        ]);
    }

    /** @test */
    public function users_without_create_permission_cannot_access_create_or_store()
    {
        // Regular user without Revenue permissions
        $getResp = $this->actingAs($this->unauthorizedUser)->get(route('revenue-matters.complaints.create'));
        $getResp->assertStatus(403);

        $postResp = $this->actingAs($this->unauthorizedUser)->post(route('revenue-matters.complaints.store'), [
            'citizen_name' => 'Unauthorized Attempt',
            'subject' => 'Test',
            'description' => 'Test',
        ]);
        $postResp->assertStatus(403);
    }

    /** @test */
    public function all_complaints_tab_displays_required_monitoring_columns_and_complaints_of_all_users()
    {
        $revAdmin = User::create([
            'name' => 'ADCR Pakpattan Admin',
            'username' => 'adcr_admin',
            'email' => 'adcr_admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $revAdmin->assignRole('Revenue Department Admin');

        // Complaint 1: Created by Officer A, held by Officer B in Pakpattan
        $c1 = RevenueComplaint::create([
            'citizen_name' => 'Haji Muhammad Din',
            'tehsil' => 'Pakpattan',
            'subject' => 'Demarcation of Khasra 120',
            'description' => 'Dispute over boundary.',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerB->id,
            'custody_stack' => [$this->officerA->id, $this->officerB->id],
            'status' => RevenueComplaintStatus::IN_PROCESS,
        ]);

        // Complaint 2: Created by Officer B, held by Officer C in Arifwala (closed)
        $c2 = RevenueComplaint::create([
            'citizen_name' => 'Malik Riaz',
            'tehsil' => 'Arifwala',
            'subject' => 'Mutation Verification',
            'description' => 'Verification of registry.',
            'creator_id' => $this->officerB->id,
            'current_holder_id' => $this->officerC->id,
            'custody_stack' => [$this->officerB->id, $this->officerC->id],
            'status' => RevenueComplaintStatus::CLOSED,
            'closed_by' => $this->officerB->id,
            'closed_at' => now(),
        ]);

        // Add a log for last activity test
        RevenueComplaintLog::create([
            'revenue_complaint_id' => $c1->id,
            'user_id' => $this->officerB->id,
            'action' => 'comment_added',
            'comments' => 'Notice issued to both parties.',
        ]);

        $response = $this->actingAs($revAdmin)->get(route('revenue-matters.complaints.index', ['tab' => 'all']));
        $response->assertStatus(200);

        // Required headers
        $response->assertSee('Complaint #');
        $response->assertSee('Citizen Name');
        $response->assertSee('Tehsil');
        $response->assertSee('Subject');
        $response->assertSee('Created By');
        $response->assertSee('Current Holder');
        $response->assertSee('Status');
        $response->assertSee('Created Date');
        $response->assertSee('Last Activity');
        $response->assertSee('Action');

        // Both complaints appear irrespective of creator/holder
        $response->assertSee($c1->complaint_number);
        $response->assertSee('Haji Muhammad Din');
        $response->assertSee('Pakpattan');
        $response->assertSee('Demarcation of Khasra 120');
        $response->assertSee($this->officerA->official_label); // Created By
        $response->assertSee($this->officerB->official_label); // Current Holder

        $response->assertSee($c2->complaint_number);
        $response->assertSee('Malik Riaz');
        $response->assertSee('Arifwala');
        $response->assertSee('Resolved');
    }

    /** @test */
    public function all_complaints_filters_by_status_tehsil_created_by_current_holder_and_search()
    {
        $revAdmin = User::create([
            'name' => 'ADCR Overseer',
            'username' => 'adcr_overseer',
            'email' => 'overseer@example.com',
            'password' => bcrypt('password'),
        ]);
        $revAdmin->assignRole('Revenue Department Admin');

        $cPakpattan = RevenueComplaint::create([
            'citizen_name' => 'Citizen Alpha',
            'cnic' => '35202-1111111-1',
            'contact_number' => '03001111111',
            'tehsil' => 'Pakpattan',
            'address' => 'Mauza Tibba',
            'subject' => 'Irrigation Watercourse Dispute',
            'description' => 'Dispute description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerB->id,
            'custody_stack' => [$this->officerA->id, $this->officerB->id],
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        $cArifwala = RevenueComplaint::create([
            'citizen_name' => 'Citizen Beta',
            'cnic' => '35202-2222222-2',
            'contact_number' => '03002222222',
            'tehsil' => 'Arifwala',
            'address' => 'Mauza Qaboola',
            'subject' => 'Inheritance Mutation Issue',
            'description' => 'Dispute description',
            'creator_id' => $this->officerB->id,
            'current_holder_id' => $this->officerC->id,
            'custody_stack' => [$this->officerB->id, $this->officerC->id],
            'status' => RevenueComplaintStatus::IN_PROCESS,
        ]);

        // Filter by Tehsil = Arifwala
        $respTehsil = $this->actingAs($revAdmin)->get(route('revenue-matters.complaints.index', [
            'tab' => 'all',
            'tehsil' => 'Arifwala',
        ]));
        $respTehsil->assertStatus(200);
        $respTehsil->assertSee($cArifwala->complaint_number);
        $respTehsil->assertDontSee($cPakpattan->complaint_number);

        // Filter by Current Holder = Officer B
        $respHolder = $this->actingAs($revAdmin)->get(route('revenue-matters.complaints.index', [
            'tab' => 'all',
            'current_holder' => $this->officerB->id,
        ]));
        $respHolder->assertStatus(200);
        $respHolder->assertSee($cPakpattan->complaint_number);
        $respHolder->assertDontSee($cArifwala->complaint_number);

        // Filter by Created By = Officer B
        $respCreator = $this->actingAs($revAdmin)->get(route('revenue-matters.complaints.index', [
            'tab' => 'all',
            'created_by' => $this->officerB->id,
        ]));
        $respCreator->assertStatus(200);
        $respCreator->assertSee($cArifwala->complaint_number);
        $respCreator->assertDontSee($cPakpattan->complaint_number);

        // Search by Address / Mauza = "Qaboola"
        $respSearch = $this->actingAs($revAdmin)->get(route('revenue-matters.complaints.index', [
            'tab' => 'all',
            'search' => 'Qaboola',
        ]));
        $respSearch->assertStatus(200);
        $respSearch->assertSee($cArifwala->complaint_number);
        $respSearch->assertDontSee($cPakpattan->complaint_number);

        // Search by CNIC = "35202-1111111-1"
        $respCnic = $this->actingAs($revAdmin)->get(route('revenue-matters.complaints.index', [
            'tab' => 'all',
            'search' => '35202-1111111-1',
        ]));
        $respCnic->assertStatus(200);
        $respCnic->assertSee($cPakpattan->complaint_number);
        $respCnic->assertDontSee($cArifwala->complaint_number);
    }

    /** @test */
    public function sidebar_all_complaints_shows_badge_for_revenue_admin_and_is_hidden_for_ordinary_user()
    {
        $revAdmin = User::create([
            'name' => 'ADCR Badge Test',
            'username' => 'adcr_badge',
            'email' => 'badge@example.com',
            'password' => bcrypt('password'),
        ]);
        $revAdmin->assignRole('Revenue Department Admin');

        RevenueComplaint::create([
            'citizen_name' => 'Citizen Badge',
            'subject' => 'Subject Badge',
            'description' => 'Description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'custody_stack' => [$this->officerA->id],
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        // Revenue Admin dashboard shows "All Complaints" and total count badge
        $adminResp = $this->actingAs($revAdmin)->get(route('revenue-matters.dashboard'));
        $adminResp->assertStatus(200);
        $adminResp->assertSee('All Complaints');
        $adminResp->assertSee('pcoded-badge');

        // Ordinary officer dashboard does NOT show "All Complaints"
        $officerResp = $this->actingAs($this->officerA)->get(route('revenue-matters.dashboard'));
        $officerResp->assertStatus(200);
        $officerResp->assertDontSee(route('revenue-matters.complaints.index', ['tab' => 'all']));
    }

    /** @test */
    public function revenue_department_only_user_login_redirects_to_revenue_dashboard()
    {
        $officer = User::create([
            'name' => 'Officer Login Test',
            'username' => 'officer_login',
            'email' => 'officer_login@example.com',
            'password' => bcrypt('password123'),
        ]);
        $officer->assignRole('Revenue Department');

        $response = $this->post('/login', [
            'username' => 'officer_login',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('revenue-matters.dashboard'));
    }

    /** @test */
    public function revenue_department_only_user_manually_visiting_dashboard_is_redirected_to_revenue_dashboard()
    {
        $response = $this->actingAs($this->officerA)->get(route('dashboard'));

        $response->assertRedirect(route('revenue-matters.dashboard'));
    }

    /** @test */
    public function revenue_department_admin_only_user_login_and_dashboard_redirects_to_revenue_dashboard()
    {
        $revAdmin = User::create([
            'name' => 'ADCR Login Test',
            'username' => 'adcr_login',
            'email' => 'adcr_login@example.com',
            'password' => bcrypt('password123'),
        ]);
        $revAdmin->assignRole('Revenue Department Admin');

        // Login redirect
        $loginResp = $this->post('/login', [
            'username' => 'adcr_login',
            'password' => 'password123',
        ]);
        $loginResp->assertRedirect(route('revenue-matters.dashboard'));

        // Direct /dashboard visit
        $dashResp = $this->actingAs($revAdmin)->get(route('dashboard'));
        $dashResp->assertRedirect(route('revenue-matters.dashboard'));
    }

    /** @test */
    public function super_admin_login_and_dashboard_access_remain_unchanged()
    {
        $loginResp = $this->post('/login', [
            'username' => $this->superAdmin->username,
            'password' => 'password',
        ]);
        $loginResp->assertRedirect(route('dashboard', absolute: false));

        // Super Admin has full general dashboard access and is never treated as revenue-only
        $this->assertTrue($this->superAdmin->hasGeneralDashboardAccess());
        $this->assertFalse($this->superAdmin->isRevenueOnly());
        $this->assertEquals(route('dashboard', absolute: false), $this->superAdmin->defaultDashboardRoute());

        // Sidebar for Super Admin shows general dashboard and land revenue matters
        $sideResp = $this->actingAs($this->superAdmin)->get(route('revenue-matters.dashboard'));
        $sideResp->assertStatus(200);
        $sideResp->assertSee(route('dashboard'));
        $sideResp->assertSee('Land Revenue Matters');
    }

    /** @test */
    public function multi_role_user_with_department_and_revenue_preserves_general_dashboard_access()
    {
        $dashPerm = Permission::firstOrCreate(['name' => 'Dashboard', 'guard_name' => 'web']);
        $deptRole = Role::firstOrCreate(['name' => 'Department', 'guard_name' => 'web']);
        $deptRole->givePermissionTo($dashPerm);

        $deptUser = User::create([
            'name' => 'Multi Role User',
            'username' => 'multi_role_user',
            'email' => 'multi_role@example.com',
            'password' => bcrypt('password123'),
        ]);
        $deptUser->assignRole(['Department', 'Revenue Department']);

        // Multi-role user has Dashboard permission from Department role, so default landing is general dashboard
        $loginResp = $this->post('/login', [
            'username' => 'multi_role_user',
            'password' => 'password123',
        ]);
        $loginResp->assertRedirect(route('dashboard', absolute: false));

        // Permissions and access methods check
        $this->assertTrue($deptUser->hasGeneralDashboardAccess());
        $this->assertTrue($deptUser->hasRevenueMattersAccess());
        $this->assertFalse($deptUser->isRevenueOnly());
        $this->assertEquals(route('dashboard', absolute: false), $deptUser->defaultDashboardRoute());

        // Sidebar shows both general CMS dashboard link and Land Revenue Matters submenu
        $sideResp = $this->actingAs($deptUser)->get(route('revenue-matters.dashboard'));
        $sideResp->assertStatus(200);
        $sideResp->assertSee(route('dashboard'));
        $sideResp->assertSee('Land Revenue Matters');
    }

    /** @test */
    public function ordinary_revenue_user_sidebar_hides_unrelated_cms_modules()
    {
        $resp = $this->actingAs($this->officerA)->get(route('revenue-matters.dashboard'));
        $resp->assertStatus(200);

        // Shows Land Revenue Matters items
        $resp->assertSee('Land Revenue Matters');
        $resp->assertSee('Lodge New Complaint');
        $resp->assertSee('Assigned');
        $resp->assertSee('Forwarded');
        $resp->assertSee('Search');

        // Hides unrelated CMS modules
        $resp->assertDontSee(route('complaints.index'));
        $resp->assertDontSee(route('users.index'));
        $resp->assertDontSee(route('officer-contacts.index'));
        $resp->assertDontSee(route('reports.summary'));
        $resp->assertDontSee(route('settings.index'));
        // Hides All Complaints because ordinary revenue officer lacks View All permission
        $resp->assertDontSee(route('revenue-matters.complaints.index', ['tab' => 'all']));
    }

    /** @test */
    public function user_without_dashboard_or_revenue_permission_is_forbidden_from_general_dashboard()
    {
        $restrictedUser = User::create([
            'name' => 'Restricted User',
            'username' => 'restricted_user',
            'email' => 'restricted@example.com',
            'password' => bcrypt('password123'),
        ]);

        $resp = $this->actingAs($restrictedUser)->get(route('dashboard'));
        $resp->assertStatus(403);
    }
}
