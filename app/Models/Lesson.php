<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function classroom(){
        return $this->belongsTo(\App\Models\Classroom::class);
    }

    public function quiz(){
        return $this->belongsTo(\App\Models\Quiz::class);
    }

    public function task(){
        return $this->belongsTo(\App\Models\Task::class);
    }
}
