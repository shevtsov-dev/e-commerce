<?php

declare(strict_types=1);

namespace App\Providers;

use App\Formatters\Interfaces\DataFormatterInterface;
use App\Formatters\ProductCsvFormatter;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Repositories\CurrencyRate\CurrencyRateRepository;
use App\Repositories\CurrencyRate\Interfaces\CurrencyRateRepositoryInterface;
use App\Repositories\Product\CategoryRepository;
use App\Repositories\Product\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Product\Interfaces\ProducerRepositoryInterface;
use App\Repositories\Product\Interfaces\ProductRepositoryInterface;
use App\Repositories\Product\ProducerRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Service\Interfaces\ServiceRepositoryInterface;
use App\Repositories\Service\ServiceRepository;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\UserRole\Interfaces\UserRoleRepositoryInterface;
use App\Repositories\UserRole\UserRoleRepository;
use App\Services\Currency\CurrencyConverter;
use App\Services\Currency\CurrencyFetcher;
use App\Services\Currency\CurrencyRateUpdater;
use App\Services\Currency\Interfaces\CurrencyConverterInterface;
use App\Services\Currency\Interfaces\CurrencyFetcherInterface;
use App\Services\Currency\Interfaces\CurrencyRateUpdaterInterface;
use App\Services\Export\ExportProcessor;
use App\Services\Export\Interfaces\ExportProcessorInterface;
use App\Services\Export\S3DownloadUrlService;
use App\Services\RabbitMq\RabbitMqConnector;
use App\Services\RabbitMq\RabbitMqInterfaces\RabbitMqConnectorInterface;
use App\Services\RabbitMq\RabbitMqInterfaces\RabbitMqConsumerInterface;
use App\Services\RabbitMq\RabbitMqInterfaces\RabbitMqPublisherInterface;
use App\View\Composers\CategoryComposer;
use App\View\Composers\ProducerComposer;
use App\View\Composers\ProductComposer;
use App\View\Composers\ServiceComposer;
use App\View\Composers\UserComposer;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->registerBindings();
        $this->registerViewComposers();
        $this->registerExternalServices();
    }

    /**
     * @return void
     */
    private function registerBindings(): void
    {
        $bindings = [
            ProductRepositoryInterface::class      => ProductRepository::class,
            CategoryRepositoryInterface::class     => CategoryRepository::class,
            ProducerRepositoryInterface::class     => ProducerRepository::class,
            ServiceRepositoryInterface::class      => ServiceRepository::class,
            UserRepositoryInterface::class         => UserRepository::class,
            UserRoleRepositoryInterface::class     => UserRoleRepository::class,
            RabbitMqConsumerInterface::class       => RabbitMqConnector::class,
            RabbitMqPublisherInterface::class      => RabbitMqConnector::class,
            RabbitMqConnectorInterface::class      => RabbitMqConnector::class,
            DataFormatterInterface::class          => ProductCsvFormatter::class,
            CurrencyConverterInterface::class      => CurrencyConverter::class,
            CurrencyFetcherInterface::class        => CurrencyFetcher::class,
            CurrencyRateUpdaterInterface::class    => CurrencyRateUpdater::class,
            CurrencyRateRepositoryInterface::class => CurrencyRateRepository::class,
        ];

        foreach ($bindings as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }

        $this->app->bind(ExportProcessorInterface::class, function () {
            return new ExportProcessor(
                app(S3Client::class),
                app(S3DownloadUrlService::class),
                app(LoggerInterface::class),
                config('filesystems.disks.s3.bucket'),
                config('mail.from.address'),
            );
        });
    }

    /**
     * @return void
     */
    private function registerViewComposers(): void
    {
        View::composer([
            'products.create',
            'products.edit',
            'products.index',
            'products.show',
            'home.index',
        ], ProductComposer::class);

        View::composer([
            'categories.create',
            'categories.index',
            'categories.edit',
        ], CategoryComposer::class);

        View::composer([
            'producers.create',
            'producers.index',
            'producers.edit',
        ], ProducerComposer::class);

        View::composer([
            'services.create',
            'services.index',
            'services.edit',
        ], ServiceComposer::class);

        View::composer([
            'admin.users.create',
            'admin.users.index',
            'admin.users.edit',
        ], UserComposer::class);
    }

    /**
     * @return void
     */
    private function registerExternalServices(): void
    {
        $this->app->singleton(fn (): S3Client => new S3Client([
            'region'                  => env('AWS_DEFAULT_REGION'),
            'version'                 => 'latest',
            'endpoint'                => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT'),
            'credentials'             => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
            'debug' => env('AWS_DEBUG'),
        ]));

        $this->app->singleton(fn (): AMQPStreamConnection => new AMQPStreamConnection(
            config('rabbitmq.connections.rabbitmq.host'),
            config('queue.connections.rabbitmq.port'),
            config('queue.connections.rabbitmq.user'),
            config('queue.connections.rabbitmq.pass'),
        ));
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        Route::middlewareGroup('auth', [
            Authenticate::class,
        ]);

        Route::middlewareGroup('user', [
            RedirectIfAuthenticated::class,
        ]);
    }
}
