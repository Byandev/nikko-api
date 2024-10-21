<?php

namespace Modules\Certificate\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCertificateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'issued_date' => 'nullable|date|date_format:Y-m-d|before:today',
            'reference_id' => 'nullable|string',
            'url' => 'nullable|string|url',
            'image' => 'required|exists:media,id',
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
