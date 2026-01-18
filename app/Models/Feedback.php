<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = "feedbacks";
    protected $fillable = [
        'emergency_request_id',
        'user_id',
        'hospital_id',
        'rating',
        'comments',
    ];

    // علاقات مفيدة
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emergencyRequest()
    {
        return $this->belongsTo(EmergencyRequest::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
