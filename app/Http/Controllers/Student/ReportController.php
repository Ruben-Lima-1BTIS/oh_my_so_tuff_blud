<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $studentId = Session::get('user_id');
        $student = Student::with('internship.internship')->find($studentId);

        $internship = $student->internship->internship ?? null;

        $weeks = [];
        if ($internship) {
            $start = Carbon::parse($internship->start_date);
            $end = Carbon::parse($internship->end_date);
            $periodStart = clone $start;
            $weekNum = 1;

            while ($periodStart->lessThanOrEqualTo($end)) {
                $periodEnd = (clone $periodStart)->addDays(6);
                if ($periodEnd->greaterThan($end)) {
                    $periodEnd = clone $end;
                }

                $weeks[$weekNum] = sprintf(
                    "Week %d (%s — %s)",
                    $weekNum,
                    $periodStart->format('Y-m-d'),
                    $periodEnd->format('Y-m-d')
                );

                $weekNum++;
                $periodStart->addDays(7);
            }
        }

        $recentReports = Report::where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('student.submit-reports', compact('student', 'weeks', 'recentReports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'week' => 'required|integer',
            'reportFile' => 'required|file|max:10240|mimes:pdf,doc,docx,txt',
        ]);

        $studentId = Session::get('user_id');
        $file = $request->file('reportFile');

        $path = $file->store('reports/' . $studentId, 'public');

        Report::create([
            'student_id' => $studentId,
            'title' => "Week {$request->week} - {$file->getClientOriginalName()}",
            'file_path' => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Report submitted successfully.');
    }
}
