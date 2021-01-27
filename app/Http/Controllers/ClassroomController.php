<?php

namespace App\Http\Controllers;

use App\Http\Resources\Classroom as ResourcesClassroom;
use App\Models\Classroom;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ClassroomController extends Controller
{
    function generateCode($length = 6) {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $ret = '';
        for($i = 0; $i < $length; ++$i) {
          $random = str_shuffle($chars);
          $ret .= $random[0];
        }
        return $ret;
    }

    function generateClassCode(){
        $code = $this->generateCode(6);
        $get_code = Classroom::where("code",$code)->get();
        if(count($get_code) == 0){
            return $code;
        }else{
            return $this->generateClassCode();
        }
    }

    public function create(Request $request){
        $classroom = new Classroom($request->all());
        $classroom->code = $this->generateClassCode();
        try{
            $classroom->save();
            return response([
                "status" => "success",
                "id" => $classroom->id,
                "class_code" => $classroom->code
            ],200);
        }catch(Exception $e){
            return response('Input Invalid',403);
        }
    }

    public function getOnly($id){
        $classroom = Classroom::with(["lessons"])->findOrFail($id);
        return response($classroom,200);
    }

    

    public function delete($id){
        $classroom = Classroom::findOrFail($id);
        try{
            $classroom->delete();
            return response([
                "status" => "success",
            ],200);
        }catch(Exception $e){
            return response('Input Invalid',403);
        }
    }

    public function update(Request $request, $id){
        $classroom = Classroom::findOrFail($id);
        try{
            $classroom->update($request->all());
            return response([
                "status" => "success",
                "id" => $classroom->id
            ],200);
        }catch(Exception $e){
            return response('Input Invalid',403);
        }
    }

    public function getAll(){
        $user = JWTAuth::parseToken()->authenticate();
        if($user->role_id == 1 ){
            $classroom = $user->taught_classroom;
        }else{
            $classroom = $user->classroom;
        }
        return response(ResourcesClassroom::collection($classroom),200);
    }


    public function join(Request $request){
        $code = $request->classcode;
        $user = JWTAuth::parseToken()->authenticate();
        $classroom = Classroom::where("code",$code)->first();
        if($user->role_id == 2){
            try{
                $user->classroom()->attach($classroom);
                return response([
                    "status" => "success",
                    "id" => $classroom->id
                ],200);
            }catch(Exception $e){
                return response('Input Invalid',403);
            }
        }
        return response("Student Only", 403);
    }
}
