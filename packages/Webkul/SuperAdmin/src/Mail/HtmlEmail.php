<?php

namespace Webkul\SuperAdmin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

class HtmlEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public string $htmlBody
    ) {}

    public function build(): self
    {
        [$address, $name] = $this->resolveFrom();

        $mailable = $this->subject($this->subjectLine)
            ->from($address, $name)
            ->html($this->htmlBody);

        return $mailable;
    }

    /**
     * @return array{0: string, 1: string}
     */
    protected function resolveFrom(): array
    {
        $address = (string) (config('superadmin.mail.from.address') ?: config('mail.from.address') ?: '');
        $name = (string) (config('superadmin.mail.from.name') ?: config('mail.from.name') ?: config('app.name', 'App'));

        if ($address === '') {
            throw new RuntimeException(
                'Mail “from” address is not configured. Set MAIL_FROM_ADDRESS (and MAIL_FROM_NAME) in .env '.
                'to an address your SMTP server is allowed to send as — often the same mailbox as MAIL_USERNAME on shared hosting. '.
                'Optional: SUPERADMIN_MAIL_FROM_ADDRESS / SUPERADMIN_MAIL_FROM_NAME.'
            );
        }

        return [$address, $name];
    }
}
