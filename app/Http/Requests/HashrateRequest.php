<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HashrateRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tariff' => 'required|integer',
            'consumption' => 'required|integer',
            'start_date' => ['required', 'date_format:Y-m-d', 'before:end_date', 'after:2010-01-01'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after:2010-01-01'],
        ];
    }
}
