<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
