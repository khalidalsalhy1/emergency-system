<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{

     use HasFactory;
    // اسم الجدول (اختياري لأن لارافل يستنتجه تلقائياً)
    protected $table = 'diseases';

    // الأعمدة التي يسمح بملؤها
    protected $fillable = [
        'disease_name',
        'description',
    ];

    /**
     * علاقة Many-to-Many مع المستخدمين
     * مرض واحد يمكن أن يمتلكه عدة مرضى
     * ومريض واحد يمكن أن يمتلك عدة أمراض
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'disease_user', 'disease_id', 'user_id')
                    ->withTimestamps();
    }
}