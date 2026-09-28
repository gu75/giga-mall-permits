<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_can_register_without_cnic(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Shop Owner',
            'email' => 'shop@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'shop_name' => 'Test Shop',
            'floor_location' => 'Ground Floor',
            'cell_no' => '03001234567',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'shop@example.com',
            'role' => 'tenant',
            'shop_name' => 'Test Shop',
        ]);
    }

    public function test_operations_can_add_tenant_without_cnic(): void
    {
        $operations = User::factory()->create(['role' => 'operations']);

        $response = $this->actingAs($operations)->post(route('tenants.store'), [
            'name' => 'New Tenant',
            'email' => 'newtenant@example.com',
            'password' => 'secret123',
            'shop_name' => 'New Shop',
            'floor_location' => 'First Floor',
            'cell_no' => '03111234567',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('tenants.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'newtenant@example.com',
            'role' => 'tenant',
        ]);
    }
}
