<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateLabelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:qr-code,code-128'],
            'data' => ['required', 'string', 'max:2048'],
            'seat_id' => ['required', 'integer', 'min:1'],
            'sign' => ['nullable', 'string', 'max:128'],
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
