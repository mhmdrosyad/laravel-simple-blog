<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePostRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:60'],
            'content' => ['required', 'string'],
            'is_draft' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date']
        ];
    }

    public function data(): array
    {
        return [
            ...$this->validated(),
            'user_id' => Auth::id(),
            'status' => $this->is_draft ? 'draft' : 'published',
        ];
    }
}
