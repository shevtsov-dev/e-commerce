<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\ProducerRequest;
use App\Models\Producer;
use App\Repositories\Product\Interfaces\ProducerRepositoryInterface;
use Psr\Log\LoggerInterface;

class ProducerService
{
    /**
     * @param ProducerRepositoryInterface $producerRepository
     * @param LoggerInterface             $logger
     */
    public function __construct(
        protected ProducerRepositoryInterface $producerRepository,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * @param ProducerRequest $request
     * @param int             $producerId
     *
     * @return Producer
     */
    public function updateProducer(ProducerRequest $request, int $producerId): Producer
    {
        $producer = $this->producerRepository
            ->findById($producerId);
        $producer->update($request->validated());
        $this->logger->info(safe_trans('messages.updated_success'. $producer->id));

        return $producer;
    }

    /**
     * @param int $producerId
     *
     * @return void
     */
    public function deleteProducer(int $producerId): void
    {
        $producer = $this->producerRepository
            ->findById($producerId);
        $producer->delete();
        $this->logger->info(safe_trans('messages.deleted_success'. $producer->id));
    }
}
