<?php

use App\Http\Controllers\ApprenticeController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TrainingCenterController;
use Illuminate\Support\Facades\Route;

Route::apiResource('training-centers', TrainingCenterController::class);
Route::apiResource('areas', AreaController::class);
Route::apiResource('computers', ComputerController::class);
Route::apiResource('courses', CourseController::class);
Route::apiResource('apprentices', ApprenticeController::class);
Route::apiResource('teachers', TeacherController::class);
