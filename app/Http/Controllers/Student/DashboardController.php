<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Hour;
use App\Models\Report;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $studentId = Session::get('user_id');
        $student = Student::with('class', 'internship.internship')->find($studentId);

        $internship = $student->internship->internship ?? null;
        $totalRequired = $internship->total_hours_required ?? 0;

        $totalHours = Hour::where('student_id', $studentId)->sum('duration_hours');
        $hoursLeft = max($totalRequired - $totalHours, 0);

        $reportCount = Report::where('student_id', $studentId)->count();

        $approvedHours = Hour::where('student_id', $studentId)
            ->where('status', 'approved')
            ->sum('duration_hours');

        $pendingHours = Hour::where('student_id', $studentId)
            ->where('status', 'pending')
            ->sum('duration_hours');

        $rejectedHours = Hour::where('student_id', $studentId)
            ->where('status', 'rejected')
            ->sum('duration_hours');

        $missingHours = max($totalRequired - $approvedHours, 0);

        $weekData = [];
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];

        foreach ($days as $index => $day) {
            $dayOfWeek = $index + 2;
            $hours = DB::table('hours')
                ->where('student_id', $studentId)
                ->whereRaw('EXTRACT(WEEK FROM date) = EXTRACT(WEEK FROM CURRENT_DATE)')
                ->whereRaw('EXTRACT(DOW FROM date) = ?', [$dayOfWeek])
                ->sum('duration_hours');

            $weekData[$day] = $hours ?? 0;
        }

        return view('student.dashboard', compact(
            'student',
            'hoursLeft',
            'reportCount',
            'approvedHours',
            'pendingHours',
            'rejectedHours',
            'missingHours',
            'weekData'
        ));
    }
}
