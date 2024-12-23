<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'sometimes|max:255',
            'email' => 'sometimes|email|max:255|unique:users',
            'password' => 'sometimes|min:6',
            'gender' => 'sometimes|in:Male,Female',
            'role' => 'sometimes|in:Talent,Investor,Mentor,Admin',
        ];
    }
}
