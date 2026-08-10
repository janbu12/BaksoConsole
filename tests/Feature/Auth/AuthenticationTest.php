<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows login and authenticates a member', function () {
    $user = User::factory()->create(['password' => 'password', 'role' => UserRole::User]);

    $this->get('/login')->assertOk()->assertSee('Masuk');
    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
});

it('rejects invalid credentials and logs out safely', function () {
    $user = User::factory()->create(['password' => 'password']);

    $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])
        ->assertSessionHasErrors('email');
    $this->actingAs($user)->post('/logout')->assertRedirect('/');
    $this->assertGuest();
});
