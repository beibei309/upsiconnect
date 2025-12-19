<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
use Illuminate\Database\Eloquent\Model;

class StudentStatus extends Model
{
<<<<<<< HEAD
=======
    use HasFactory;

    protected $table = 'student_statuses';

>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
    protected $fillable = [
        'student_id',
        'matric_no',
        'semester',
        'status',
<<<<<<< HEAD
        'effective_date'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
=======
        'effective_date',
        'graduation_date',
    ];

    // RELATIONSHIP (Make sure this appears only ONCE)
    public function student()
    {
        // This links 'student_id' in this table to the 'id' in the 'users' table
        return $this->belongsTo(User::class, 'student_id');
    }
}
>>>>>>> 6399068de6df6748517e7d5a89890a29e239f3f6
