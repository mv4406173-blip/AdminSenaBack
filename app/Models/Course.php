<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //

    public function trainingCenter()
    {
        return $this->belongsTo('App\\Models\\TrainingCenter');
    }

    protected $fillable = [
        'course_number',
        'day',
        'area_id',
        'training_center_id',
        'imagen_url',
        'imagen_public_id',
    ];

    public function area()
    {
        return $this->belongsTo('App\\Models\\Area');
    }

    public function apprentices()
    {
        return $this->hasMany('App\\Models\\Apprentice');
    }

    public function teachers()
    {
        return $this->belongsToMany('App\\Models\\Teacher', 'course_teacher');
    }
}
