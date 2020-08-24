<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiGetPaymentPeriodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'from' => 'required|date',
            'to' => 'required|date'
        ];
    }
}
