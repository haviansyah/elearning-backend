<?php

namespace App\Http\Controllers;

use App\Models\AttemptAnswer;
use App\Models\Classroom;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class GradeController extends Controller
{

    private function get_poin($id){
        $quiz = QuizAttempt::with(["quiz","quiz.questions"])->find($id)->quiz;
        $answers = AttemptAnswer::with(["question","question.options"])->where("quiz_attempt_id",$id)->get();
        
        $poin = $answers->sum("poin");
        $max_poin = $quiz->questions->count();

        return [
            "max_poin" => $max_poin,
            "poin" => $poin,
            "percentage" => $poin/$max_poin*100
        ];
    }

    public function get_grade_by_classroom($id){
        $classroom = Classroom::with(["lessons","lessons.task","lessons.task.attempts","lessons.quiz","lessons.quiz.attempts","students"])->findOrFail($id);
        $students = $classroom->students;
        $lesson = $classroom->lessons;
        return $classroom;

    }
    
}
