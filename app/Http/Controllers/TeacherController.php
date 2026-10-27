<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;

class TeacherController extends ApiCrudController
{
    protected function modelClass(): string
    {
        return Teacher::class;
    }

    protected function rules(bool $updating): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'area_id' => 'required|exists:areas,id',
            'trainingcenter_id' => 'required|exists:training_centers,id',
        ];
    }

    protected function relations(): array
    {
        return ['area', 'trainingCenter', 'courses'];
    }

    protected function imageFolder(): ?string
    {
        return 'teachers';
    }

    protected function hasDependencies(Model $model): bool
    {
        return $model->courses()->exists();
    }
}
