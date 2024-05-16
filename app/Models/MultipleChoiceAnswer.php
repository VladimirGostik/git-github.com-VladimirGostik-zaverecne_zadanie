<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleChoiceAnswer extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $fillable = [
        'answer',
        'question_id',
        'is_correct',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
