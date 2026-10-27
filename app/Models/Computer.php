<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Computer extends Model
{
    //
    
    public function apprentices()
    {
        return $this->hasMany('App\\Models\\Apprentice');
    }
    protected $fillable = [
        'number',
        'brand',
    ];
}
