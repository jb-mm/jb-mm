<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $monthName }} {{ $year }} - Calendar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
        {{-- Header with navigation --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('calendar', ['year' => $previousMonth['year'], 'month' => $previousMonth['month']]) }}"
               class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <h2 class="text-xl font-semibold text-gray-800">{{ $monthName }} {{ $year }}</h2>

            <a href="{{ route('calendar', ['year' => $nextMonth['year'], 'month' => $nextMonth['month']]) }}"
               class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Day-of-week headers --}}
        <div class="grid grid-cols-7 text-center text-sm font-medium text-gray-500 mb-2">
            <div>Sun</div>
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
        </div>

        {{-- Calendar days --}}
        @foreach ($weeks as $week)
            <div class="grid grid-cols-7 text-center">
                @foreach ($week as $cell)
                    @if ($cell['day'])
                        <div class="py-2 text-sm
                            {{ $cell['isToday'] ? 'bg-blue-600 text-white rounded-full font-bold' : 'text-gray-700 hover:bg-gray-50 rounded-full' }}">
                            {{ $cell['day'] }}
                        </div>
                    @else
                        <div class="py-2"></div>
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
</body>
</html>
