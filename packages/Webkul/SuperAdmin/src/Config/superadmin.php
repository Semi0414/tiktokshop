<?php

return [
    /**
     * Mailer name from config/mail.php (e.g. smtp). Set MAIL_MAILER in .env.
     *
     * From address: many hosts require MAIL_FROM_ADDRESS (or SUPERADMIN_MAIL_FROM_ADDRESS) to match
     * the mailbox or domain allowed by your SMTP account — otherwise messages bounce as
     * “Mail Delivery Failed: returning message to sender”. Set MAIL_EHLO_DOMAIN to your site domain if needed.
     */
    'mail' => [
        'mailer' => env('SUPERADMIN_MAIL_MAILER', env('MAIL_MAILER', 'smtp')),
        'from' => [
            'address' => env('SUPERADMIN_MAIL_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
            'name' => env('SUPERADMIN_MAIL_FROM_NAME', env('MAIL_FROM_NAME', env('APP_NAME', 'TikStore'))),
        ],
    ],
];
