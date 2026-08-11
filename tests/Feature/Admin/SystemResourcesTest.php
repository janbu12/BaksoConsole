<?php

use App\Enums\UserRole;
use App\Models\User;
use App\Services\SystemResourceService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows admin to view system resource dashboard and fetch live metrics', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);

    // 1. View Dashboard
    $response = $this->actingAs($admin)->get('/admin/resources');
    $response->assertOk()
        ->assertSee('Resource &amp; Server Health Monitor', false)
        ->assertSee('CPU Usage')
        ->assertSee('System RAM')
        ->assertSee('Storage Disk');

    // 2. Fetch Live JSON Metrics
    $metricsResponse = $this->actingAs($admin)->getJson('/admin/resources/metrics');
    $metricsResponse->assertOk()
        ->assertJsonStructure([
            'timestamp',
            'cpu' => ['usage_percentage', 'cores', 'model', 'status'],
            'memory' => ['system_total_gb', 'system_used_gb', 'system_free_gb', 'system_usage_percentage', 'php_used_mb', 'php_peak_mb', 'php_limit', 'status'],
            'storage' => ['total_gb', 'used_gb', 'free_gb', 'usage_percentage', 'drive_path', 'status'],
            'database' => ['connected', 'driver', 'database_name', 'latency_ms', 'version', 'status'],
            'environment' => ['php_version', 'laravel_version', 'os', 'server_software', 'cache_driver', 'timezone'],
        ]);
});

it('blocks non-admin users from viewing system resources', function () {
    $user = User::factory()->create(['role' => UserRole::User]);

    $this->actingAs($user)->get('/admin/resources')->assertForbidden();
    $this->actingAs($user)->getJson('/admin/resources/metrics')->assertForbidden();
});

it('correctly calculates metrics via SystemResourceService', function () {
    $service = app(SystemResourceService::class);
    $metrics = $service->getAllMetrics();

    expect($metrics)->toBeArray()
        ->and($metrics)->toHaveKeys(['timestamp', 'cpu', 'memory', 'storage', 'database', 'environment'])
        ->and($metrics['cpu']['cores'])->toBeGreaterThanOrEqual(1)
        ->and($metrics['database']['connected'])->toBeTrue();
});
