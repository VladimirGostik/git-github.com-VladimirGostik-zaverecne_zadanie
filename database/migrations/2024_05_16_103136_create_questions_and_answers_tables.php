<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsAndAnswersTables extends Migration
{
    public function up()
    {
        // Create Questions Table
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('type');
            $table->boolean('active')->default(true);
            $table->foreignId('creator_id')->constrained('users');
            $table->string('code')->unique();
            $table->date('startdate');
            $table->time('starttime');
            $table->date('enddate');
            $table->time('endtime');
            $table->timestamps(); // Keep timestamps for created_at and updated_at
        });

        // Create Multiple Choice Answers Table
        Schema::create('multiple_choice_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->string('answer');
            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('counter')->default(0); // Counter for vote count
            $table->timestamps();
        });

        // Create Free Response Answers Table
        Schema::create('free_response_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('answer');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('free_response_answers');
        Schema::dropIfExists('multiple_choice_answers');
        Schema::dropIfExists('questions');
    }
}
