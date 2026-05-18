<?php

namespace Database\Seeders;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $quiz = new Quiz();
        $quiz->name = "Olimpiade Matematika SD";
        $quiz->slug = Str::slug($quiz->name);
        $quiz->save();
    }
}
