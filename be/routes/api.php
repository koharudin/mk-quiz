<?php

use App\Models\User;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("/", function () {
    return response()->json(["message" => "oke"]);
});

Route::get("/token", function () {
    $user = User::find(2);
    $token = $user->createToken("app_token");
    return response()->json(["token" => $token]);
});
Route::get("/info", function () {
    return response()->json(["message" => "info"]);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(["middleware" => ["auth:api"]], function ($router) {
    Route::get("/quiz/", function () {
        $quiz = Quiz::all();
        $quiz->load("questions");
        return response()->json([
            "data" => $quiz
        ]);
    })->name("quiz.detail");
    Route::post("/quiz/{id}/attempt", function ($id) {
        $quiz = Quiz::find($id);
        $user = auth()->user();
        $quiz_attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user)
        ]);
        $quiz_attempt->generateQuestion(30);

        return response()->json(["message" => "generated quiz", "quiz_attempt_uuid" => $quiz_attempt->uuid]);
    })->name("quiz.start-attempt");

    Route::get("/quiz-attempt/{quiz_attempt_uuid}", function ($quiz_attempt_uuid) {
        $quiz_attempt = QuizAttempt::where("uuid", $quiz_attempt_uuid)->get()->first();
        if (!$quiz_attempt) {
            abort(404);
        }
        $quiz_attempt->load(["participant", "questions"]);
        $score = $quiz_attempt->calculate_score();

        return response()->json(["quiz_attempt" => $quiz_attempt]);
    })->name("quiz.attempt-detail");
});
