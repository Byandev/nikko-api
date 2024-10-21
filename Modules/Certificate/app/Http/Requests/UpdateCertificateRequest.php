<?php

namespace Modules\Certificate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string',
            'issued_date' => 'nullable|date|date_format:Y-m-d|before:today',
            'reference_id' => 'sometimes|string',
            'url' => 'sometimes|string|url',
            'image' => 'sometimes|exists:media,id',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
