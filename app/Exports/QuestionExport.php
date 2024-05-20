<?php

namespace App\Exports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuestionExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect(Question::getAllQuestions());
    }

    public function headings():array {
        return [
            'id',
            'question',
            'subject',
            'type',
            'active', 
            'creator_id', 
            'code', 
            'startdate', 
            'starttime', 
            'enddate', 
            'endtime', 
            'multiple_answer', 
            'open_ended_display'
        ];
    }
}
