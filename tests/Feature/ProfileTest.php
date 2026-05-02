<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk()
        ->assertSee('Profile Information')
        ->assertSee('Update Password')
        ->assertSee('Delete Account');
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->withSession(['_token' => 'test-token'])
        ->patch('/profile', [
            '_token' => 'test-token',
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->withSession(['_token' => 'test-token'])
        ->patch('/profile', [
            '_token' => 'test-token',
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->withSession(['_token' => 'test-token'])
        ->delete('/profile', [
            '_token' => 'test-token',
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->withSession(['_token' => 'test-token'])
        ->from('/profile')
        ->delete('/profile', [
            '_token' => 'test-token',
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});

test('authenticated user sees nav badge linking to profile edit page', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get(route('vehicles.index'));

    $response->assertSuccessful()
        ->assertSee('href="'.route('profile.edit').'"', false);
});
