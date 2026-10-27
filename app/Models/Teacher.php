<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    //

    protected $fillable = [
        'name',
        'email',
        'area_id',
        'trainingcenter_id',
        'imagen_url',
        'imagen_public_id',
    ];

    public function area()
    {
        return $this->belongsTo('App\\Models\\Area');
    }

    public function courses()
    {
        return $this->belongsToMany('App\\Models\\Course', 'course_teacher');
    }

    public function trainingCenter()
    {
        return $this->belongsTo(TrainingCenter::class, 'trainingcenter_id');
    }
}
