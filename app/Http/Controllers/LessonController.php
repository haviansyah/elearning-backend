<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Exception;
use Illuminate\Http\Request;
use RoleConst;
use Tymon\JWTAuth\Facades\JWTAuth;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $user = JWTAuth::parseToken()->authenticate(); 
            $role = $user->role_id;

            if($role == RoleConst::STUDENT){
                return response(["status"=>"unauthorized"],403);
            }

            $materi_file_name = null;
            if ($files = $request->file('file_materi')) {
             
                //store file into document folder
                $file = $request->file_materi->store('uploads/materi','public');
     
                //store your file into database
                $materi_file_name = $file;
            }

            $name = $request->name;
            $classroom_id = $request->classroom_id;

            $due_date = $request->due_date;
            $duration = $request->duration;

            $quiz_id = $request->quiz_id;
            $task_id = $request->task_id;
            
            $video_url = $request->video_url ?? null;
            $new_lesson = new Lesson([
                "name" => $name,
                "classroom_id" => $classroom_id,
                "due_date" => $due_date,
                "duration" => $duration,
                "quiz_id" => $quiz_id,
                "task_id" => $task_id,
                "video_url" => $video_url,
                "materi_file_name" => $materi_file_name
            ]);

            $new_lesson->save();
            return response(["status" => "OK"],200);
            
        }catch(Exception $e){
            return $e;
            return response(["status" => "invalid_input"],405);
        }
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lesson = Lesson::findOrFail($id);
        return $lesson;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $lesson = Lesson::findOrFail($id);
            $materi_file_name = null;
            if ($files = $request->file('file_materi')) {
             
                //store file into document folder
                $file = $request->file_materi->store('uploads/materi','public');
     
                //store your file into database
                $materi_file_name = $file;
                $lesson->materi_file_name = $materi_file_name;
                $lesson->save();
            }

            $name = $request->name;
            $classroom_id = $request->classroom_id;
            $quiz_id = $request->quiz_id;
            $task_id = $request->task_id;
            $due_date = $request->due_date;
            $duration = $request->duration;
            $video_url = $request->video_url ?? null;
            $lesson->update([
                "name" => $name,
                "classroom_id" => $classroom_id,
                "due_date" => $due_date,
                "duration" => $duration,
                "quiz_id" => $quiz_id,
                "task_id" => $task_id,
                "video_url" => $video_url,
            ]);

            return response(["status"=>"OK"],200);
        }catch(Exception $e){
            return response(["status"=>"invalid input"],403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
