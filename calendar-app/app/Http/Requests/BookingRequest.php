<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class BookingRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => ['date', 'required'],
            'end_date' => ['date'],
            'start_time' => ['date_format:H:i', 'required'],
            'end_time' => ['date_format:H:i', 'required'],
            'day' => ['string', 'required'],
            'repetition' => ['string', 'required'],
            'user' => ['string', 'required'],
        ];
    }

    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Invalid model',
                'errors' => $validator->errors()
            ], 400)
        );
    }
}
