<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
            'email' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = User::where('email', $value)->orWhere('username', $value)->first();
                    if (!$user) {
                        $fail('The value must be a valid email or username.');
                    }
                },
            ],
        ];
    }
}
