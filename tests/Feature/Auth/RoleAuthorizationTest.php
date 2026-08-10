<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('protects dashboards by authentication and role', function () {
    $member = User::factory()->create(['role' => UserRole::User]);
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    $this->get('/dashboard')->assertRedirect('/login');
    $this->actingAs($member)->get('/admin')->assertForbidden();
    $this->actingAs($admin)->get('/admin')->assertOk();
});
