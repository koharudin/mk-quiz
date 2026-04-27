<?php

namespace App\Console\Commands;

use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuizAttempt;
use Illuminate\Console\Command;

class QuestionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'question:score';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       /// return $this->attempt();
        return $this->calc();
    }
    public function attempt(){
        $quiz_attempt = QuizAttempt::findOrFail(1);
        $randoms = $quiz_attempt->generateQuestion(20);
        $this->info($randoms);
    }
    public function calc(){
        
        $quiz_attempt = QuizAttempt::findOrFail(1);
        $score = $quiz_attempt->calculate_score();
        $this->info("Skor ".$score);
    }
    public function generate()
    {
       
        for ($i = 1; $i <= 100; $i++) {
            $opt1 = rand(10, 50);
            $opt2 = rand(50, 100);
            $q = new Question();
            $q->question =  $opt1 . " + " . $opt2;
            $q->question_type_id = 1;
            $q->save();
        }
    }
}
