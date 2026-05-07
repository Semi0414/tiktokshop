<?php

namespace Webkul\SuperAdmin\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\GDPR\Models\GDPRDataRequest;
use Webkul\SuperAdmin\Mail\Customer\GDPR\NewRequestNotification;
use Webkul\SuperAdmin\Mail\Customer\GDPR\StatusUpdateNotification;

class GDPR extends Base
{
    /**
     * Send mail on creating GDPR request
     *
     * @param  GDPRDataRequest  $gdprRequest
     * @return void
     */
    public function afterGdprRequestCreated($gdprRequest)
    {
        try {
            Mail::queue(new NewRequestNotification($gdprRequest));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send mail on creating GDPR request
     *
     * @param  GDPRDataRequest  $gdprRequest
     * @return void
     */
    public function afterGdprRequestUpdated($gdprRequest)
    {
        try {
            Mail::queue(new StatusUpdateNotification($gdprRequest));
        } catch (\Exception $e) {
            report($e);
        }
    }
}
