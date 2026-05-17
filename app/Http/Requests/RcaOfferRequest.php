<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RcaOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

   public function rules(): array
{
    return [
        'api_token' => ['required', 'string'],
        'tax_id' => ['required', 'digits:13'],
        'birthdate' => ['required', 'date'],
        'vin' => ['required', 'min:8', 'max:17'],
        'registration_number' => ['required', 'min:5'],
    ];
}

    public function messages(): array
    {
        return [
            'tax_id.required' => 'CNP este obligatoriu.',
            'tax_id.digits' => 'CNP trebuie să aibă 13 cifre.',

            'birthdate.required' => 'Data nașterii este obligatorie.',

            'vin.required' => 'VIN este obligatoriu.',
            'vin.min' => 'VIN prea scurt.',

            'registration_number.required' => 'Numărul auto este obligatoriu.',
        ];
    }
}