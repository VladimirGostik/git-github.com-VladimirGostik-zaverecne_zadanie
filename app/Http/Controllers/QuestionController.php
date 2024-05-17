<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateQuestionRequest;
use Illuminate\Http\Request;
use App\Models\FreeResponseAnswer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class QuestionController extends Controller {
    // Display form for creating a question
    public function create()
    {
        return view('questions.create');
    }

    // Store a new question in the database
    public function store(CreateQuestionRequest $request) {
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
            'subject' => $request->subject,
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

// Retrieve all questions created by the authenticated user
public function index()
{
    $userQuestions = auth()->user()->questions()->latest()->get();
    return view('dashboard', compact('userQuestions'));
}

// Retrieve all questions from the database - for admin users
public function allQuestions()
{
    // Retrieve all questions with their respective creators
    $questions = Question::with('user')->latest()->get();

    // Extract creator names from users table
    $creatorNames = [];
    foreach ($questions as $question) {
        $creatorId = $question->creator_id;
        $creator = User::find($creatorId);
        $creatorNames[$question->id] = $creator->name;
    }

    return view('admin.dashboard', compact('questions', 'creatorNames'));
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
