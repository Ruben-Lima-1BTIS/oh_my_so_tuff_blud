<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Hour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class HoursController extends Controller
{
    public function index()
    {
        $studentId = Session::get('user_id');
        $student = Student::with('internship.internship')->find($studentId);

        $internship = $student->internship->internship ?? null;

        if (!$internship) {
            return redirect()->route('student.dashboard')
                ->withErrors(['error' => 'No active internship assigned.']);
        }

        $entries = Hour::where('student_id', $studentId)
            ->where('internship_id', $internship->id)
            ->orderBy('date', 'desc')
            ->limit(20)
            ->get();

        return view('student.log-hours', compact('student', 'internship', 'entries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $studentId = Session::get('user_id');
        $student = Student::with('internship.internship')->find($studentId);
        $internship = $student->internship->internship ?? null;

        if (!$internship) {
            return response()->json(['success' => false, 'error' => 'No active internship assigned.']);
        }

        $exists = Hour::where('student_id', $studentId)
            ->where('internship_id', $internship->id)
            ->where('date', $request->date)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'error' => 'You have already logged hours for this date.']);
        }

        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek;

        if ($dayOfWeek == 0 || $dayOfWeek == 6) {
            return response()->json(['success' => false, 'error' => 'Cannot log hours on weekends.']);
        }

        if ($date->isFuture()) {
            return response()->json(['success' => false, 'error' => 'Cannot log future dates.']);
        }

        $startTime = Carbon::parse($request->start_time);
        $endTime = Carbon::parse($request->end_time);

        if ($startTime->greaterThanOrEqualTo($endTime)) {
            return response()->json(['success' => false, 'error' => 'Start time must be before end time.']);
        }

        $rawMinutes = $endTime->diffInMinutes($startTime);
        $durationHours = round(($rawMinutes - $internship->lunch_break_minutes) / 60 * 2) / 2;

        if ($durationHours <= 0) {
            return response()->json(['success' => false, 'error' => 'Duration after lunch must be positive.']);
        }

        Hour::create([
            'student_id' => $studentId,
            'internship_id' => $internship->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_hours' => $durationHours,
            'status' => 'pending',
        ]);

        return response()->json(['success' => true]);
    }
}
