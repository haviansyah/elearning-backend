<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz as ModelsQuiz;
use Illuminate\Http\Request;
use QuestionTypeConst;
use Tymon\JWTAuth\Facades\JWTAuth;

class Quiz extends Controller
{
    public function store(Request $request){

        $creating_user = JWTAuth::parseToken()->authenticate(); 

        $name = $request->name;
        $description = $request->description;

        $due_date = $request->due_date;
        $duration = $request->duration;

        $random_question = $request->is_random ? 1 : 0;

        $question_type = $request->question_type;

        $questions = $request->questions;



        $new_quiz = new ModelsQuiz([
            "name" => $name,
            "description" => $description,
            "due_date" => $due_date,
            "duration" => $duration,
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

        var_dump($new_quiz);

    }
}
