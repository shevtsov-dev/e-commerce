<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Traits\SuccessMessageTrait;
use App\Models\Service;
use App\Repositories\Service\Interfaces\ServiceRepositoryInterface;
use App\Services\ServiceService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Psr\Log\LoggerInterface;

class ServiceController extends Controller
{
    use SuccessMessageTrait;

    /**
     * @return string
     */
    protected function entityKey(): string
    {
        return 'service';
    }

    /**
     * @param ServiceRepositoryInterface $serviceRepository
     * @param ServiceService             $serviceService
     * @param LoggerInterface            $logger,
     */
    public function __construct(
        protected ServiceRepositoryInterface $serviceRepository,
        protected ServiceService             $serviceService,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('services.index');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('services.create');
    }

    /**
     * @param ServiceRequest $request
     *
     * @return RedirectResponse
     */
    public function store(ServiceRequest $request): RedirectResponse
    {
        $this->serviceRepository
            ->create($request->validated());

        return redirect(route('services.index'))
            ->with('success', $this->successMessage('create'));
    }

    /**
     * @param int $serviceId
     *
     * @return View
     */
    public function show(int $serviceId): View
    {
        return view('services.show', [
            'service' => $this->findOrFail($serviceId),
        ]);
    }

    /**
     * @param int $serviceId
     *
     * @return View
     */
    public function edit(int $serviceId): View
    {
        return view('services.edit', [
            'service' => $this->findOrFail($serviceId),
        ]);
    }

    /**
     * @param ServiceRequest $request
     * @param int            $serviceId
     *
     * @return RedirectResponse
     */
    public function update(ServiceRequest $request, int $serviceId): RedirectResponse
    {
        $this->serviceService
            ->updateService($request, $serviceId);

        return redirect(route('services.index'))
            ->with('success', $this->successMessage('update'));
    }

    /**
     * @param int $serviceId
     *
     * @return RedirectResponse
     */
    public function destroy(int $serviceId): RedirectResponse
    {
        try {
            $this->serviceService
                ->deleteService($serviceId);

            return redirect(route('services.index'))
                ->with('success', $this->successMessage('delete'));
        } catch (Exception $e) {
            $this->logger->error(safe_trans('messages.delete_failed', ['error' => $e->getMessage()]));

            return back()->withErrors(safe_trans('messages.delete_failed'));
        }
    }

    /**
     * @param int $serviceId
     *
     * @return Service
     */
    private function findOrFail(int $serviceId): Service
    {
        return $this->serviceRepository->findById($serviceId);
    }
}
