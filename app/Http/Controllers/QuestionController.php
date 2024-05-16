<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateQuestionRequest;
use Illuminate\Http\Request;
use App\Models\FreeResponseAnswer;

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

    // Generate a unique 5-character alphanumeric code
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < 5; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    auth()->user()->questions()->create([
        'question' => $request->questionText,
        'type' => $request->questionType,
        'creator_id' => auth()->id(),
        'code' => $code,
        'startdate' => $request->start_date,
        'starttime' => $request->start_time,
        'enddate' => $request->end_date,
        'endtime' => $request->end_time,

    ]);
    $options = [];
    return back();
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
