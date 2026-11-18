<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $fillable = [
        'employee_id',
        'user_id',
        'attendance_date',
        'checked_in_at',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'checked_in_at' => 'datetime:H:i',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}