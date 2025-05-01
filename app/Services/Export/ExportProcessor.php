<?php

declare(strict_types=1);

namespace App\Services\Export;

use App\Mail\ExportCompleted;
use App\Services\Export\Interfaces\ExportProcessorInterface;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Support\Facades\Mail;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ExportProcessor implements ExportProcessorInterface
{
    /**
     * @const FILE_NAME
     */
    private const FILE_NAME_PREFIX = 'products-';

    /**
     * @const FILE_EXTENSION
     */
    private const FILE_EXTENSION = '.csv';

    /**
     * @param S3Client             $s3Client
     * @param string               $bucket
     * @param LoggerInterface      $logger
     * @param string               $adminEmail
     * @param S3DownloadUrlService $urlService
     */
    public function __construct(
        protected S3Client             $s3Client,
        protected S3DownloadUrlService $urlService,
        protected LoggerInterface      $logger,
        protected string               $bucket,
        protected string               $adminEmail,
    ) {
    }

    /**
     * @param string $csvData
     *
     * @return void
     *
     * @throws Exception
     */
    public function handle(string $csvData): void
    {
        try {
            $fileName = $this->uploadToS3($csvData);
            $this->sendExportCompletedEmail($fileName);
        } catch (Throwable $e) {
            $this->logger->error(
                safe_trans('messages.export_failed'),
                [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            throw new Exception(safe_trans('messages.export_failed', ['error' => $e->getMessage()]), previous: $e);
        }
    }

    /**
     * @param string $csvData
     *
     * @return string
     */
    private function uploadToS3(string $csvData): string
    {
        $fileName = self::FILE_NAME_PREFIX . time() . self::FILE_EXTENSION;

        $this->s3Client->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $fileName,
            'Body'   => $csvData,
        ]);

        return $fileName;
    }

    /**
     * @param string $fileName
     *
     * @return void
     */
    private function sendExportCompletedEmail(string $fileName): void
    {
        $downloadUrl = $this->urlService->generate($fileName);

        Mail::to($this->adminEmail)
            ->send(new ExportCompleted($downloadUrl));

        $this->logger->info(safe_trans('messages.export_completed', ['link' => $downloadUrl]));
    }
}
