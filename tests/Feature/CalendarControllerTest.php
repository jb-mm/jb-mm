<?php

namespace Tests\Feature;

use Tests\TestCase;

class CalendarControllerTest extends TestCase
{
    public function test_calendar_page_loads_with_defaults(): void
    {
        $response = $this->get('/calendar');

        $response->assertStatus(200);
        $response->assertViewHas('year');
        $response->assertViewHas('month');
        $response->assertViewHas('monthName');
        $response->assertViewHas('weeks');
        $response->assertViewHas('previousMonth');
        $response->assertViewHas('nextMonth');
    }

    public function test_calendar_page_loads_with_specific_month(): void
    {
        $response = $this->get('/calendar?year=2026&month=6');

        $response->assertStatus(200);
        $response->assertViewHas('year', 2026);
        $response->assertViewHas('month', 6);
        $response->assertViewHas('monthName', 'June');
    }

    public function test_calendar_page_shows_navigation_links(): void
    {
        $response = $this->get('/calendar?year=2026&month=3');

        $response->assertStatus(200);
        $response->assertSee('March 2026');
    }

    public function test_calendar_handles_invalid_month_gracefully(): void
    {
        $response = $this->get('/calendar?year=2026&month=13');

        $response->assertStatus(200);
    }
}
