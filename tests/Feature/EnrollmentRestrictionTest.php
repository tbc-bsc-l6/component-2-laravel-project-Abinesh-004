<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Module;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentRestrictionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        UserRole::create(['role' => 'student']);
        UserRole::create(['role' => 'old_student']);
        UserRole::create(['role' => 'teacher']);
        UserRole::create(['role' => 'admin']);
    }

    public function test_student_cannot_see_completed_modules_in_enrollment_list()
    {
        $studentRole = UserRole::where('role', 'student')->first();
        $student = User::factory()->create(['user_role_id' => $studentRole->id]);

        $module = Module::factory()->create(['is_available' => true]);

        // Student has passed the module
        Enrollment::create([
            'user_id' => $student->id,
            'module_id' => $module->id,
            'status' => 'pass',
            'enrolled_at' => now()->subMonths(3),
            'completed_at' => now()->subMonth(),
        ]);

        $response = $this->actingAs($student)->get(route('student.enroll.index'));
        
        $response->assertStatus(200);
        $response->assertDontSee($module->module);
    }

    public function test_student_cannot_enroll_in_completed_module_via_post()
    {
        $studentRole = UserRole::where('role', 'student')->first();
        $student = User::factory()->create(['user_role_id' => $studentRole->id]);

        $module = Module::factory()->create(['is_available' => true]);

        // Student has passed the module
        Enrollment::create([
            'user_id' => $student->id,
            'module_id' => $module->id,
            'status' => 'pass',
            'enrolled_at' => now()->subMonths(3),
            'completed_at' => now()->subMonth(),
        ]);

        $response = $this->actingAs($student)
            ->from(route('student.enroll.index'))
            ->post(route('student.enroll.store'), [
                'module_id' => $module->id,
            ]);

        $response->assertRedirect(route('student.enroll.index'));
        $response->assertSessionHas('error', 'You are already enrolled in this module!');
    }

    public function test_student_can_see_failed_modules_in_enrollment_list()
    {
        $studentRole = UserRole::where('role', 'student')->first();
        $student = User::factory()->create(['user_role_id' => $studentRole->id]);

        $module = Module::factory()->create(['is_available' => true]);

        // Student has passed the module
        Enrollment::create([
            'user_id' => $student->id,
            'module_id' => $module->id,
            'status' => 'fail',
            'enrolled_at' => now()->subMonths(3),
            'completed_at' => now()->subMonth(),
        ]);

        $response = $this->actingAs($student)->get(route('student.enroll.index'));
        
        $response->assertStatus(200);
        $response->assertSee($module->module);
    }
}
