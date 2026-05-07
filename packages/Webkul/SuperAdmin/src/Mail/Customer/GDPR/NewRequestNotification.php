<?php

namespace Webkul\SuperAdmin\Mail\Customer\GDPR;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\GDPR\Contracts\GDPRDataRequest;
use Webkul\SuperAdmin\Mail\Mailable;

class NewRequestNotification extends Mailable
{
    /**
     * Create a new message instance.
     */
    public function __construct(public GDPRDataRequest $gdprRequest) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjectKey = $this->gdprRequest->type === 'update'
            ? 'superadmin::app.emails.customers.gdpr.new-update-request'
            : 'superadmin::app.emails.customers.gdpr.new-delete-request';

        return new Envelope(
            to: [
                new Address(
                    core()->getAdminEmailDetails()['email'],
                    core()->getAdminEmailDetails()['name']
                ),
            ],
            subject: trans($subjectKey)
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'superadmin::emails.customers.gdpr.new-request'
        );
    }
}
