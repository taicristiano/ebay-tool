<?php

namespace App\Services;

use Lang;
use Carbon\Carbon;

class CommonService
{
    /**
     * validate email
     * @param  string $email 
     * @return boolean
     */
    public function validateEmail($email)
    {
        if (!$email) {
            return false;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    /**
     * format date
     * @param  string $format
     * @param  string $date
     * @return string
     */
    public function formatDate($format, $date)
    {
        if (!$date) {
            return;
        }
        return Carbon::parse($date)->format($format);
    }

    public function getStatusFlag($value)
    {
        return $value ? Lang::get('view.on') : Lang::get('view.off');
    }
}