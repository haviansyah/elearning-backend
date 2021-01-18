<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teacher(){
        return $this->belongsTo(\App\Models\User::class,"teacher_user_id");
    }

    public function students(){
        return $this->belongsToMany(\App\Models\User::class);
    }
}
