<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentStatus extends Model
{
    protected $fillable = [
        'student_id',
        'matric_no',
        'semester',
        'status',
        'effective_date'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
