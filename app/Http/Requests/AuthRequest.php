<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        /**
         * @var User|null $user
         */
        $user   = $this->route('user');
        $userId = is_object($user) ? (string) $user->id : '';

        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $userId,
            'password' => $this->isMethod('post')
                ? 'required|string|min:8|confirmed'
                : 'nullable|string|min:8|confirmed',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => safe_trans('messages.name_required', [
                'attribute' => safe_trans('field_names.name'),
            ]),
            'name.string' => safe_trans('messages.name_string', [
                'attribute' => safe_trans('field_names.name'),
            ]),
            'name.max' => safe_trans('messages.name_max', [
                'attribute' => safe_trans('field_names.name'),
            ]),

            'email.required' => safe_trans('messages.email_required', [
                'attribute' => safe_trans('field_names.email'),
            ]),
            'email.email' => safe_trans('messages.email_invalid', [
                'attribute' => safe_trans('field_names.email'),
            ]),
            'email.unique' => safe_trans('messages.email_unique', [
                'attribute' => safe_trans('field_names.email'),
            ]),

            'password.required' => safe_trans('messages.password_required', [
                'attribute' => safe_trans('field_names.password'),
            ]),
            'password.min' => safe_trans('messages.password_min', [
                'attribute' => safe_trans('field_names.password'),
            ]),
            'password.confirmed' => safe_trans('messages.password_confirmation', [
                'attribute' => safe_trans('field_names.password'),
            ]),
        ];
    }
}
