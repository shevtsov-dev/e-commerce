<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Repositories\Service\Interfaces\ServiceRepositoryInterface;
use Psr\Log\LoggerInterface;

class ServiceService
{
    /**
     * @param ServiceRepositoryInterface $serviceRepository
     * @param LoggerInterface            $logger,
 */
    public function __construct(
        protected ServiceRepositoryInterface $serviceRepository,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * @param ServiceRequest $request
     * @param int            $serviceId
     *
     * @return Service
     */
    public function updateService(ServiceRequest $request, int $serviceId): Service
    {
        $service = $this->serviceRepository
            ->findById($serviceId);
        $service->update($request->validated());
        $this->logger->info(safe_trans('messages.updated_success'. $service->id));

        return $service;
    }

    /**
     * @param int $serviceId
     *
     * @return void
     */
    public function deleteService(int $serviceId): void
    {
        $service = $this->serviceRepository
            ->findById($serviceId);
        $service->delete();
        $this->logger->info(safe_trans('messages.deleted_success'. $service->id));
    }
}
