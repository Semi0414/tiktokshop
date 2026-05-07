<?php

namespace Webkul\SuperAdmin\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Webkul\SuperAdmin\Mail\HtmlEmail;
use Webkul\SuperAdmin\Models\SuperAdminEmailLog;

class SuperAdminEmailService
{
    /**
     * Keep only keys that exist on superadmin_email_logs (legacy DBs may omit columns).
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    protected function onlyExistingLogColumns(array $attributes): array
    {
        if (! Schema::hasTable('superadmin_email_logs')) {
            return [];
        }

        $existing = array_flip(Schema::getColumnListing('superadmin_email_logs'));

        return array_intersect_key($attributes, $existing);
    }

    /**
     * Build attributes for superadmin_email_logs; columns not present in DB are dropped.
     *
     * @return array<string, mixed>
     */
    protected function attributesForEmailLog(
        string $to,
        string $subject,
        string $recipientType,
        ?int $recipientId,
        string $mailType,
        string $status,
        ?string $errorMessage,
        string $bodyPreview
    ): array {
        $row = [
            'to_email' => $to,
            'recipient_type' => $recipientType,
            'recipient_id' => $recipientId,
            'subject' => $subject,
            'mail_type' => $mailType,
            'status' => $status,
            'error_message' => $errorMessage,
            'body_preview' => $bodyPreview,
        ];

        return $this->onlyExistingLogColumns($row);
    }

    /**
     * Send HTML mail using configured mailer and persist a log row.
     * When `body_html` column exists, a log row is created first, then mail is sent synchronously
     * in the same request (status: queued → sent | failed). Legacy installs without `body_html`
     * insert a single "sent" or "failed" row without storing full HTML.
     */
    public function send(
        string $to,
        string $subject,
        string $htmlBody,
        string $recipientType,
        ?int $recipientId,
        string $mailType = 'custom'
    ): void {
        $htmlBody = $this->ensureUtf8MailHtml($htmlBody);

        $previewPlain = strip_tags($htmlBody);
        $preview = Str::limit($previewPlain, 500, '');

        if (! Schema::hasTable('superadmin_email_logs')) {
            $mailer = config('superadmin.mail.mailer', config('mail.default', 'smtp'));
            Mail::mailer($mailer)->to($to)->send(new HtmlEmail($subject, $htmlBody));

            return;
        }

        $useBodyHtmlLog = Schema::hasColumn('superadmin_email_logs', 'body_html');

        if ($useBodyHtmlLog) {
            $attrs = $this->onlyExistingLogColumns(array_merge(
                $this->attributesForEmailLog(
                    $to,
                    $subject,
                    $recipientType,
                    $recipientId,
                    $mailType,
                    'queued',
                    null,
                    $preview
                ),
                ['body_html' => $htmlBody]
            ));

            if ($attrs === []) {
                return;
            }

            $log = SuperAdminEmailLog::query()->create($attrs);

            $body = $log->body_html;
            if ($body === null || $body === '') {
                $log->update([
                    'status' => 'failed',
                    'error_message' => 'Missing email body in log.',
                ]);

                throw new \RuntimeException('Missing email body in log.');
            }

            $mailer = config('superadmin.mail.mailer', config('mail.default', 'smtp'));

            try {
                Mail::mailer($mailer)->to($log->to_email)->send(new HtmlEmail($log->subject, $body));

                $log->update([
                    'status' => 'sent',
                    'error_message' => null,
                ]);
            } catch (\Throwable $e) {
                Log::error('SuperAdmin mail send failed', [
                    'log_id' => $log->id,
                    'to' => $log->to_email,
                    'error' => $e->getMessage(),
                ]);

                $log->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                throw $e;
            }

            return;
        }

        $mailer = config('superadmin.mail.mailer', config('mail.default', 'smtp'));

        try {
            Mail::mailer($mailer)->to($to)->send(new HtmlEmail($subject, $htmlBody));

            $attrs = $this->attributesForEmailLog(
                $to,
                $subject,
                $recipientType,
                $recipientId,
                $mailType,
                'sent',
                null,
                $preview
            );
            if ($attrs !== []) {
                SuperAdminEmailLog::query()->create($attrs);
            }
        } catch (\Throwable $e) {
            Log::error('SuperAdmin mail send failed', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            try {
                $attrs = $this->attributesForEmailLog(
                    $to,
                    $subject,
                    $recipientType,
                    $recipientId,
                    $mailType,
                    'failed',
                    $e->getMessage(),
                    $preview
                );
                if ($attrs !== []) {
                    SuperAdminEmailLog::query()->create($attrs);
                }
            } catch (\Throwable $logError) {
                Log::error('SuperAdmin email log insert failed', [
                    'to' => $to,
                    'error' => $logError->getMessage(),
                ]);
            }

            throw $e;
        }
    }

    /**
     * Normalize encoding so Symfony Mailer and MySQL do not reject malformed UTF-8 in HTML bodies.
     */
    protected function ensureUtf8MailHtml(string $html): string
    {
        if ($html === '') {
            return '';
        }

        if (mb_check_encoding($html, 'UTF-8')) {
            return $html;
        }

        $detected = mb_detect_encoding($html, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        if ($detected !== false && $detected !== 'UTF-8') {
            return mb_convert_encoding($html, 'UTF-8', $detected);
        }

        $clean = @iconv('UTF-8', 'UTF-8//IGNORE', $html);

        return $clean !== false ? $clean : $html;
    }
}
