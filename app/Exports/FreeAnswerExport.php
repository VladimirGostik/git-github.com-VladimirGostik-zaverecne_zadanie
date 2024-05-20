<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FreeAnswerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect(Question::getAllFreeAnswer());
    }

    public function headings(): array
    {
        return [
            'id', 
            'question_id', 
            'answer'
        ];
    }
}
