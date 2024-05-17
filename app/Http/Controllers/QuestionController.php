<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateQuestionRequest;
use Illuminate\Http\Request;
use App\Models\FreeResponseAnswer;
use Illuminate\Support\Facades\DB;


class QuestionController extends Controller
{
    // Display form for creating a question
    public function create()
    {
        return view('questions.create');
    }

    // Store a new question in the database
    public function store(CreateQuestionRequest $request)
{
    // Start a transaction
    DB::beginTransaction();

    try {
        // Generate a unique 5-character alphanumeric code
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Create the question
        $question = auth()->user()->questions()->create([
            'question' => $request->questionText,
            'type' => $request->questionType,
            'creator_id' => auth()->id(),
            'code' => $code,
            'startdate' => $request->start_date,
            'starttime' => $request->start_time,
            'enddate' => $request->end_date,
            'endtime' => $request->end_time,
        ]);

        // Check if the question type is multiple choice
        if ($request->questionType === 'multiple_choice') {
            // Create and store the multiple choice answers
            $options = $request->options;
            $correctOptions = $request->correct_options;
            foreach ($options as $key => $option) {
                $isCorrect = array_key_exists($key, $correctOptions);
                $question->multipleChoiceAnswers()->create([
                    'answer' => $option,
                    'is_correct' => $isCorrect,
                ]);
            }
        }

        // Commit the transaction if all operations succeed
        DB::commit();

        return redirect()->route('dashboard')->with('success', 'Question created successfully!');
    } catch (\Exception $e) {
        // Rollback the transaction if an exception occurs
        DB::rollBack();

        // Handle the exception as needed (logging, returning an error response, etc.)
        return back()->withError('An error occurred while saving the question.');
    }
}

    // Store a new free response answer in the database
    public function storeFreeResponseAnswer(Request $request)
    {
        // Validate request data
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string',
        ]);

        // Create new free response answer
        $answer = new FreeResponseAnswer();
        $answer->question_id = $request->input('question_id');
        $answer->answer = $request->input('answer');
        $answer->save();

        return redirect()->route('dashboard')->with('success', 'Question created successfully!');
    }
}
