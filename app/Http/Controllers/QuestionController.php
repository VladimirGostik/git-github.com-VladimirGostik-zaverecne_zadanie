<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateQuestionRequest;
use Illuminate\Http\Request;
use App\Models\FreeResponseAnswer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        // Redirect the user to the appropriate dashboard route
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
        $multipleChoiceAnswers = $question->multipleChoiceAnswers()->get();
        return view('questions.edit', compact('question', 'multipleChoiceAnswers'));
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
        $question->multiple_answer = $request->input('multiple_answer', false); // Default to false if not present
        $question->open_ended_display = null; // Reset to null for multiple choice questions
    } else {
        $question->open_ended_display = $request->input('open_ended_display', 'list'); // Default to 'list' if not present
        $question->multiple_answer = null; // Reset to null for open-ended questions
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
    return redirect()->route($dashboardRoute)->with('success', 'Question created successfully!');
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
