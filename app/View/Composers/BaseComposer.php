<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Repositories\Product\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Product\Interfaces\ProducerRepositoryInterface;
use App\Repositories\Product\Interfaces\ProductRepositoryInterface;
use App\Repositories\Service\Interfaces\ServiceRepositoryInterface;
use App\Repositories\UserRole\Interfaces\UserRoleRepositoryInterface;
use App\View\Composers\Interfaces\BaseComposerInterface;
use Illuminate\View\View;

abstract class BaseComposer implements BaseComposerInterface
{
    /**
     * @param (ProductRepositoryInterface|CategoryRepositoryInterface|ProducerRepositoryInterface|ServiceRepositoryInterface|UserRoleRepositoryInterface)[] $repositories
     */
    public function __construct(
        protected readonly array $repositories,
    ) {
    }

    /**
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view): void
    {
        foreach ($this->repositories as $key => $repository) {
            $view->with($key, $repository->getAll());
        }
    }
}
