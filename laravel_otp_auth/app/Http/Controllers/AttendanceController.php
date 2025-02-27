<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Show attendance marking page
    public function create()
    {
        $students = Student::all();
        return view('admin.attendances.create', compact('students'));
    }

    // Store attendance records
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent',
            'attendances.*.reason' => 'nullable|string',
        ]);

        foreach ($request->attendances as $attendanceData) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $attendanceData['student_id'],
                    'date' => $request->date,
                ],
                [
                    'status' => $attendanceData['status'],
                    'reason' => $attendanceData['reason'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.students.index')->with('success', 'Attendance marked successfully.');
    }

    // Show attendance reports
    public function reports(Request $request)
    {
        $reportType = $request->query('report_type', 'daily');
        $date = $request->query('date', now()->format('Y-m-d'));

        $query = Attendance::with('student');

        switch ($reportType) {
            case 'daily':
                $query->whereDate('date', $date);
                break;
            case 'weekly':
                $query->whereBetween('date', [
                    Carbon::parse($date)->startOfWeek(),
                    Carbon::parse($date)->endOfWeek(),
                ]);
                break;
            case 'monthly':
                $query->whereYear('date', Carbon::parse($date)->year)
                      ->whereMonth('date', Carbon::parse($date)->month);
                break;
        }

        $attendances = $query->get();

        return view('admin.attendances.reports', compact('attendances', 'reportType', 'date'));
    }
}
