<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'suggested_price',
        'price_range',
        'status',
        'is_active',
    ];

    protected $attributes = [
        'status' => 'available',
        'is_active' => true,
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get applications for this service
     */
    public function applications()
    {
        return $this->hasMany(ServiceApplication::class, 'service_id');
    }

    /**
     * Check if service is available
     */
    public function isAvailable()
    {
        return $this->status === 'available' && $this->is_active;
    }

    /**
     * Mark service as busy/unavailable
     */
    public function markAsBusy()
    {
        $this->update(['status' => 'busy']);
    }

    /**
     * Mark service as available again
     */
    public function markAsAvailable()
    {
        $this->update(['status' => 'available']);
    }
}