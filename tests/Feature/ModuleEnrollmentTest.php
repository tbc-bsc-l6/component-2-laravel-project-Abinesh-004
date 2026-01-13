<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Module;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles
        UserRole::create(['role' => 'student']);
        UserRole::create(['role' => 'old_student']);
        UserRole::create(['role' => 'teacher']);
        UserRole::create(['role' => 'admin']);
    }

    private function createStudent()
    {
        $role = UserRole::where('role', 'student')->first();
        return User::factory()->create(['user_role_id' => $role->id]);
    }

    private function createOldStudent()
    {
        $role = UserRole::where('role', 'old_student')->first();
        return User::factory()->create(['user_role_id' => $role->id]);
    }

    public function test_student_can_enroll_in_available_module()
    {
        $student = $this->createStudent();
        $module = Module::factory()->create(['is_available' => true]);

        $response = $this->actingAs($student)
            ->post(route('student.enroll.store'), [
                'module_id' => $module->id
            ]);

        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $student->id,
            'module_id' => $module->id,
            'status' => 'enrolled'
        ]);
    }

    public function test_student_cannot_enroll_twice_in_same_module()
    {
        $student = $this->createStudent();
        $module = Module::factory()->create();

        // Enroll first time
        Enrollment::create([
            'user_id' => $student->id,
            'module_id' => $module->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        // Try to enroll again
        $response = $this->actingAs($student)
            ->from(route('student.enroll.index'))
            ->post(route('student.enroll.store'), [
                'module_id' => $module->id
            ]);

        $response->assertRedirect(route('student.enroll.index'));
        $response->assertSessionHas('error', 'You are already enrolled in this module!');

        $this->assertEquals(1, Enrollment::count());
    }

    public function test_student_cannot_enroll_in_more_than_4_modules()
    {
        $student = $this->createStudent();
        $modules = Module::factory()->count(5)->create();

        // Enroll in 4 modules manually
        foreach ($modules->take(4) as $module) {
            Enrollment::create([
                'user_id' => $student->id,
                'module_id' => $module->id,
                'status' => 'enrolled',
                'enrolled_at' => now(),
            ]);
        }

        // Try to enroll in 5th
        $response = $this->actingAs($student)
            ->from(route('student.enroll.index'))
            ->post(route('student.enroll.store'), [
                'module_id' => $modules->last()->id
            ]);

        $response->assertRedirect(route('student.enroll.index'));
        $response->assertSessionHas('error', 'You have reached the maximum of 4 active modules!');
    }

    public function test_student_cannot_enroll_if_module_is_full()
    {
        $student = $this->createStudent();
        $module = Module::factory()->create();
        $role = UserRole::where('role', 'student')->first();

        // Fill with 10 random students
        $existingStudents = User::factory()->count(10)->create(['user_role_id' => $role->id]);
        foreach ($existingStudents as $existingStudent) {
            Enrollment::create([
                'user_id' => $existingStudent->id,
                'module_id' => $module->id,
                'status' => 'enrolled',
                'enrolled_at' => now(),
            ]);
        }

        // Try to enroll
        $response = $this->actingAs($student)
            ->from(route('student.enroll.index'))
            ->post(route('student.enroll.store'), [
                'module_id' => $module->id
            ]);

        $response->assertRedirect(route('student.enroll.index'));
        $response->assertSessionHas('error', 'This module is full. Please try again later.');
    }

    public function test_old_student_cannot_enroll()
    {
        $oldStudent = $this->createOldStudent();
        $module = Module::factory()->create();

        $response = $this->actingAs($oldStudent)
            ->from(route('student.enroll.index'))
            ->post(route('student.enroll.store'), [
                'module_id' => $module->id
            ]);

        // Based on controller, it redirects back with error
        $response->assertRedirect(route('student.enroll.index'));
        $response->assertSessionHas('error', 'Old students cannot enroll in new modules.');
        
        $this->assertDatabaseMissing('enrollments', [
            'user_id' => $oldStudent->id,
            'module_id' => $module->id
        ]);
    }
}
