<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class CourseController extends ApiCrudController
{
    protected function modelClass(): string
    {
        return Course::class;
    }

    protected function rules(bool $updating): array
    {
        return [
            'course_number' => 'required|string|max:255',
            'day' => 'required|string|max:255',
            'area_id' => 'required|exists:areas,id',
            'training_center_id' => 'required|exists:training_centers,id',
            'teachers' => 'sometimes|array',
            'teachers.*' => 'integer|distinct|exists:teachers,id',
        ];
    }

    protected function relations(): array
    {
        return ['area', 'trainingCenter', 'apprentices', 'teachers'];
    }

    protected function imageFolder(): ?string
    {
        return 'courses';
    }

    protected function syncRelations(): array
    {
        return ['teachers' => 'teachers'];
    }

    protected function hasDependencies(Model $model): bool
    {
        return $model->apprentices()->exists() || $model->teachers()->exists();
    }
}
