<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
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
    public function rules(): array
    {
        /**
         * @var Category|null $category
         */
        $category   = $this->route('category');
        $categoryId = $category instanceof Category ? $category->id : null;

        return [
            'name'  => 'required|string|max:255',
            'alias' => 'required|string|max:255|unique:categories,alias,' . ($categoryId ?: 'NULL'),
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

            'alias.required' => safe_trans('messages.alias_required', [
                'attribute' => safe_trans('field_names.alias'),
            ]),
            'alias.string' => safe_trans('messages.alias_string', [
                'attribute' => safe_trans('field_names.alias'),
            ]),
            'alias.max' => safe_trans('messages.alias_max', [
                'attribute' => safe_trans('field_names.alias'),
            ]),
            'alias.unique' => safe_trans('messages.alias_unique', [
                'attribute' => safe_trans('field_names.alias'),
            ]),
        ];
    }
}
