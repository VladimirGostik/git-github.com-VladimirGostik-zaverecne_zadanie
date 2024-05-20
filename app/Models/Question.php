<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'subject',
        'type',
        'code',
        'startdate',
        'starttime',
        'enddate',
        'endtime',
        'creator_id',
        'multiple_answer',
        'open_ended_display',
    ];

    protected $guarded = [];

    public function multipleChoiceAnswers()
    {
        return $this->hasMany(MultipleChoiceAnswer::class, 'question_id');
    }

    public function freeResponseAnswers()
    {
        return $this->hasMany(FreeResponseAnswer::class, 'question_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getAllQuestions(){
        $result = DB::table('questions')
        ->select('id', 'question', 'subject', 'type', 'active', 'creator_id', 'code', 'startdate', 'starttime', 'enddate', 'endtime', 'multiple_answer', 'open_ended_display')
        ->get()
        ->toArray();

        return $result;
    }

    public static function getAllMultiAnswer(){
        $result = DB::table('multiple_choice_answers')
        ->select('id', 'question_id', 'answer', 'is_correct', 'counter')
        ->get()
        ->toArray();

        return $result;
    }
    public static function getAllFreeAnswer(){
        $result = DB::table('free_response_answers')
        ->select('id', 'question_id', 'answer')
        ->get()
        ->toArray();

        return $result;
    }

}
