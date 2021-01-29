<?php

namespace App\Http\Controllers;

use App\Http\Resources\Calendar;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use RoleConst;
use Tymon\JWTAuth\Facades\JWTAuth;

class CalendarController extends Controller
{
    public function get_event_by_classroom(){
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $user = User::with(["classroom","taught_classroom","classroom.lessons","taught_classroom.lessons"])->findOrFail($user->id);
            if($user->role_id == RoleConst::TEACHER ){
                $classrooms = $user->taught_classroom;
            }else{
                $classrooms = $user->classroom;
            }
            $lessons = $classrooms->pluck('lessons')->flatten(1);
            return response(Calendar::collection($lessons));
        }catch(Exception $e){
            return $e;
            return response(["status"=>"OK"],200);
        }
    }
}
