<?php

namespace App\Services;

class OtherServices
{
    public static function formatDates($dateString)
    {
        // Split the input string by comma and trim each date
        $dates = array_map('trim', explode(',', $dateString));

        // Parse the dates and group by month and year
        $formattedDates = [];
        foreach ($dates as $date) {
            $timestamp = strtotime($date);
            $year = date('Y', $timestamp);
            $month = date('M', $timestamp);
            $day = date('d', $timestamp);

            // Group days by month and year
            $formattedDates[$year][$month][] = $day;
        }

        // Construct the formatted string
        $result = [];
        foreach ($formattedDates as $year => $months) {
            foreach ($months as $month => $days) {
                $result[] = $month . ' ' . implode(', ', $days) . ' ' . $year;
            }
        }

        return implode(', ', $result);
    }
}
