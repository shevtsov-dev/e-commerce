<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Repositories\Product\Interfaces\ProducerRepositoryInterface;

class ProducerComposer extends BaseComposer
{
    /**
     * @param ProducerRepositoryInterface $producerRepository
     */
    public function __construct(
        protected ProducerRepositoryInterface $producerRepository,
    ) {
        parent::__construct([
            'producers' => $producerRepository,
        ]);
    }
}
