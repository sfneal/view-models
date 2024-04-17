<?php

namespace Sfneal\ViewModels\Traits;

use Illuminate\Http\Request;

trait DateSetter
{
    /**
     * Set a search value for $start or $end property.
     *
     * @param Request $request
     * @param string $key
     * @param string $time
     * @return string|null
     */
    private function setDay(Request $request, string $key, string $time = '00:00:00'): ?string
    {
        if (! is_null($day = $request->input($key))) {
            return self::getDatetime($day, $time);
        } else {
            return null;
        }
    }

    /**
     * Retrieve the datetime for a date at midnight.
     *
     * @param  string  $date
     * @param  string  $time
     * @return string
     */
    private static function getDatetime(string $date, string $time = '00:00:00'): string
    {
        return date('Y-m-d', strtotime($date)).' '.$time;
    }
}
