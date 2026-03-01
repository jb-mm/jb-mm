<?php

namespace Tests\Unit;

use App\Services\CalendarService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CalendarServiceTest extends TestCase
{
    private CalendarService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CalendarService;
    }

    public function test_returns_correct_month_name_and_year(): void
    {
        $data = $this->service->getMonthData(2026, 3);

        $this->assertEquals(2026, $data['year']);
        $this->assertEquals(3, $data['month']);
        $this->assertEquals('March', $data['monthName']);
    }

    public function test_returns_previous_and_next_month(): void
    {
        $data = $this->service->getMonthData(2026, 3);

        $this->assertEquals(['year' => 2026, 'month' => 2], $data['previousMonth']);
        $this->assertEquals(['year' => 2026, 'month' => 4], $data['nextMonth']);
    }

    public function test_wraps_year_for_january_previous_month(): void
    {
        $data = $this->service->getMonthData(2026, 1);

        $this->assertEquals(['year' => 2025, 'month' => 12], $data['previousMonth']);
    }

    public function test_wraps_year_for_december_next_month(): void
    {
        $data = $this->service->getMonthData(2025, 12);

        $this->assertEquals(['year' => 2026, 'month' => 1], $data['nextMonth']);
    }

    public function test_weeks_cover_all_days_in_month(): void
    {
        // March 2026 has 31 days
        $data = $this->service->getMonthData(2026, 3);

        $allDays = [];
        foreach ($data['weeks'] as $week) {
            foreach ($week as $cell) {
                if ($cell['day'] !== null) {
                    $allDays[] = $cell['day'];
                }
            }
        }

        $this->assertEquals(range(1, 31), $allDays);
    }

    public function test_february_leap_year_has_29_days(): void
    {
        // 2024 was a leap year
        $data = $this->service->getMonthData(2024, 2);

        $allDays = [];
        foreach ($data['weeks'] as $week) {
            foreach ($week as $cell) {
                if ($cell['day'] !== null) {
                    $allDays[] = $cell['day'];
                }
            }
        }

        $this->assertEquals(range(1, 29), $allDays);
    }

    public function test_each_week_has_seven_cells(): void
    {
        $data = $this->service->getMonthData(2026, 3);

        foreach ($data['weeks'] as $week) {
            $this->assertCount(7, $week);
        }
    }

    public function test_first_day_starts_at_correct_position(): void
    {
        // March 1, 2026 is a Sunday (dayOfWeek = 0)
        $data = $this->service->getMonthData(2026, 3);

        $firstWeek = $data['weeks'][0];
        $this->assertEquals(1, $firstWeek[0]['day']); // Sunday position
    }

    public function test_today_is_marked(): void
    {
        $today = Carbon::today();
        $data = $this->service->getMonthData($today->year, $today->month);

        $foundToday = false;
        foreach ($data['weeks'] as $week) {
            foreach ($week as $cell) {
                if ($cell['day'] === $today->day) {
                    $this->assertTrue($cell['isToday']);
                    $foundToday = true;
                }
            }
        }

        $this->assertTrue($foundToday, 'Today should be marked in the calendar');
    }

    public function test_other_month_days_are_not_marked_as_today(): void
    {
        // Use a month that is definitely not the current month
        $today = Carbon::today();
        $otherMonth = $today->month === 6 ? 7 : 6;
        $data = $this->service->getMonthData(2020, $otherMonth);

        foreach ($data['weeks'] as $week) {
            foreach ($week as $cell) {
                $this->assertFalse($cell['isToday']);
            }
        }
    }
}
