<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\TrainingCenter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminApiCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_area_api_supports_the_complete_crud_flow(): void
    {
        $created = $this->postJson('/api/areas', ['name' => 'Software'])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Software')
            ->json('data.id');

        $this->getJson('/api/areas/'.$created)
            ->assertOk()
            ->assertJsonPath('name', 'Software');

        $this->putJson('/api/areas/'.$created, ['name' => 'Programación'])
            ->assertOk()
            ->assertJsonPath('data.name', 'Programación');

        $this->getJson('/api/areas')
            ->assertOk()
            ->assertJsonPath('0.name', 'Programación');

        $this->deleteJson('/api/areas/'.$created)
            ->assertOk()
            ->assertJsonPath('message', 'Registro eliminado correctamente.');
    }

    public function test_course_api_synchronizes_its_instructors_and_protects_related_records(): void
    {
        $area = Area::query()->create(['name' => 'Software']);
        $center = TrainingCenter::query()->create(['name' => 'Centro Norte', 'location' => 'Bogotá']);
        $teacher = Teacher::query()->create([
            'name' => 'Ana Pérez',
            'email' => 'ana@example.test',
            'area_id' => $area->id,
            'trainingcenter_id' => $center->id,
        ]);
        $course = Course::query()->create([
            'course_number' => 'ADSO-01',
            'day' => 'Lunes',
            'area_id' => $area->id,
            'training_center_id' => $center->id,
        ]);

        $this->putJson('/api/courses/'.$course->id, [
            'course_number' => 'ADSO-01',
            'day' => 'Martes',
            'area_id' => $area->id,
            'training_center_id' => $center->id,
            'teachers' => [$teacher->id],
        ])
            ->assertOk()
            ->assertJsonPath('data.teachers.0.id', $teacher->id);

        $this->deleteJson('/api/courses/'.$course->id)
            ->assertStatus(409);
    }

    public function test_image_backed_resources_require_an_image_on_creation(): void
    {
        $this->postJson('/api/training-centers', [
            'name' => 'Centro Norte',
            'location' => 'Bogotá',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['imagen']);
    }

    public function test_training_center_api_loads_teachers_using_the_existing_foreign_key(): void
    {
        $area = Area::query()->create(['name' => 'Software']);
        $center = TrainingCenter::query()->create([
            'name' => 'Centro Norte',
            'location' => 'Bogotá',
        ]);
        Teacher::query()->create([
            'name' => 'Ana Pérez',
            'email' => 'ana@example.test',
            'area_id' => $area->id,
            'trainingcenter_id' => $center->id,
        ]);

        $this->getJson('/api/training-centers')
            ->assertOk()
            ->assertJsonPath('0.teachers.0.name', 'Ana Pérez');
    }
}
