<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminStudentRemovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_module_students_page()
    {
        $adminRole = UserRole::firstOrCreate(['role' => 'admin']);
        $admin = User::factory()->create(['user_role_id' => $adminRole->id]);

        $module = Module::create([
            'module' => 'Test Module',
            'is_available' => true
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.modules.show', $module));

        $response->assertStatus(200);
        $response->assertSee('Test Module');
    }

    public function test_admin_can_remove_student_from_module()
    {
        $adminRole = UserRole::firstOrCreate(['role' => 'admin']);
        $admin = User::factory()->create(['user_role_id' => $adminRole->id]);

        $studentRole = UserRole::firstOrCreate(['role' => 'student']);
        $student = User::factory()->create(['user_role_id' => $studentRole->id]);

        $module = Module::create([
            'module' => 'Test Module',
            'is_available' => true
        ]);

        $module->enrollments()->create([
            'user_id' => $student->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.modules.remove-student', $module), [
                'user_id' => $student->id
            ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('enrollments', [
            'module_id' => $module->id,
            'user_id' => $student->id
        ]);
    }
}
