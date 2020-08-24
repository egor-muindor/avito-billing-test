<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRegisterPaymentRequest extends FormRequest
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
            'target' => 'required|string|min:1|max:511',
            'amount' => 'required|numeric|regex:/^\d+(\.\d{1,4})?$/|min:0',
            'callback_url' => 'url|max:511'
        ];
    }

    /**
     * Get the validation rules message text.
     *
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'amount.regex' => 'Number of decimal places must be less than or equal to 4.'
        ];
    }
}
