<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ExportCompleted extends Mailable
{
    /**
     * @param string $downloadFilePath
     */
    public function __construct(
        protected string $downloadFilePath,
    ) {
    }

    /**
     * @return ExportCompleted
     */
    public function build(): ExportCompleted
    {
        return $this->from(env('MAIL_ADMIN_ADDRESS'))
            ->subject(safe_trans('emails.export_subject'))
            ->view('emails.exportCompleted')
            ->with(['downloadFilePath' => $this->downloadFilePath]);
    }
}
