<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function role(){
        return $this->belongsTo(\App\Models\Role::class);
    }

    public function taught_classroom(){
        return $this->hasMany(\App\Models\Classroom::class,"teacher_user_id");
    }

    public function classroom(){
        return $this->belongsToMany(\App\Models\Classroom::class);
    }

    public function created_quiz(){
        return $this->hasMany(\App\Models\Quiz::class,"created_by_user_id");
    }

    public function created_task(){
        return $this->hasMany(\App\Models\Task::class,"created_by_user_id");
    }

    public function quiz_attempt(){
        return $this->hasMany(\App\Models\QuizAttempt::class);
    }
}
