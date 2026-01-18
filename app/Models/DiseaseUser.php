<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseUser extends Model
{
     use HasFactory;
    protected $table = 'disease_user';

    protected $fillable = [
        'user_id',
        'disease_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | العلاقات Relationships
    |--------------------------------------------------------------------------
    */

    // علاقة: المرض المستخدم ← المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // علاقة: المرض المستخدم ← المرض
    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}