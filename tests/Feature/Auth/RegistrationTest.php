<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registers only a normal user and creates one profile', function () {
    $this->get('/register')->assertOk();
    $this->post('/register', [
        'name' => 'Budi Player',
        'email' => 'budi@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'admin',
    ])->assertRedirect('/dashboard');

    $user = User::whereEmail('budi@example.com')->firstOrFail();
    expect($user->role)->toBe(UserRole::User)->and($user->profile)->not->toBeNull();
    $this->assertAuthenticatedAs($user);
});
