<?php

if (!function_exists('calculate_business_days')) {
    function calculate_business_days(string $start, string $end): int
    {
        try {
            $startDate = new DateTime($start);
            $endDate = new DateTime($end);
        } catch (Exception $e) {
            return 0;
        }

        if ($startDate > $endDate) {
            return 0;
        }

        $count = 0;
        $current = clone $startDate;

        while ($current <= $endDate) {
            $dayOfWeek = (int) $current->format('N');
            if ($dayOfWeek <= 5) {
                $count++;
            }
            $current->modify('+1 day');
        }

        return $count;
    }
}
