<?php

namespace Webkul\SuperAdmin\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAdminEmailLog extends Model
{
    protected $table = 'superadmin_email_logs';

    protected $fillable = [
        'to_email',
        'recipient_type',
        'recipient_id',
        'subject',
        'mail_type',
        'status',
        'error_message',
        'body_preview',
        'body_html',
    ];

    protected $casts = [
        'recipient_id' => 'integer',
    ];
}
