<?php

namespace App\Http\Controllers;

use App\Http\Resources\Calendar;
use App\Http\Resources\Event;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use RoleConst;
use Tymon\JWTAuth\Facades\JWTAuth;

class DashboardController extends Controller
{
    public function index(){
        try{
            $user = JWTAuth::parseToken()->authenticate();  
            if($user->role_id == RoleConst::TEACHER){
                $classrooms = $user->taught_classroom;
                $classroom = $user->taught_classroom->count();
                $quiz = $user->created_quiz->count();
                $lesson = $user->taught_classroom->pluck("lessons")->flatten(1)->count();
                $task = $user->created_task->count();
               
                
            }else{
                $user = User::with(["classroom","classroom.lessons","classroom.lessons.quiz","classroom.lessons.task"])->findOrFail($user->id);
                $classrooms = $user->classroom;
                $classroom = $user->classroom->count();
                $quiz = $user->classroom->pluck("lessons")->flatten(1)->pluck("quiz")->count();
                $task = $user->classroom->pluck("lessons")->flatten(1)->pluck("task")->count();
                $lesson = $user->classroom->pluck("lessons")->flatten(1)->count();
            }
            $lessons = $classrooms->pluck('lessons')->flatten(1);
            return [
                "counts" => [
                    "classroom"=>$classroom,
                    "quiz"=>$quiz,
                    "lesson"=>$lesson,
                    "task"=>$task
                ],
                "upcoming_events" => Event::collection($lessons)
            ];
        }catch(Exception $e){

        }
    }
}
