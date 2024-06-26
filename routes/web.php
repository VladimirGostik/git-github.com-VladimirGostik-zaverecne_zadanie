<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\PDFExportController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
require __DIR__.'/auth.php';

// Non-logged user routes
Route::get('/{code}', [QuestionController::class, 'show'])->where('code', '[A-Za-z0-9]{5}')->name('questions.show');

// Regular user routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [QuestionController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions/create', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::patch('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    // Route::post('/questions/{question}/copy', [QuestionController::class, 'copy'])->name('questions.copy'); toto

    Route::get('/export-csv', [QuestionController::class, 'exportToCSV'])->name('export.csv');
    Route::get('/export-csv2', [QuestionController::class, 'export2'])->name('export2.csv');
    Route::get('/export-csv3', [QuestionController::class, 'export3'])->name('export3.csv');
});

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [QuestionController::class, 'allQuestions'])->name('admin.dashboard');

    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/admin/users', [UserController::class, 'allUsers'])->name('admin.users');
    // Route::post('/questions/{question}/copy', [QuestionController::class, 'copy'])->name('admin.questions.copy'); + toto neide
});


// Other routes
Route::get('/tutorial', [TutorialController::class, 'index'])->name('tutorial');
Route::get('/export-pdf', [PDFExportController::class, 'exportPDF'])->name('export-pdf');

// Route to store free response answers
Route::post('/questions/storeFreeResponseAnswer', [QuestionController::class, 'storeFreeResponseAnswer'])->name('questions.storeFreeResponseAnswer');

// Route to store multiple choice answers
Route::post('/questions/storeMultipleChoiceAnswer', [QuestionController::class, 'storeMultipleChoiceAnswer'])->name('questions.storeMultipleChoiceAnswer');

Route::get('/results/{code}', [QuestionController::class, 'showResults'])->name('questions.results');
Route::post('/questions/{question}/copy', [QuestionController::class, 'copy'])->name('admin.questions.copy'); // Bohuzial musi byt takto, ked pridam osobitne do admin aj user grupy tak to nefunguje

