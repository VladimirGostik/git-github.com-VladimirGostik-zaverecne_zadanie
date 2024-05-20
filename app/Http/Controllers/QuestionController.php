<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateQuestionRequest;
use Illuminate\Http\Request;
use App\Models\FreeResponseAnswer;
use App\Models\MultipleChoiceAnswer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller {
    // Display form for creating a question
    public function create()
    {
        // Fetch all existing users
        $users = User::all();
        
        // Pass the $users variable to the view
        return view('questions.create', compact('users'));
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
            // Initialize values for multiple_answer and open_ended_display
            $multipleAnswer = null;
            $openEndedDisplay = null;
    
            // Determine the value for multiple_answer or open_ended_display based on the question type
            if ($request->questionType === 'multiple_choice') {
                $multipleAnswer = $request->multipleChoiceSelection === 'multiple';
                $openEndedDisplay = null; // Not relevant for multiple choice
            } elseif ($request->questionType === 'open_ended') {
                $multipleAnswer = null; // Not relevant for open ended
                $openEndedDisplay = $request->openEndedDisplay;
            }
    
            $creatorId = auth()->id();
            if (auth()->user()->isAdmin() && $request->filled('user')) {
                $creatorId = $request->user;
            }

            $question = Question::create([
                'question' => $request->questionText,
                'subject' => $request->subject,
                'type' => $request->questionType,
                'creator_id' => $creatorId,
                'code' => $code,
                'startdate' => $request->start_date,
                'starttime' => $request->start_time,
                'enddate' => $request->end_date,
                'endtime' => $request->end_time,
                'multiple_answer' => $multipleAnswer,
                'open_ended_display' => $openEndedDisplay,
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
    
            // Determine the dashboard route based on the user's role
            $dashboardRoute = Auth::user()->isAdmin() ? 'admin.dashboard' : 'dashboard';
            return redirect()->route($dashboardRoute)->with('success', 'Question created successfully!');

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

    public function edit(Question $question)
    {
        // Fetch all existing users
        $users = User::all();

        $multipleChoiceAnswers = $question->multipleChoiceAnswers()->get();
        return view('questions.edit', compact('question', 'multipleChoiceAnswers', 'users'));
    }

    public function update(Request $request, Question $question)
{
    $request->validate([
        'question' => 'required|string|max:255',
        'subject' => 'required|string|max:255',
        'type' => 'required|string|in:open_ended,multiple_choice',
        'start_date' => 'required|date',
        'start_time' => 'required|date_format:H:i',
        'end_date' => 'required|date',
        'end_time' => 'required|date_format:H:i',
        'multiple_answer' => 'nullable|boolean',
        'open_ended_display' => 'nullable|string|in:list,word_cloud',
    ]);

    // Update the question's basic details
    $question->update($request->only('question', 'subject', 'type', 'start_date', 'start_time', 'end_date', 'end_time'));

    // Update the `multiple_answer` and `open_ended_display` fields separately
    if ($request->type == 'multiple_choice') {
        $question->multiple_answer =  $request->multipleChoiceSelection === 'multiple';
        $question->open_ended_display = null; // Reset to null for multiple choice questions
    } else {
        $question->open_ended_display = $request->input('openEndedDisplay', 'list'); // Default to 'list' if not present
        $question->multiple_answer = null; // Reset to null for open-ended questions
    }

    // Update the creator ID if the user is an admin
    if (auth()->user()->isAdmin() && $request->filled('user')) {
        $question->creator_id = $request->user;
    }

    $question->save();

    if ($request->type == 'multiple_choice') {
        // Delete existing multiple choice answers
        $question->multipleChoiceAnswers()->delete();

        // Get options and correct options from the request
        $options = $request->input('options', []);
        $correctOptions = $request->input('correct_options', []);

        // Create new multiple choice answers
        foreach ($options as $index => $option) {
            $question->multipleChoiceAnswers()->create([
                'answer' => $option,
                'is_correct' => isset($correctOptions[$index])
            ]);
        }
    }

    // Determine the dashboard route based on the user's role
    $dashboardRoute = Auth::user()->isAdmin() ? 'admin.dashboard' : 'dashboard';

    // Redirect the user to the appropriate dashboard route
    return redirect()->route($dashboardRoute)->with('success', 'Question updated successfully!');
}

public function show($code)
{
    $question = Question::where('code', $code)->first();

    if (!$question) {
        return redirect()->back()->with('error', 'Question not found.');
    }

    $multipleChoiceAnswers = [];
    if ($question->type === 'multiple_choice') {
        $multipleChoiceAnswers = $question->multipleChoiceAnswers()->get();
    }

    return view('questions.show', compact('question', 'multipleChoiceAnswers'));
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

    // Redirect to the results page for the question
    return redirect()->route('questions.results', ['code' => $answer->question->code])->with('success', 'Answer submitted successfully!');
}

public function storeMultipleChoiceAnswer(Request $request)
{
    // Validate request data
    $request->validate([
        'question_id' => 'required|exists:questions,id',
        'selected_options' => 'required|array',
        'selected_options.*' => 'exists:multiple_choice_answers,id',
    ]);

    // Retrieve the question
    $question = Question::findOrFail($request->input('question_id'));

    // Update the counters for selected options
    foreach ($request->input('selected_options') as $optionId) {
        $option = MultipleChoiceAnswer::findOrFail($optionId);
        $option->increment('counter');
    }

    // Retrieve all options for the question with updated counters
    $multipleChoiceAnswers = $question->multipleChoiceAnswers()->with('question')->get();

    // Calculate the total number of votes
    $totalVotes = $multipleChoiceAnswers->sum('counter');

    // Retrieve the user's selections
    $selectedOptions = $request->input('selected_options');

    // Return the response as JSON
    return response()->json([
        'answers' => $multipleChoiceAnswers,
        'totalVotes' => $totalVotes,
        'userSelections' => $selectedOptions,
    ]);
}

public function showResults($code)
{
    // Retrieve the question using the code
    $question = Question::where('code', $code)->firstOrFail();

    // Get all the free response answers for the question
    $answers = FreeResponseAnswer::where('question_id', $question->id)->get();

    // Pass the question and answers to the view
    return view('questions.results', compact('question', 'answers'));
}

public function destroy($id)
{
    $question = Question::findOrFail($id); // Find the question by its ID

    // Check if the current user is authorized to delete the question
    if (auth()->user()->isAdmin() || $question->creator_id === auth()->id()) {
        $question->delete(); // Delete the question
        return redirect()->route('dashboard')->with('success', 'Question deleted successfully');
    }
    
    // Determine the dashboard route based on the user's role
    $dashboardRoute = Auth::user()->isAdmin() ? 'admin.dashboard' : 'dashboard';
    return redirect()->route($dashboardRoute)->with('success', 'Question deleted successfully!');
}


}
