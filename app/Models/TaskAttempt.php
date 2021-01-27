<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAttempt extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function task(){
        return $this->belongsTo(\App\Models\Task::class);
    }

    public function user(){
        return $this->belongsTo(\App\Models\User::class);
    }   
}
