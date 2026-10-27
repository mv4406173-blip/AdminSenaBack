<?php

namespace App\Http\Controllers;

use App\Models\Computer;
use Illuminate\Database\Eloquent\Model;

class ComputerController extends ApiCrudController
{
    protected function modelClass(): string
    {
        return Computer::class;
    }

    protected function rules(bool $updating): array
    {
        return [
            'number' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
        ];
    }

    protected function relations(): array
    {
        return ['apprentices'];
    }

    protected function hasDependencies(Model $model): bool
    {
        return $model->apprentices()->exists();
    }
}
