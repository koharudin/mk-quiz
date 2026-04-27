<?php

namespace App\Console\Commands;

use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\Topic;
use Illuminate\Console\Command;

class QuizCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:topic';

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
        // $this->generate_questions();
        //$this->attach_topic();
        $this->generate_quiz();
    }

    public function generate_quiz(){
        $quiz = Quiz::create([
            'title' => 'Computer Science Quiz',
            'description' => 'Test your knowledge of computer science',
            'slug' => 'computer-science-quiz',
            'time_between_attempts' => 0, //Time in seconds between each attempt
            'total_marks' => 10,
            'pass_marks' => 6,
            'max_attempts' => 1,
            'is_published' => 1,
            'valid_from' => now(),
            'valid_upto' => now()->addDay(5),
            'media_url'=>'',
            'media_type'=>'',
            'negative_marking_settings'=>[
                'enable_negative_marks' => true,
                'negative_marking_type' => 'fixed',
                'negative_mark_value' => 0,
            ]
        ]);
    }
    public function attach_topic()
    {

        $question  = Question::find(1);
        $computer_science = Topic::find(1);
        $matematika = Topic::find(4);

        //$computer_science->children()->save($matematika);
        $question->topics()->attach([$computer_science->id, $matematika->id]);
    }

    public function generate_questions()
    {
        $question_one = Question::create([
            'question' => 'What is an algorithm?',
            'question_type_id' => 1,
            'is_active' => true,
            'media_url' => 'url',
            'media_type' => 'image'
        ]);
    }
    public function generate_types()
    {
        QuestionType::create(
            [
                [
                    'question_type' => 'multiple_choice_single_answer',
                ],
                [
                    'question_type' => 'multiple_choice_multiple_answer',
                ],
                [
                    'question_type' => 'fill_the_blank',
                ]
            ]
        );
    }
    public function createTopic()
    {
        $computer_science = Topic::create([
            'topic' => 'Olimpiade',
            'slug' => 'olimpiade',
        ]);
    }
}
