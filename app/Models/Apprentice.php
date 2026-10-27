<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apprentice extends Model
{
    //

    public function course()
    {
        return $this->belongsTo('App\\Models\\Course');
    }
    protected $fillable = [
        'name',
        'email',
        'phone',
        'course_id',
        'computer_id',
    ];

    public function computer()
    {
        return $this->belongsTo('App\\Models\\Computer');
    }}
