<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Database\Eloquent\Model;

class AreaController extends ApiCrudController
{
    protected function modelClass(): string
    {
        return Area::class;
    }

    protected function rules(bool $updating): array
    {
        return ['name' => 'required|string|max:255'];
    }

    protected function relations(): array
    {
        return ['courses', 'teachers'];
    }

    protected function hasDependencies(Model $model): bool
    {
        return $model->courses()->exists() || $model->teachers()->exists();
    }
}
