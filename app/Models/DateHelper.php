<?php

namespace Carbon;

class DateHelper
{
    public static function formatYmd($date_string)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date_string)->format('Y:m:d');
    }
}