<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'terminal_id' => 'required',
            'card_pan' => 'required',
            'card_expiry_date' => 'required|size:4',
            'account_type' => 'sometimes|string',
            'icc_data' => 'required|string',
            'track2_data' => 'required|string',
            'pin_block' => 'sometimes',
            'sequence_number' => 'sometimes|required|size:3',
            'customer_reference' => 'sometimes',
            'channel' => 'required|string'
        ];
    }
}
