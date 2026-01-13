<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaseInsensitiveLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        UserRole::create(['role' => 'student']);
        UserRole::create(['role' => 'teacher']);
        UserRole::create(['role' => 'admin']);
        UserRole::create(['role' => 'old_student']);
    }

    public function test_users_can_authenticate_using_mixed_case_email()
    {
        $role = UserRole::where('role', 'student')->first();
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'user_role_id' => $role->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'TEST@Example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home', absolute: false));
    }
}
