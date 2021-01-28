<?php

namespace App\Http\Controllers;

use App\Http\Resources\Attempt;
use App\Http\Resources\Quiz as ResourcesQuiz;
use App\Models\AttemptAnswer;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz as ModelsQuiz;
use App\Models\QuizAttempt;
use Exception;
use Illuminate\Http\Request;
use QuestionTypeConst;
use RoleConst;
use Tymon\JWTAuth\Facades\JWTAuth;

class Quiz extends Controller
{

    public function index(){
        $user = JWTAuth::parseToken()->authenticate(); 
        $role = $user->role_id;

        if($role == RoleConst::TEACHER){
            return $user->created_quiz;
        }
    }

    public function store(Request $request){

        try{

            $creating_user = JWTAuth::parseToken()->authenticate(); 
            $role = $creating_user->role_id;

            if($role == RoleConst::STUDENT){
                return response(["status"=>"unauthorized"],403);
            }
        
            $name = $request->name;
            $description = $request->description;

            $random_question = $request->is_random ? 1 : 0;

            $question_type = $request->question_type;

            $questions = $request->questions;



            $new_quiz = new ModelsQuiz([
                "name" => $name,
                "description" => $description,
                "random_question" => $random_question,
                "question_type" => $question_type,
                "created_by_user_id" => $creating_user->id
            ]);
            $new_quiz->save();
            
            if($question_type == QuestionTypeConst::MULTIPLE_CHOICE){
                foreach($questions as $question){
                    
                    $question_name = $question["question"];
                    $new_question = new Question([
                        "quiz_id" => $new_quiz->id,
                        "question" => $question_name,
                        "question_type" => $question_type
                    ]);
                    $new_question->save();
                    
                    $options = $question["options"];
                    
                    foreach($options as $option){
                        $new_option = new QuestionOption([
                            "question_id" => $new_question->id,
                            "option" => $option["option"],
                            "isTrue" => $option["is_true"] ?? "0"
                        ]);
                        $new_option->save();
                    }
                }
            }else{
                foreach($questions as $question){
                    $question_name = $question["question"];
                    $question_answer = $question["answer"] ?? null;
                    $new_question = new Question([
                        "quiz_id" => $new_quiz->id,
                        "question" => $question_name,
                        "question_type" => $question_type,
                        "answer" => $question_answer 
                    ]);
                    $new_question->save();
                }
            }
            return response(["status" => "OK","data" => $new_quiz],200);
        }catch(Exception $e){
            return $e;
            return response(["status" => "invalid_input"],405);
        }
        

    }

    public function getOne($id){
        $quiz = ModelsQuiz::findOrFail($id);
        $quiz_data = $quiz->with(["questions","questions.options"])->where("id",$id)->first();
        // return $quiz_data;
        return new ResourcesQuiz($quiz_data);
    }

    public function post_attempt(Request $request, $id){
        try{
            $user = JWTAuth::parseToken()->authenticate(); 
            $quiz_id = $id;
            $user_id = $user->id;
            $start_at = $request->start_at;
            $finished_at = $request->finished_at;
    
    
            $quiz = ModelsQuiz::findOrFail($quiz_id);
            $question_type = $quiz->question_type;
            
            $new_quiz_attempt = new QuizAttempt([
                "quiz_id" => $quiz_id,
                "user_id" => $user_id,
                "start_at" => $start_at,
                "finished_at" => $finished_at            
            ]);
            $new_quiz_attempt->save();
            
            $answers = $request->answers;
    
    
            foreach($answers as $answer){
                $new_answer_attempt = new AttemptAnswer([
                    "quiz_attempt_id" => $new_quiz_attempt->id,
                    "question_id" => $answer["question_id"],
                    "answer" => $answer["answer"]
                ]);
    
                $new_answer_attempt->save();
            }

            switch($quiz->question_type){
                case QuestionTypeConst::MULTIPLE_CHOICE:
                    $this->calculate_poin_multiple_choice($new_quiz_attempt->id);
                break;
                case QuestionTypeConst::MATCH_PAIR:
                    $this->calculate_poin_match_pair($new_quiz_attempt->id);
                break;
                case QuestionTypeConst::TRUE_OR_FALSE:
                    $this->calculate_poin_match_pair($new_quiz_attempt->id);
                break;
            }
            return response(["status"=>"OK"],200);
        }catch(Exception $e){
            return $e;
            return response('invalid_input',403);
        }
       

    }

    public function get_attempt($id){

        try{
            $user = JWTAuth::parseToken()->authenticate();  
            if($user->role_id == RoleConst::TEACHER){
                $quiz = ModelsQuiz::with(["attempts","attempts.user"])->findOrFail($id);
                return response(new Attempt($quiz),200);
            }else{
                $quiz = ModelsQuiz::with(["attempts"])->with("attempts",function($q) use($user){
                    return $q->where("user_id",$user->id);
                })->findOrFail($id);
                return response(new Attempt($quiz),200);
            }
            
        }catch(Exception $e){
            return $e;
        }
    }

    private function calculate_poin_multiple_choice($id){
        $quiz = QuizAttempt::with(["quiz","quiz.questions"])->find($id)->quiz;
        $answers = AttemptAnswer::with(["question","question.options"])->where("quiz_attempt_id",$id)->get();
        
        foreach($answers as $answer){
            $option_id = $answer->answer;
            $option = QuestionOption::find($option_id);
            $answer->poin = $option->isTrue;
            $answer->save();
        }


        $poin = $answers->sum("poin");
        $max_poin = $quiz->questions->count();

        return [
            "max_poin" => $max_poin,
            "poin" => $poin
        ];
    }

    
    public function calculate_poin_match_pair($id){
        $quiz = QuizAttempt::with(["quiz","quiz.questions"])->find($id)->quiz;
        $answers = AttemptAnswer::with(["question","question.options"])->where("quiz_attempt_id",$id)->get();
        
        foreach($answers as $answer){
            $answer->poin = ($answer->answer == $answer->question->answer);
            $answer->save();
        }


        $poin = $answers->sum("poin");
        $max_poin = $quiz->questions->count();

        return [
            "max_poin" => $max_poin,
            "poin" => $poin
        ];
    }

    private function get_poin($id){
        $quiz = QuizAttempt::with(["quiz","quiz.questions"])->find($id)->quiz;
        $answers = AttemptAnswer::with(["question","question.options"])->where("quiz_attempt_id",$id)->get();
        
        $poin = $answers->sum("poin");
        $max_poin = $quiz->questions->count();

        return [
            "max_poin" => $max_poin,
            "poin" => $poin
        ];
    }

    public function get_detail_attempt($id){
        $attempt = \App\Models\QuizAttempt::with(["answers"])->findOrFail($id);
        return $attempt;
    }
}
