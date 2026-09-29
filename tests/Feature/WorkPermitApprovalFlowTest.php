<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkPermit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkPermitApprovalFlowTest extends TestCase
{
    use RefreshDatabase;

    private function createPendingPermit(User $tenant): WorkPermit
    {
        $permit = WorkPermit::create([
            'tenant_id' => $tenant->id,
            'outlet_name' => 'Test Shop',
            'floor_location' => 'Ground Floor',
            'site_incharge_name' => 'Ali',
            'site_incharge_cell_no' => '03001234567',
            'site_incharge_cnic' => '3520112345671',
            'nature_of_work' => 'Electrical wiring',
            'requested_by' => 'Ali',
            'requested_by_cell_no' => '03001234567',
            'valid_from' => now()->toDateString(),
            'valid_from_time' => '21:00',
            'valid_to' => now()->addDay()->toDateString(),
            'valid_to_time' => '08:00',
            'daytime_work_requested' => false,
            'status' => 'pending_operations',
        ]);

        $permit->workers()->create([
            'worker_name' => 'Worker One',
            'job_description' => 'Electrician',
            'cnic_number' => '3520112345672',
        ]);

        return $permit;
    }

    public function test_full_approval_flow_operations_then_hse_and_security(): void
    {
        $tenant = User::factory()->create(['role' => 'tenant']);
        $operations = User::factory()->create(['role' => 'operations']);
        $hse = User::factory()->create(['role' => 'hse']);
        $security = User::factory()->create(['role' => 'security']);

        $permit = $this->createPendingPermit($tenant);

        // Operations approves first
        $this->actingAs($operations)
            ->post(route('work-permits.approve', $permit), ['remarks' => 'Looks fine'])
            ->assertSessionHas('success');

        $this->assertSame('in_review', $permit->fresh()->status);

        // HSE manager logs in and approves
        $this->actingAs($hse)
            ->post(route('work-permits.approve', $permit), ['remarks' => 'HSE ok'])
            ->assertSessionHas('success');

        $this->assertSame('in_review', $permit->fresh()->status); // still waiting on Security

        // Security manager logs in and approves
        $this->actingAs($security)
            ->post(route('work-permits.approve', $permit), ['remarks' => 'Security ok'])
            ->assertSessionHas('success');

        $this->assertSame('approved', $permit->fresh()->status);
    }

    public function test_hse_cannot_approve_before_operations(): void
    {
        $tenant = User::factory()->create(['role' => 'tenant']);
        $hse = User::factory()->create(['role' => 'hse']);

        $permit = $this->createPendingPermit($tenant);

        $this->actingAs($hse)
            ->post(route('work-permits.approve', $permit))
            ->assertForbidden();

        $this->assertSame('pending_operations', $permit->fresh()->status);
    }

    public function test_department_cannot_approve_the_same_permit_twice(): void
    {
        $tenant = User::factory()->create(['role' => 'tenant']);
        $operations = User::factory()->create(['role' => 'operations']);
        $hse = User::factory()->create(['role' => 'hse']);

        $permit = $this->createPendingPermit($tenant);

        $this->actingAs($operations)->post(route('work-permits.approve', $permit));
        $this->actingAs($hse)->post(route('work-permits.approve', $permit));

        $this->actingAs($hse)
            ->post(route('work-permits.approve', $permit))
            ->assertForbidden();
    }

    public function test_hse_rejection_rejects_the_whole_permit(): void
    {
        $tenant = User::factory()->create(['role' => 'tenant']);
        $operations = User::factory()->create(['role' => 'operations']);
        $hse = User::factory()->create(['role' => 'hse']);

        $permit = $this->createPendingPermit($tenant);

        $this->actingAs($operations)->post(route('work-permits.approve', $permit));

        $this->actingAs($hse)
            ->post(route('work-permits.reject', $permit), ['reason' => 'Missing documents'])
            ->assertSessionHas('success');

        $permit->refresh();
        $this->assertSame('rejected', $permit->status);
        $this->assertSame('Missing documents', $permit->rejection_reason);
    }

    public function test_a_tenant_cannot_approve_another_tenants_permit(): void
    {
        $tenant = User::factory()->create(['role' => 'tenant']);
        $otherTenant = User::factory()->create(['role' => 'tenant']);

        $permit = $this->createPendingPermit($tenant);

        $this->actingAs($otherTenant)
            ->post(route('work-permits.approve', $permit))
            ->assertForbidden();
    }

    public function test_tenant_only_sees_their_own_permits_on_the_list(): void
    {
        $tenantA = User::factory()->create(['role' => 'tenant']);
        $tenantB = User::factory()->create(['role' => 'tenant']);

        $permitA = $this->createPendingPermit($tenantA);
        $this->createPendingPermit($tenantB);

        $response = $this->actingAs($tenantA)->get(route('work-permits.index'));

        $response->assertOk();
        $response->assertSee($permitA->outlet_name);
    }

    public function test_admin_can_fully_approve_a_permit_in_one_action(): void
    {
        $tenant = User::factory()->create(['role' => 'tenant']);
        $admin = User::factory()->create(['role' => 'admin']);

        $permit = $this->createPendingPermit($tenant);

        $this->actingAs($admin)
            ->post(route('work-permits.approve', $permit), ['remarks' => 'Admin override'])
            ->assertSessionHas('success');

        $this->assertSame('approved', $permit->fresh()->status);
    }
}
