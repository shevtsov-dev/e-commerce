<?php

declare(strict_types=1);

namespace App\Services\Export\Interfaces;

interface ExportProcessorInterface
{
    public function handle(string $csvData): void;
}
