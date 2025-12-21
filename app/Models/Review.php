<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'service_request_id',
        'service_application_id',
        'reviewer_id',
        'reviewee_id',
        'service_id', // <--- Aku tambah ni, pastikan column ni wujud kat DB
        'rating',
        'comment',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function serviceApplication()
    {
        return $this->belongsTo(ServiceApplication::class);
    }

    // 👇 Ini function service yang betul
    // PENTING: Pastikan nama column dalam database reviews ialah 'service_id'
    // Kalau dalam database nama dia 'student_service_id', tukar kat bawah ni.
    public function service()
    {
        return $this->belongsTo(StudentService::class, 'service_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }
}