<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAttempt;
use Exception;
use Illuminate\Http\Request;
use RoleConst;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskController extends Controller
{
    public function store(Request $request){
        try{
            $creating_user = JWTAuth::parseToken()->authenticate(); 
            $role = $creating_user->role_id;

            if($role == RoleConst::STUDENT){
                return response(["status"=>"unauthorized"],403);
            }

            $name = $request->name;
            $description = $request->description;
            $due_date = $request->due_date;
            $duration = $request->duration;

            $new_task = new Task([
                "name" => $name,
                "description" => $description,
                "due_date" => $due_date,
                "duration" => $duration,
                "created_by_user_id" => $creating_user->id
            ]);

            $new_task->save();
            return response(["status" => "OK"],200);

        }catch(Exception $e){
            // return $e;
            return response(["status" => "invalid_input"],405);
        }
    }

    public function index(){
        try{
            $user = JWTAuth::parseToken()->authenticate(); 
            $role = $user->role_id;

            if($role == RoleConst::TEACHER){
                return $user->created_task;
            }

        }catch(Exception $e){
            // return $e;
            return response(["status" => "invalid_input"],405);
        }
    }

    public function get($id){
        $task = Task::findOrFail($id);
        return $task;
    }

    public function update(Request $request, $id){
        try{
            $task = Task::findOrFail($id);
            $task->update($request->all());
            return response(["status" => "OK"],200);

        }catch(Exception $e){
            return $e;
            return response(["status" => "invalid_input"],405);
        }
    }


    public function new_attempt(Request $request,$id){
        try{
            $user = JWTAuth::parseToken()->authenticate(); 
            $role = $user->role_id;

            if($role == RoleConst::TEACHER){
                return response(["status"=>"unauthorized"],403);
            }

            $task = Task::findOrFail($id);
            $task_id = $id;
            $user_id = $user->id;
            $answer = $request->answer;
            $start_at = $request->start_at;
            $finished_at = $request->finished_at;

            $new_task_attempt = new TaskAttempt([
                "task_id" => $task_id,
                "user_id" => $user_id,
                "answer" => $answer,
                "start_at" => $start_at,
                "finished_at" => $finished_at
            ]);

            $new_task_attempt->save();
            return response(["status" => "OK"],200);

        }catch(Exception $e){
            return $e;
            return response(["status" => "invalid_input"],405);
        }
    }

    public function show_attempt($id){
        try{
            $user = JWTAuth::parseToken()->authenticate();  
            if($user->role_id == RoleConst::TEACHER){
                $quiz = Task::with(["attempts","attempts.user"])->findOrFail($id);
                return response($quiz->attempts,200);
            }else{
                $quiz = Task::with(["attempts"])->with("attempts",function($q) use($user){
                    return $q->where("user_id",$user->id);
                })->findOrFail($id);
                return response($quiz->attempts,200);
            }
            
        }catch(Exception $e){
            return $e;
        }
    }

    public function detail_attempt($id){
        try{
            $attempt = TaskAttempt::with(["user"])->findOrFail($id);
            return $attempt;
        }catch(Exception $e){
            return $e;
        }
    }
}
