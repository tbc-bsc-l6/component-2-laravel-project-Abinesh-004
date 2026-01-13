<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\UserRole;

class CaseInsensitiveRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        UserRole::create(['role' => 'student']);
    }

    public function test_registration_accepts_mixed_case_email()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'Test@Example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticated();
        
        // Ensure email is stored as lowercase
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }
}
