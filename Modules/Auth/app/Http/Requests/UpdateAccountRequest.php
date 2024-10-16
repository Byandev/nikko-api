<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Auth\Enums\LanguageProficiencyType;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|nullable',
            'bio' => 'sometimes|nullable',
            'languages' => 'sometimes|array',
            'languages.*.name' => 'required',
            'languages.*.proficiency' => ['required',  Rule::enum(LanguageProficiencyType::class)],
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
