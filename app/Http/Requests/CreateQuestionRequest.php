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
          'questionText' => 'required|string',
          'questionType' => 'required|string',
          // 'options' => 'required|array|min:2', // Validation for array elements
          // 'correct_options.*' => 'required|array|min:1' // Validation for array elements
        ];
    }
}