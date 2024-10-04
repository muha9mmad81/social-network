<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'current_blue_key' => ['required', function ($attribute, $value, $fail) {
                // Check if the current blue key (password) matches the stored password
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('The current blue key does not match our records.');
                }
            }],
            'blue_key'      => 'required|min:8',
        ];
    }
}
