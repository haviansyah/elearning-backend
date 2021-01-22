<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttemptAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function question(){
        return $this->belongsTo(\App\Models\Question::class);
    }

    public function quiz_attempt(){
        return $this->belongsTo(\App\Models\QuizAttempt::class);
    }
}
