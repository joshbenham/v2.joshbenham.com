<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('can be created using factory', function () {
    $user = User::factory()->create();

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->id)->toBeInt()
        ->and($user->name)->toBeString()
        ->and($user->email)->toBeString();
});

it('has fillable attributes', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
    ]);

    expect($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com')
        ->and($user->password)->not->toBe('secret123'); // Should be hashed
});

it('hides password and remember_token in array', function () {
    $user = User::factory()->create();

    $array = $user->toArray();

    expect($array)->not->toHaveKey('password')
        ->and($array)->not->toHaveKey('remember_token');
});

it('casts email_verified_at to datetime', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    expect($user->email_verified_at)->toBeInstanceOf(DateTimeInterface::class);
});

it('hashes password automatically', function () {
    $user = User::factory()->create([
        'password' => 'plain-password',
    ]);

    expect($user->password)->not->toBe('plain-password')
        ->and(Hash::check('plain-password', $user->password))->toBeTrue();
});

it('can be created as unverified', function () {
    $user = User::factory()->unverified()->create();

    expect($user->email_verified_at)->toBeNull();
});

it('can be created as verified', function () {
    $user = User::factory()->create();

    expect($user->email_verified_at)->not->toBeNull()
        ->and($user->email_verified_at)->toBeInstanceOf(DateTimeInterface::class);
});

it('has notifiable trait', function () {
    $user = User::factory()->create();

    expect(method_exists($user, 'notify'))->toBeTrue()
        ->and(method_exists($user, 'notifyNow'))->toBeTrue();
});

it('has factory trait', function () {
    expect(User::class)->toHaveMethod('factory');
});

it('stores email as string', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
    ]);

    expect($user->email)->toBeString()
        ->and($user->email)->toBe('test@example.com');
});

it('stores name as string', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
    ]);

    expect($user->name)->toBeString()
        ->and($user->name)->toBe('Test User');
});

it('can update attributes', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
    ]);

    $user->update(['name' => 'New Name']);

    expect($user->fresh()->name)->toBe('New Name');
});

it('can be deleted', function () {
    $user = User::factory()->create();
    $userId = $user->id;

    $user->delete();

    expect(User::find($userId))->toBeNull();
});
