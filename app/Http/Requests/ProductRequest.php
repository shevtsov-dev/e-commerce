<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
         * @var Product|null $product
         */
        $product   = $this->route('product');
        $productId = $product instanceof Product ? $product->id : null;

        return [
            'category_id'     => 'required|exists:categories,id',
            'name'            => 'required|string|max:255',
            'alias'           => 'required|string|max:255|unique:products,alias,' . ($productId ?: 'NULL'),
            'description'     => 'nullable|string|max:1000',
            'producer_id'     => 'required|exists:producers,id',
            'production_date' => 'nullable|date|required_with:price',
            'price'           => 'nullable|numeric|min:0|max:99999999.99',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => safe_trans('messages.category_id_required', [
                'attribute' => safe_trans('field_names.category_id'),
            ]),
            'category_id.exists' => safe_trans('messages.category_id_exists', [
                'attribute' => safe_trans('field_names.category_id'),
            ]),

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

            'description.string' => safe_trans('messages.description_string', [
                'attribute' => safe_trans('field_names.description'),
            ]),
            'description.max' => safe_trans('messages.description_max', [
                'attribute' => safe_trans('field_names.description'),
            ]),

            'producer_id.required' => safe_trans('messages.producer_id_required', [
                'attribute' => safe_trans('field_names.producer_id'),
            ]),
            'producer_id.exists' => safe_trans('messages.producer_id_exists', [
                'attribute' => safe_trans('field_names.producer_id'),
            ]),

            'production_date.required_with' => safe_trans('messages.production_date_required_with_price', [
                'attribute' => safe_trans('field_names.production_date'),
            ]),
            'production_date.date' => safe_trans('messages.production_date', [
                'attribute' => safe_trans('field_names.production_date'),
            ]),

            'price.numeric' => safe_trans('messages.price_numeric', [
                'attribute' => safe_trans('field_names.price'),
            ]),
            'price.min' => safe_trans('messages.price_min', [
                'attribute' => safe_trans('field_names.price'),
            ]),
            'price.max' => safe_trans('messages.price_max', [
                'attribute' => safe_trans('field_names.price'),
            ]),
        ];
    }
}
