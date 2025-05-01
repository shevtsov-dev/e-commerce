<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|string|email',
            'password' => 'required|string|min:8',
        ];
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => safe_trans('messages.email_required', [
                'attribute' => safe_trans('field_names.email'),
            ]),
            'email.email' => safe_trans('messages.email_invalid', [
                'attribute' => safe_trans('field_names.email'),
            ]),

            'password.required' => safe_trans('messages.password_required', [
                'attribute' => safe_trans('field_names.password'),
            ]),
            'password.min' => safe_trans('messages.password_min', [
                'attribute' => safe_trans('field_names.password'),
            ]),
        ];
    }
}
