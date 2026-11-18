<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::withCount('attendanceLogs')
            ->orderBy('name')
            ->get();

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'phone' => 'nullable|string',
            'hire_date' => 'required|date',
            'daily_salary' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        // Auto-generate employee code
        $count = Employee::count() + 1;
        $validated['employee_code'] = 'HH' . str_pad($count, 3, '0', STR_PAD_LEFT);

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $employee = Employee::with(['attendanceLogs.user'])
            ->findOrFail($id);

        return view('employees.show', compact('employee'));
    }

    public function attendanceLog(Request $request)
    {
        $employees = Employee::orderBy('name')->get();

        $attendanceLogs = AttendanceLog::with(['employee', 'user'])
            ->latest('attendance_date')
            ->latest('id')
            ->paginate(15);

        return view('employees.attendance-log', compact('employees', 'attendanceLogs'));
    }

    public function storeAttendance(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'checked_in_at' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
        ]);

        $alreadyLogged = AttendanceLog::where('employee_id', $validated['employee_id'])
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->exists();

        if ($alreadyLogged) {
            return back()->withErrors([
                'attendance_date' => 'Attendance for this employee on that date has already been recorded.',
            ])->withInput();
        }

        AttendanceLog::create([
            'employee_id' => $validated['employee_id'],
            'user_id' => $request->user()->id,
            'attendance_date' => $validated['attendance_date'],
            'checked_in_at' => $validated['checked_in_at'] ?? now()->format('H:i'),
            'notes' => $validated['notes'] ?? null,
        ]);

        Employee::findOrFail($validated['employee_id'])->markAttendance();

        return redirect()->route('employees.attendance-log')
            ->with('success', 'Attendance has been recorded.');
    }
}