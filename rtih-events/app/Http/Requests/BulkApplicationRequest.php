<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We will authorize individual applications in the controller/service
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id',
            'action' => 'required|string|in:approve,reject,delete'
        ];
    }
}
