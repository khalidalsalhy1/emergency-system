<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InjuryType extends Model
{
    use HasFactory;

    protected $fillable = [
        'injury_name',
        'description',
    ];

    public function emergencyRequests()
    {
        return $this->hasMany(EmergencyRequest::class);
    }
}