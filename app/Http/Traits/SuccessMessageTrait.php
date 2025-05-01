<?php

declare(strict_types=1);

namespace App\Http\Traits;

trait SuccessMessageTrait
{
    /**
     * @return string
     */
    abstract protected function entityKey(): string;

    /**
     * @param string $action
     *
     * @return string
     */
    protected function successMessage(string $action): string
    {
        return safe_trans('messages.' . $action . '_success', [
            'name' => safe_trans('entities.' . $this->entityKey()),
        ]);
    }
}
