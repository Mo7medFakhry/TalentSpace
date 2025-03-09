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
            'email' => 'sometimes|email|max:255',
            'password' => 'sometimes|min:6',
            'phone' => 'sometimes|string|max:15',
            'address' => 'sometimes|string|max:100',
            'birthday' => 'sometimes|date',
            'bio' => 'sometimes|string',
            'profilePicture' => 'sometimes|string',
            'gender' => 'sometimes|in:Male,Female',
            'role' => 'sometimes|in:Talent,Investor,Mentor,Admin',
        ];
    }
}
