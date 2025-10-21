<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_service_id',
        'requester_id',
        'provider_id',
        'message',
        'offered_price',
        'status',
        'accepted_at',
        'completed_at'
    ];

    protected $casts = [
        'offered_price' => 'decimal:2',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the student service this request is for
     */
    public function studentService()
    {
        return $this->belongsTo(StudentService::class);
    }

    /**
     * Get the community member who made the request
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Get the student who provides the service
     */
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    /**
     * Get reviews for this service request
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'service_request_id');
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for accepted requests
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope for completed requests
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Check if request is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is accepted
     */
    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if request is in progress
     */
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if request is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Accept the service request
     */
    public function accept()
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);
    }

    /**
     * Reject the service request
     */
    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    /**
     * Mark as in progress
     */
    public function markInProgress()
    {
        $this->update(['status' => 'in_progress']);
    }

    /**
     * Mark as completed
     */
    public function markCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'accepted' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            'in_progress' => 'bg-indigo-100 text-indigo-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-gray-100 text-gray-800'
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get formatted status
     */
    public function getFormattedStatusAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }
}