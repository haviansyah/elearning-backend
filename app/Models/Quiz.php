<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function questions(){
        return $this->hasMany(\App\Models\Question::class);
    }

    public function attempts(){
        return $this->hasMany(\App\Models\QuizAttempt::class);
    }
}
