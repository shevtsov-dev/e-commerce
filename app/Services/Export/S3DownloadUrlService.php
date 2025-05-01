<?php

declare(strict_types=1);

namespace App\Services\Export;

use Psr\Log\LoggerInterface;

class S3DownloadUrlService
{
    /**
     * @var string
     */
    private string $bucket;
    /**
     * @var string
     */
    private string $storageUrl;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected LoggerInterface $logger,
    ) {
        $this->bucket     = (string) config('filesystems.disks.s3.bucket');
        $this->storageUrl = (string) config('filesystems.disks.s3.url');
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public function generate(string $fileName): string
    {
        $bucketPath   = rtrim($this->bucket, '/');
        $storagePath  = rtrim($this->storageUrl, '/');
        $downloadUrl  = "$storagePath/$bucketPath/$fileName";

        $this->logger->info(safe_trans('messages.download_url_generated', ['url' => $downloadUrl]));

        return $downloadUrl;
    }
}
