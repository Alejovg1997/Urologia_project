<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;

class AvailabilityController extends Controller
{
    protected function normalizeDayString(string $s)
    {
        $s = mb_strtolower($s);
        $s = str_replace(['á','é','í','ó','ú','ü','ñ'], ['a','e','i','o','u','u','n'], $s);
        $s = trim($s);
        return $s;
    }

    protected function dayNameToNumber(string $name)
    {
        $n = $this->normalizeDayString($name);
        $map = [
            'domingo' => 0,
            'lunes' => 1,
            'martes' => 2,
            'miercoles' => 3,
            'mierc' => 3,
            'jueves' => 4,
            'viernes' => 5,
            'sabado' => 6,
            'sabado' => 6,
        ];

        return $map[$n] ?? null;
    }

    public function index()
    {
        $doctors = Doctor::all(['id','name','specialty','slug']);

        return Inertia::render('Public/DoctorAvailability', [
            'doctors' => $doctors,
        ]);
    }

    /**
     * Return JSON availability slots for a given doctor and week start date.
     * Accepts `start` param as YYYY-MM-DD for the week's Monday (optional).
     */
    public function week(Request $request, Doctor $doctor)
    {
        $startParam = $request->input('start');
        if ($startParam) {
            try {
                $weekStart = Carbon::parse($startParam)->startOfWeek(Carbon::MONDAY)->startOfDay();
            } catch (\Throwable $e) {
                $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->startOfDay();
            }
        } else {
            $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->startOfDay();
        }

        $duration = (int) env('APPOINTMENT_DURATION_MINUTES', 20);

        $availabilities = $doctor->availabilities()->get();

        // group availabilities by day number
        $byDay = [];
        foreach ($availabilities as $a) {
            $dayNum = $this->dayNameToNumber($a->day_of_week);
            if ($dayNum === null) continue;
            $byDay[$dayNum][] = $a;
        }

        // load appointments for the whole week (pending/confirmadas)
        $weekEnd = (clone $weekStart)->addDays(6)->endOfDay();
        $appointments = $doctor->appointments()
            ->whereIn('status', ['pendiente','confirmada'])
            ->where(function($q) use ($weekStart, $weekEnd) {
                $q->whereBetween('start_time', [$weekStart, $weekEnd])
                  ->orWhereBetween('end_time', [$weekStart, $weekEnd]);
            })
            ->get();

        // Normalize appointments to integer timestamps to avoid timezone comparison bugs
        $appointmentsArr = $appointments->map(function($ap) {
            $s = Carbon::parse($ap->start_time)->timestamp;
            $e = Carbon::parse($ap->end_time)->timestamp;
            return [
                'start' => $s,
                'end' => $e,
            ];
        })->toArray();

        // Debug helper: include appointments details when ?debug=1 is present
        $appointmentsDebug = $appointments->map(function($ap) {
            return [
                'id' => $ap->id,
                'status' => $ap->status,
                'start' => Carbon::parse($ap->start_time)->toDateTimeString(),
                'end' => Carbon::parse($ap->end_time)->toDateTimeString(),
                'start_ts' => Carbon::parse($ap->start_time)->timestamp,
                'end_ts' => Carbon::parse($ap->end_time)->timestamp,
            ];
        })->toArray();

        $result = [];

        for ($d = 0; $d < 7; $d++) {
            $date = (clone $weekStart)->addDays($d);
            $dayNum = $date->dayOfWeek; // 0 (Sun) - 6 (Sat)
            $slots = [];

            if (!isset($byDay[$dayNum])) {
                $result[] = ['date' => $date->toDateString(), 'slots' => []];
                continue;
            }

            foreach ($byDay[$dayNum] as $avail) {
                $startTime = Carbon::parse($date->toDateString() . ' ' . $avail->start_time);
                $endTime = Carbon::parse($date->toDateString() . ' ' . $avail->end_time);

                $slotStart = $startTime->copy();
                while ($slotStart->addMinutes(0) && $slotStart->lt($endTime)) {
                    $slotEnd = $slotStart->copy()->addMinutes($duration);
                    if ($slotEnd->gt($endTime)) break;

                        // check overlap using integer timestamps (safer across timezones)
                        $slotStartTs = $slotStart->timestamp;
                        $slotEndTs = $slotEnd->timestamp;
                        $overlap = false;
                        foreach ($appointmentsArr as $ap) {
                            if ($slotStartTs < $ap['end'] && $slotEndTs > $ap['start']) {
                                $overlap = true; break;
                            }
                        }

                        if (! $overlap) {
                            $slots[] = [
                                'start' => $slotStart->toIso8601String(),
                                'end' => $slotEnd->toIso8601String(),
                            ];
                        }

                    $slotStart = $slotStart->copy()->addMinutes($duration);
                }
            }

            // sort slots by start
            usort($slots, function($a,$b){ return strcmp($a['start'],$b['start']); });

            $result[] = ['date' => $date->toDateString(), 'slots' => $slots];
        }

        $response = [
            'doctor' => ['id' => $doctor->id, 'name' => $doctor->name, 'specialty' => $doctor->specialty],
            'week_start' => $weekStart->toDateString(),
            'week_end' => $weekEnd->toDateString(),
            'slots_by_day' => $result,
        ];

        if ($request->boolean('debug')) {
            $response['appointments_loaded'] = $appointmentsDebug;
        }

        return response()->json($response);
    }
}
