<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Module;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles
        UserRole::create(['role' => 'student']);
        UserRole::create(['role' => 'teacher']);
        UserRole::create(['role' => 'admin']);
        UserRole::create(['role' => 'old_student']);
    }

    public function test_student_dashboard_displays_enrolled_modules_with_teacher_name()
    {
        // Create student
        $studentRole = UserRole::where('role', 'student')->first();
        $student = User::factory()->create([
            'user_role_id' => $studentRole->id,
            'name' => 'Test Student'
        ]);

        // Create teacher
        $teacherRole = UserRole::where('role', 'teacher')->first();
        $teacher = User::factory()->create([
            'user_role_id' => $teacherRole->id,
            'name' => 'Prof. Dumbledore'
        ]);

        // Create module
        $module = Module::factory()->create([
            'module' => 'Defense Against the Dark Arts'
        ]);

        // Assign teacher to module
        $module->teachers()->attach($teacher->id);

        // Enroll student in module
        Enrollment::create([
            'user_id' => $student->id,
            'module_id' => $module->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        // Act
        $response = $this->actingAs($student)->get(route('student.dashboard'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Defense Against the Dark Arts');
        $response->assertSee('Prof. Dumbledore');
        $response->assertSee('Teacher:');
    }

    public function test_student_dashboard_displays_not_assigned_when_no_teacher()
    {
        // Create student
        $studentRole = UserRole::where('role', 'student')->first();
        $student = User::factory()->create([
            'user_role_id' => $studentRole->id,
            'name' => 'Test Student'
        ]);

        // Create module without teacher
        $module = Module::factory()->create([
            'module' => 'Potions'
        ]);

        // Enroll student in module
        Enrollment::create([
            'user_id' => $student->id,
            'module_id' => $module->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        // Act
        $response = $this->actingAs($student)->get(route('student.dashboard'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Potions');
        $response->assertSee('Not Assigned');
    }
}
