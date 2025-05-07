<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $user instanceof User ? $user->id : null;

        $isCreating = $this->isMethod('POST');

        return [
            'name' => $isCreating ? 'required|string|max:255' : 'nullable|string|max:255',

            // Разрешаем email только при создании
            'email' => $isCreating
                ? 'required|email|unique:users,email'
                : '', // ничего не валидируем

            'password' => $isCreating
                ? 'required|string|min:8|confirmed'
                : 'sometimes|nullable|string|min:8|confirmed',

            'role_id' => 'required|exists:roles,id',
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

            'password.confirmed' => safe_trans('messages.password_confirmation', [
                'attribute' => safe_trans('field_names.password'),
            ]),

            'password.min' => safe_trans('messages.password_min', [
                'attribute' => safe_trans('field_names.password'),
            ]),

            'role_id.required' => safe_trans('messages.role_required', [
                'attribute' => safe_trans('field_names.role'),
            ]),
            'role_id.exists' => safe_trans('messages.role_exists', [
                'attribute' => safe_trans('field_names.role'),
            ]),
        ];
    }

    /**
     * @return bool
     */
    private function isCreatingRequest(): bool
    {
        return $this->isMethod('post');
    }
}
