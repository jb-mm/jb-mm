<?php

namespace App\Services;

use Carbon\Carbon;

class CalendarService
{
    /**
     * Generate calendar data for the given year and month.
     *
     * @param  int  $year
     * @param  int  $month
     * @return array{
     *     year: int,
     *     month: int,
     *     monthName: string,
     *     weeks: array<array<array{day: int|null, isToday: bool}>>,
     *     previousMonth: array{year: int, month: int},
     *     nextMonth: array{year: int, month: int},
     * }
     */
    public function getMonthData(int $year, int $month): array
    {
        $date = Carbon::createFromDate($year, $month, 1);
        $today = Carbon::today();

        $daysInMonth = $date->daysInMonth;
        $startDayOfWeek = $date->dayOfWeek; // 0 = Sunday

        $weeks = [];
        $currentDay = 1;

        // Build weeks (each week is an array of 7 day cells)
        $week = array_fill(0, 7, ['day' => null, 'isToday' => false]);

        // Fill first week with leading nulls for days before month starts
        for ($i = $startDayOfWeek; $i < 7 && $currentDay <= $daysInMonth; $i++) {
            $week[$i] = [
                'day' => $currentDay,
                'isToday' => $today->year === $year && $today->month === $month && $today->day === $currentDay,
            ];
            $currentDay++;
        }
        $weeks[] = $week;

        // Fill remaining weeks
        while ($currentDay <= $daysInMonth) {
            $week = array_fill(0, 7, ['day' => null, 'isToday' => false]);
            for ($i = 0; $i < 7 && $currentDay <= $daysInMonth; $i++) {
                $week[$i] = [
                    'day' => $currentDay,
                    'isToday' => $today->year === $year && $today->month === $month && $today->day === $currentDay,
                ];
                $currentDay++;
            }
            $weeks[] = $week;
        }

        $previousMonth = $date->copy()->subMonth();
        $nextMonth = $date->copy()->addMonth();

        return [
            'year' => $year,
            'month' => $month,
            'monthName' => $date->format('F'),
            'weeks' => $weeks,
            'previousMonth' => [
                'year' => $previousMonth->year,
                'month' => $previousMonth->month,
            ],
            'nextMonth' => [
                'year' => $nextMonth->year,
                'month' => $nextMonth->month,
            ],
        ];
    }
}
