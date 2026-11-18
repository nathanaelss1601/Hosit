<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_code',
        'name',
        'position',
        'phone',
        'hire_date',
        'daily_salary',
        'attendance_count',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'daily_salary' => 'integer',
        'attendance_count' => 'integer',
    ];

    public function markAttendance(): int
    {
        return $this->increment('attendance_count');
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }
}