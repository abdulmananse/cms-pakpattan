<?php

namespace Tests\Feature;

use App\Enums\RevenueComplaintStatus;
use App\Models\Role;
use App\Models\RevenueComplaint;
use App\Models\User;
use App\Services\Revenue\OfficerColorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RevenueAllComplaintsReportUiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $officerA;
    protected User $officerB;
    protected User $officerC;

    protected function setUp(): void
    {
        parent::setUp();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'Revenue Department Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Revenue Department', 'guard_name' => 'web']);

        $this->admin = User::create([
            'name' => 'ADCR Pakpattan Admin',
            'username' => 'adcr_admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'designation' => 'ADC Revenue',
        ]);
        $this->admin->assignRole('Revenue Department Admin');

        // Officer A and Officer B have the exact same designation ("Revenue Officer")
        $this->officerA = User::create([
            'name' => 'Tariq Mehmood',
            'username' => 'tariq_ro',
            'email' => 'tariq@example.com',
            'password' => bcrypt('password'),
            'designation' => 'Revenue Officer',
        ]);
        $this->officerA->assignRole('Revenue Department');

        $this->officerB = User::create([
            'name' => 'Ahmad Raza',
            'username' => 'ahmad_ro',
            'email' => 'ahmad@example.com',
            'password' => bcrypt('password'),
            'designation' => 'Revenue Officer',
        ]);
        $this->officerB->assignRole('Revenue Department');

        $this->officerC = User::create([
            'name' => 'Kashif Ali',
            'username' => 'kashif_ro',
            'email' => 'kashif@example.com',
            'password' => bcrypt('password'),
            'designation' => 'Naib Tehsildar',
        ]);
        $this->officerC->assignRole('Revenue Department');
    }

    /** @test */
    public function officer_color_service_is_deterministic_and_distinguishes_officers_with_same_designation()
    {
        // 1. Same officer returns identical theme consistently
        $themeA1 = OfficerColorService::getThemeForUser($this->officerA->id);
        $themeA2 = OfficerColorService::getThemeForUser($this->officerA->id);
        $this->assertEquals($themeA1, $themeA2);
        $this->assertNotEmpty($themeA1['bg']);
        $this->assertNotEmpty($themeA1['border']);

        // 2. Different officers with identical designation get distinguishable colors
        $themeB = OfficerColorService::getThemeForUser($this->officerB->id);
        $this->assertNotEquals($themeA1['bg'], $themeB['bg']);
        $this->assertNotEquals($themeA1['border'], $themeB['border']);

        // 3. Initials generation
        $this->assertEquals('TM', OfficerColorService::getInitials($this->officerA->name));
        $this->assertEquals('AR', OfficerColorService::getInitials($this->officerB->name));
    }

    /** @test */
    public function complaints_held_by_same_officer_have_identical_row_colors_in_all_complaints_report()
    {
        // Two complaints held by Officer A
        $c1 = RevenueComplaint::create([
            'citizen_name' => 'Citizen 1',
            'subject' => 'Dispute 1',
            'description' => 'Description 1',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        $c2 = RevenueComplaint::create([
            'citizen_name' => 'Citizen 2',
            'subject' => 'Dispute 2',
            'description' => 'Description 2',
            'creator_id' => $this->officerB->id,
            'current_holder_id' => $this->officerA->id,
            'status' => RevenueComplaintStatus::IN_PROCESS,
        ]);

        // One complaint held by Officer B
        $c3 = RevenueComplaint::create([
            'citizen_name' => 'Citizen 3',
            'subject' => 'Dispute 3',
            'description' => 'Description 3',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerB->id,
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        $themeA = OfficerColorService::getThemeForUser($this->officerA->id);
        $themeB = OfficerColorService::getThemeForUser($this->officerB->id);

        $response = $this->actingAs($this->admin)->get(route('revenue-matters.complaints.index', ['tab' => 'all']));
        $response->assertStatus(200);

        // Check that theme A is present for both c1 and c2
        $response->assertSee("--row-officer-bg: {$themeA['bg']}", false);
        $response->assertSee("--row-officer-border: {$themeA['border']}", false);

        // Check that theme B is present for c3
        $response->assertSee("--row-officer-bg: {$themeB['bg']}", false);
        $response->assertSee("--row-officer-border: {$themeB['border']}", false);

        // Check Color Legend is rendered with officer details
        $response->assertSee('Officer Color Legend');
        $response->assertSee($this->officerA->official_label);
        $response->assertSee($this->officerB->official_label);
    }

    /** @test */
    public function forwarding_a_complaint_changes_its_row_color_to_new_holder_color()
    {
        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Citizen Transfer',
            'subject' => 'Boundary Verification',
            'description' => 'Transfer dispute description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'custody_stack' => [$this->officerA->id],
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        $themeA = OfficerColorService::getThemeForUser($this->officerA->id);
        $themeB = OfficerColorService::getThemeForUser($this->officerB->id);

        // Before forwarding: row uses Officer A theme
        $respBefore = $this->actingAs($this->admin)->get(route('revenue-matters.complaints.index', ['tab' => 'all']));
        $respBefore->assertSee("--row-officer-bg: {$themeA['bg']}", false);

        // Officer A forwards to Officer B
        $this->actingAs($this->officerA)->post(route('revenue-matters.complaints.forward', $complaint), [
            'recipient_id' => $this->officerB->id,
            'instructions' => 'Please verify field notes.',
        ]);

        $complaint->refresh();
        $this->assertEquals($this->officerB->id, $complaint->current_holder_id);

        // After forwarding: row uses Officer B theme
        $respAfter = $this->actingAs($this->admin)->get(route('revenue-matters.complaints.index', ['tab' => 'all']));
        $respAfter->assertSee("--row-officer-bg: {$themeB['bg']}", false);
    }

    /** @test */
    public function distinct_status_badges_are_displayed_appropriately()
    {
        // 1. Open complaint (Amber)
        $cOpen = RevenueComplaint::create([
            'citizen_name' => 'Citizen Open',
            'subject' => 'Open Complaint',
            'description' => 'Open description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        // 2. In Process complaint (Blue)
        $cInProcess = RevenueComplaint::create([
            'citizen_name' => 'Citizen InProcess',
            'subject' => 'In Process Complaint',
            'description' => 'In process description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'status' => RevenueComplaintStatus::IN_PROCESS,
        ]);

        // 3. Resolved complaint (Green)
        $cClosed = RevenueComplaint::create([
            'citizen_name' => 'Citizen Resolved',
            'subject' => 'Resolved Complaint',
            'description' => 'Closed description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'status' => RevenueComplaintStatus::CLOSED,
            'closed_by' => $this->officerA->id,
            'closed_at' => now(),
        ]);

        // 4. Reopened complaint (Purple)
        $cReopened = RevenueComplaint::create([
            'citizen_name' => 'Citizen Reopened',
            'subject' => 'Reopened Complaint',
            'description' => 'Reopened description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'status' => RevenueComplaintStatus::IN_PROCESS,
            'reopened_by' => $this->admin->id,
            'reopened_at' => now(),
            'reopening_reason' => 'Further enquiry needed.',
        ]);

        // 5. Overdue complaint (> 7 days old, Open) (Red)
        $cOverdue = RevenueComplaint::create([
            'citizen_name' => 'Citizen Overdue',
            'subject' => 'Overdue Complaint',
            'description' => 'Overdue description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'status' => RevenueComplaintStatus::OPEN,
            'created_at' => now()->subDays(10),
        ]);

        $response = $this->actingAs($this->admin)->get(route('revenue-matters.complaints.index', ['tab' => 'all']));
        $response->assertStatus(200);

        // Check distinct badges classes
        $response->assertSee('status-badge-open');
        $response->assertSee('status-badge-in-process');
        $response->assertSee('status-badge-resolved');
        $response->assertSee('status-badge-reopened');
        $response->assertSee('status-badge-overdue');

        $response->assertSee('Open');
        $response->assertSee('In Process');
        $response->assertSee('Resolved');
        $response->assertSee('Reopened');
        $response->assertSee('Overdue');
    }

    /** @test */
    public function colors_and_filters_remain_stable_after_filtering_and_refresh()
    {
        $c1 = RevenueComplaint::create([
            'citizen_name' => 'Citizen Alpha',
            'subject' => 'Dispute Alpha',
            'description' => 'Filter test description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerA->id,
            'tehsil' => 'Pakpattan',
            'status' => RevenueComplaintStatus::OPEN,
        ]);

        $themeA = OfficerColorService::getThemeForUser($this->officerA->id);

        // Filter by current_holder
        $respFiltered = $this->actingAs($this->admin)->get(route('revenue-matters.complaints.index', [
            'tab' => 'all',
            'current_holder' => $this->officerA->id,
        ]));
        $respFiltered->assertStatus(200);
        $respFiltered->assertSee($c1->complaint_number);
        $respFiltered->assertSee("--row-officer-bg: {$themeA['bg']}", false);

        // Refresh / second request
        $respRefresh = $this->actingAs($this->admin)->get(route('revenue-matters.complaints.index', [
            'tab' => 'all',
            'current_holder' => $this->officerA->id,
        ]));
        $respRefresh->assertStatus(200);
        $respRefresh->assertSee("--row-officer-bg: {$themeA['bg']}", false);
    }

    /** @test */
    public function last_activity_text_has_proper_contrast_and_dark_gray_color()
    {
        $complaint = RevenueComplaint::create([
            'citizen_name' => 'Citizen Activity Test',
            'subject' => 'Activity Test',
            'description' => 'Test log description',
            'creator_id' => $this->officerA->id,
            'current_holder_id' => $this->officerB->id,
            'status' => RevenueComplaintStatus::IN_PROCESS,
        ]);

        \App\Models\RevenueComplaintLog::create([
            'revenue_complaint_id' => $complaint->id,
            'user_id' => $this->officerA->id,
            'action' => 'forwarded',
            'from_user_id' => $this->officerA->id,
            'to_user_id' => $this->officerB->id,
            'comments' => 'Forwarded for spot inquiry.',
        ]);

        $response = $this->actingAs($this->admin)->get(route('revenue-matters.complaints.index', ['tab' => 'all']));
        $response->assertStatus(200);

        // Verify last activity CSS classes are present
        $response->assertSee('last-activity-time');
        $response->assertSee('last-activity-desc');

        // Verify dark gray color definition is applied in CSS
        $response->assertSee('color: #475569 !important;', false);
        $response->assertSee('font-weight: 500 !important;', false);

        // Verify the action label text is rendered
        $response->assertSee('Forwarded');
    }

    /** @test */
    public function dashboard_metric_cards_and_typography_classes_rendered_correctly(): void
    {
        $response = $this->actingAs($this->admin)->get(route('revenue-matters.dashboard'));
        $response->assertStatus(200);

        // Verify metric card CSS classes and typography structure
        $response->assertSee('stat-card');
        $response->assertSee('stat-card-title');
        $response->assertSee('stat-card-value');
        $response->assertSee('stat-card-desc');
        $response->assertSee('stat-icon-wrapper');

        // Centralized stylesheet rules
        $response->assertSee('font-size: 46px !important;', false);
        $response->assertSee('font-size: 20px !important;', false);
        $response->assertSee('font-size: 16.5px !important;', false);
    }

    /** @test */
    public function create_complaint_form_typography_classes_rendered_correctly(): void
    {
        // Spatie permission for creating complaint
        \App\Models\Permission::firstOrCreate(['name' => 'Land Revenue Matters Create', 'guard_name' => 'web']);
        $this->admin->givePermissionTo('Land Revenue Matters Create');

        $response = $this->actingAs($this->admin)->get(route('revenue-matters.complaints.create'));
        $response->assertStatus(200);

        // Verify form card and section heading classes
        $response->assertSee('revenue-form-card');
        $response->assertSee('form-section-heading');
        $response->assertSee('revenue-form-btn');
    }

    /** @test */
    public function show_complaint_details_and_audit_trail_typography_classes_rendered_correctly(): void
    {
        $complaint = RevenueComplaint::create([
            'complaint_number' => 'REV-2026-99999',
            'citizen_name' => 'Bashir Ahmad',
            'cnic' => '35202-9999999-9',
            'subject' => 'Boundary Demarcation Inquiry',
            'description' => 'Detailed boundary demarcation dispute description.',
            'status' => RevenueComplaintStatus::OPEN,
            'creator_id' => $this->admin->id,
            'current_holder_id' => $this->admin->id,
        ]);

        $complaint->logs()->create([
            'action' => 'created',
            'user_id' => $this->admin->id,
            'comments' => 'Initial complaint registered.',
        ]);

        $response = $this->actingAs($this->admin)->get(route('revenue-matters.complaints.show', $complaint));
        $response->assertStatus(200);

        // Verify show page typography elements
        $response->assertSee('complaint-main-heading');
        $response->assertSee('details-table');
        $response->assertSee('audit-officer-name');
        $response->assertSee('audit-comment-box');
        $response->assertSee('workflow-btn');
    }
}
