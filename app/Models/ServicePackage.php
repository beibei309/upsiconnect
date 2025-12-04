<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePackage extends Model
{
    use HasFactory;

    // Define the inverse of the relationship with student services
    public function studentService()
    {
        return $this->belongsTo(StudentService::class);
    }
}
