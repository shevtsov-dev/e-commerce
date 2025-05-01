<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\OutputInterface;

Artisan::command('inspire', function (OutputInterface $output) {
    $output->writeln(Inspiring::quote());
})->purpose('Display an inspiring quote');
