<?php

namespace App\Http\Requests\Settings\Ozon;

use Illuminate\Foundation\Http\FormRequest;

class OzonStatusSettings extends FormRequest
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
            'status_name' => 'string',
            'status_color' => 'string',
            'ozon_status_name' => 'string',
            'watch_label' => 'boolean',
            'watch_ozon_status' => 'boolean',
        ];
    }
}
