<?php

namespace Database\Seeders;

use App\Http\Controllers\ClassroomController;
use App\Models\Classroom;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use QuestionTypeConst;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('classrooms')->truncate();
        DB::table('users')->truncate();
        DB::table('roles')->truncate();

        $guru = new Role([
            "id" => 1,
            "name" => "TEACHER"
        ]);
        $guru->save();
        $murid = new Role([
            "id" => 2,
            "name" => "STUDENT"
        ]);
        $murid->save();

        $guru_haviansyah = new User([
            "name" => "Muhammad Haviansyah",
            "email" => "haviansyah09@gmail.com",
            "password" => Hash::make("testing12345"),
            "role_id" => 1,
        ]);
        $guru_haviansyah->save();


        $murid_nanang = new User([
            "name" => "Nanang Sukajan",
            "email" => "sukajan@gmail.com",
            "password" => Hash::make("testing12345"),
            "role_id" => 2,
        ]);
        $murid_nanang->save();


        $murid_ijan = new User([
            "name" => "Ijan Supardi",
            "email" => "ijan@gmail.com",
            "password" => Hash::make("testing12345"),
            "role_id" => 2,
        ]);
        $murid_ijan->save();

        $kelas_4ka21 = new Classroom([
            "name" => "4KA21",
            "subject" => "Ilmu Kanuragan",
            "description" => "Belajar Ilmu Hitam",
            "max_student" => 50,
            "teacher_user_id" => $guru_haviansyah->id,
            "code" => (new ClassroomController)->generateClassCode()
        ]);
        $kelas_4ka21->save();

        // Attacth Student to Class
        $kelas_4ka21->students()->attach($murid_nanang);
        $kelas_4ka21->students()->attach($murid_ijan);

        $quiz_mpc = new Quiz([
            "name" => "Quiz 1",
            "description" => "testing quiz MPC",
            "random_question" => 1,
            "question_type" => QuestionTypeConst::MULTIPLE_CHOICE,
            "created_by_user_id" => $guru_haviansyah->id
        ]);

        $quiz_mpc->save();

        $question_1 = new Question([
            "question" => "Apa yang dilakukan jika mengantuk?",
            "question_type" => QuestionTypeConst::MULTIPLE_CHOICE,
            "quiz_id" => $quiz_mpc->id
        ]);
        $question_1->save();

        QuestionOption::insert([
            [
                "question_id" => $question_1->id,
                "option" => "Makan",
                "isTrue" => 0
            ],
            [
                "question_id" => $question_1->id,
                "option" => "Minum",
                "isTrue" => 0
            ],
            [
                "question_id" => $question_1->id,
                "option" => "Begadang",
                "isTrue" => 0
            ],
            [
                "question_id" => $question_1->id,
                "option" => "Tidur",
                "isTrue" => 1
            ],
        ]);
    }
}
