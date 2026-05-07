<?php

namespace Webkul\SuperAdmin\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Webkul\SuperAdmin\Mail\HtmlEmail;
use Webkul\SuperAdmin\Models\SuperAdminEmailLog;

class SendSuperAdminEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int Do not retry after SMTP failure (log row is already marked failed). */
    public int $tries = 1;

    public function __construct(
        public int $logId
    ) {}

    public function handle(): void
    {
        $log = SuperAdminEmailLog::query()->find($this->logId);

        if (! $log || $log->status !== 'queued') {
            return;
        }

        $body = $log->body_html;
        if ($body === null || $body === '') {
            $log->update([
                'status' => 'failed',
                'error_message' => 'Missing email body in log.',
            ]);

            return;
        }

        $mailer = config('superadmin.mail.mailer', config('mail.default', 'smtp'));

        try {
            Mail::mailer($mailer)->to($log->to_email)->send(new HtmlEmail($log->subject, $body));

            $log->update([
                'status' => 'sent',
                'error_message' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('SuperAdmin queued mail failed', [
                'log_id' => $this->logId,
                'error' => $e->getMessage(),
            ]);

            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
