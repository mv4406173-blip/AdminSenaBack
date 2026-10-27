<?php

namespace App\Http\Controllers;

use App\Models\Apprentice;
use Illuminate\Database\Eloquent\Model;

class ApprenticeController extends ApiCrudController
{
    protected function modelClass(): string
    {
        return Apprentice::class;
    }

    protected function rules(bool $updating): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'course_id' => 'required|exists:courses,id',
            'computer_id' => 'required|exists:computers,id',
        ];
    }

    protected function relations(): array
    {
        return ['course', 'computer'];
    }

    protected function hasDependencies(Model $model): bool
    {
        return false;
    }
}
