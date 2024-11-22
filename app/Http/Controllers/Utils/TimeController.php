<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimeController extends Controller
{
    public function reformatDate(string $date, string $format='ru'): string {
        if($format == 'ru') {
            $dateArr = explode('-', $date);
            return sprintf('%s.%s.%s', $dateArr[2], $dateArr[1], $dateArr[0]);
        }
        $dateArr = explode('.', $date);
        return sprintf('%s-%s-%s', $dateArr[2], $dateArr[1], $dateArr[0]);
    }

    /**
     * @param string $dateTime
     * @param string $format
     * @return array{formatted_date: string, date: string, time: string}
     */
    public function reformatDateTime(string $dateTime, string $format='ru'): array {
        $dateTimeArr = explode(' ', $dateTime);
        $formatDate = $dateTimeArr[0];
        if($format == 'ru') {
            $formatDate = $this->reformatDate($dateTimeArr[0]);
        }
        return [
            'formatted_date' => sprintf('%s %s', $formatDate, $dateTimeArr[1]),
            'date' => $formatDate,
            'time' => $dateTimeArr[1]
        ];
    }
}
