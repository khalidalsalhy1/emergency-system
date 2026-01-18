<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthGuide extends Model
{
    use HasFactory;

    /**
     * اسم الجدول المرتبط بالنموذج.
     */
    protected $table = 'health_guides';

    /**
     * الأعمدة المسموح بالتعبئة الجماعية (Mass Assignment) فيها.
     */
    protected $fillable = [
        'title',
        'content',
        'category',
        'image',
    ];
}
