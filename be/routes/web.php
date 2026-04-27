<?php

use App\Models\User;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizAttempt;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $list_quiz =  Quiz::all();
    return view("homepage", ["list_quiz" => $list_quiz]);
});

Route::get("/quiz/{id}", function ($id) {
    $quiz = Quiz::find($id);
    $quiz->load("questions");
    return response()->json([
        "data" => $quiz
    ]);
})->name("quiz.detail");

Route::get("/quiz/{id}/attempt", function ($id) {
    $quiz = Quiz::find(1);
    $user_id = 1;
    $user = User::findOrFail($user_id);
    $quiz_attempt = QuizAttempt::create([
        'quiz_id' => $quiz->id,
        'participant_id' => $user->id,
        'participant_type' => get_class($user)
    ]);
    $quiz_attempt->generateQuestion(30);

    return redirect(route("quiz.attempt-detail", [$quiz_attempt->uuid]));
})->name("quiz.start-attempt");

Route::get("/quiz-attempt/{quiz_attempt_uuid}/question/{question_uuid}", function ($quiz_attempt_uuid, $question_uuid) {
    $quiz_attempt = QuizAttempt::where("uuid", $quiz_attempt_uuid)->get()->first();
    $question_quiz_attempt = QuizQuestion::where("uuid", $question_uuid)->get()->first();
    return view("quiz.attempt-detail", ["quiz_attempt" => $quiz_attempt, "question_quiz_attempt" => $question_quiz_attempt]);
})->name("quiz.attempt-detail-question");




Route::get("/question/{id}", function ($id) {
    $question = Question::find($id);
    $question_two_option_one = QuestionOption::create([
        'question_id' => 1,
        'option' => '8',
        'is_correct' => false,
        'media_type' => 'image',
        'media_url' => 'media url'
    ]);
    $question_two_option_one = QuestionOption::create([
        'question_id' => 1,
        'option' => '21',
        'is_correct' => false,
        'media_type' => 'image',
        'media_url' => 'media url'
    ]);
    $question_two_option_one = QuestionOption::create([
        'question_id' => 1,
        'option' => '11',
        'is_correct' => true,
        'media_type' => 'image',
        'media_url' => 'media url'
    ]);

    $question_two_option_one = QuestionOption::create([
        'question_id' => 1,
        'option' => '9',
        'is_correct' => false,
        'media_type' => 'image',
        'media_url' => 'media url'
    ]);
    $question->load("options");
    return response()->json([
        "data" => $question
    ]);
});



Route::get("/quiz/{id}/add-question/{question_id}", function ($quiz_id, $question_id) {
    $quiz = Quiz::findOrFail($quiz_id);
    $question = Question::findOrFail($question_id);
    $quiz_question =  QuizQuestion::create([
        'quiz_id' => $quiz->id,
        'question_id' => $question->id,
        'marks' => 3,
        'order' => 1,
        'negative_marks' => 1,
        'is_optional' => false
    ]);
    return response()->json([
        "data" => $quiz_question
    ]);
});

Route::get("/participant/create", function () {
    $user = User::create([
        "name" => "Koharudin",
        "email" => "koharudin.mail07@gmail.com",
        "password" => Hash::make("demo")
    ]);
    $user->save();
    return response()->json([
        "data" => $user
    ]);
});
Route::get("/participant/create2", function () {
    $user = User::create([
        "name" => "Andini",
        "email" => "andini.ayudhia.azzahra@gmail.com",
        "password" => Hash::make("demo")
    ]);
    $user->save();
    return response()->json([
        "data" => $user
    ]);
});

Route::get("/quiz/{id}/attempt/{user_id}", function ($id, $user_id) {
    $quiz = Quiz::find(1);
    $user = User::findOrFail($user_id);
    $quiz_attempt = QuizAttempt::create([
        'quiz_id' => $quiz->id,
        'participant_id' => $user->id,
        'participant_type' => get_class($user)
    ]);
    return response()->json([
        "data" => $quiz_attempt
    ]);
});

Route::get("/quiz-attempt/{quiz_attempt_uuid}", function ($quiz_attempt_uuid) {
    $quiz_attempt = QuizAttempt::where("uuid", $quiz_attempt_uuid)->get()->first();
    if (!$quiz_attempt) {
        abort(404);
    }
    $quiz_attempt->load(["participant", "questions"]);
    $score = $quiz_attempt->calculate_score();

    return view("quiz.attempt-detail", ["quiz_attempt" => $quiz_attempt]);
})->name("quiz.attempt-detail");
