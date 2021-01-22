<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function quiz(){
        return $this->belongsTo(\App\Models\Quiz::class);
    }

    public function user(){
        return $this->belongsTo(\App\Models\User::class);
    }

    public function answers(){
        return $this->hasMany(\App\Models\AttemptAnswer::class);
    }
}
