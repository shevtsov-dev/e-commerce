<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProducerRequest;
use App\Http\Traits\SuccessMessageTrait;
use App\Models\Producer;
use App\Repositories\Product\Interfaces\ProducerRepositoryInterface;
use App\Services\ProducerService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Psr\Log\LoggerInterface;

class ProducerController extends Controller
{
    use SuccessMessageTrait;

    /**
     * @return string
     */
    protected function entityKey(): string
    {
        return 'producer';
    }

    /**
     * @param ProducerRepositoryInterface $producerRepository
     * @param ProducerService             $producerService
     * @param LoggerInterface             $logger,
     */
    public function __construct(
        protected ProducerRepositoryInterface $producerRepository,
        protected ProducerService             $producerService,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('producers.index');
    }

    /**
     * @param int $producerId
     *
     * @return View
     */
    public function show(int $producerId): View
    {
        return view('producers.show', [
            'producer' => $this->producerRepository
                ->findProducerWithProducts($producerId),
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('producers.create');
    }

    /**
     * @param ProducerRequest $request
     *
     * @return RedirectResponse
     */
    public function store(ProducerRequest $request): RedirectResponse
    {
        $this->producerRepository
            ->create($request->validated());

        return redirect(route('producers.index'))
            ->with('success', $this->successMessage('create'));
    }

    /**
     * @param int $producerId
     *
     * @return View
     */
    public function edit(int $producerId): View
    {
        return view('producers.edit', [
            'producer' => $this->findOrFail($producerId),
        ]);
    }

    /**
     * @param ProducerRequest $request
     * @param int             $producerId
     *
     * @return RedirectResponse
     */
    public function update(ProducerRequest $request, int $producerId): RedirectResponse
    {
        $this->producerService
            ->updateProducer($request, $producerId);

        return redirect(route('producers.index'))
            ->with('success', $this->successMessage('update'));
    }

    /**
     * @param int $producerId
     *
     * @return RedirectResponse
     */
    public function destroy(int $producerId): RedirectResponse
    {
        try {
            $this->producerService->deleteProducer($producerId);

            return redirect(route('producers.index'))
                ->with('success', $this->successMessage('delete'));
        } catch (Exception $e) {
            $this->logger->error(safe_trans('messages.delete_failed', ['error' => $e->getMessage()]));

            return back()->withErrors(safe_trans('messages.delete_failed'));
        }
    }

    /**
     * @param int $producerId
     *
     * @return Producer
     */
    private function findOrFail(int $producerId): Producer
    {
        return $this->producerRepository->findById($producerId);
    }
}
