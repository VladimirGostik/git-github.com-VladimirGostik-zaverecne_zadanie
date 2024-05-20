<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MultiAnswerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect(Question::getAllMultiAnswer());
    }

    public function headings(): array
    {
        return [
            'id',
            'question_id',
            'answer',
            'is_correct',
            'counter'
        ];
    }
}
