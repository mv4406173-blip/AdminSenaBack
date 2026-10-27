<?php

namespace App\Http\Controllers;

use App\Models\TrainingCenter;
use Illuminate\Database\Eloquent\Model;

class TrainingCenterController extends ApiCrudController
{
    protected function modelClass(): string
    {
        return TrainingCenter::class;
    }

    protected function rules(bool $updating): array
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ];
    }

    protected function relations(): array
    {
        return ['courses', 'teachers'];
    }

    protected function imageFolder(): ?string
    {
        return 'training_centers';
    }

    protected function hasDependencies(Model $model): bool
    {
        return $model->courses()->exists() || $model->teachers()->exists();
    }
}
