<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
         * @var Service|null $service
         */
        $service   = $this->route('service');
        $serviceId = $service instanceof Service ? $service->id : null;

        return [
            'name'        => 'required|string|max:255',
            'alias'       => 'required|string|max:255|unique:services,alias,' . ($serviceId ?: 'NULL'),
            'target_date' => 'nullable|date|required_with:price',
            'price'       => 'nullable|numeric|min:0|max:99999999.99',
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

            'target_date.required_with' => safe_trans('messages.target_date_required_with_price', [
                'attribute' => safe_trans('field_names.target_date'),
            ]),
            'target_date.date' => safe_trans('messages.target_date', [
                'attribute' => safe_trans('field_names.target_date'),
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
