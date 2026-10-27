<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCenter extends Model
{
    //

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'trainingcenter_id');
    }

    protected $fillable = [
        'name',
        'location',
        'imagen_url',
        'imagen_public_id',
    ];

    public function courses()
    {
        return $this->hasMany('App\\Models\\Course');
    }
}
