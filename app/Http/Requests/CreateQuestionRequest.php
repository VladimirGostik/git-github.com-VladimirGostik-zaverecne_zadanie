<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
          
        ]);
    }


    public function rules()
    {
        return [
            'questionText' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'questionType' => 'required|string|in:open_ended,multiple_choice',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date',
            'end_time' => 'required|date_format:H:i',
            'multiple_answer' => 'nullable|boolean',
            'open_ended_display' => 'nullable|string|in:list,word_cloud',
        ];
    }
}