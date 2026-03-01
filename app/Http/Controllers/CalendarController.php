<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function __construct(
        protected CalendarService $calendarService,
    ) {}

    public function index(Request $request): View
    {
        $year = (int) $request->query('year', Carbon::today()->year);
        $month = (int) $request->query('month', Carbon::today()->month);

        // Clamp month to valid range
        if ($month < 1 || $month > 12) {
            $month = Carbon::today()->month;
        }

        $calendar = $this->calendarService->getMonthData($year, $month);

        return view('calendar', $calendar);
    }
}
