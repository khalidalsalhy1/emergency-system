<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
     use HasFactory;
    protected $table = 'notifications';
    
    // 🚨🚨 التعديل الحاسم: إضافة جميع الحقول الإضافية 🚨🚨
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        
      
        
    ];

    /*
    |--------------------------------------------------------------------------
    | العلاقات Relationships
    |--------------------------------------------------------------------------
    */

    // كل إشعار يخص مستخدم معيّن
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | دوال مساعدة Helper Methods
    |--------------------------------------------------------------------------
    */

    // هل الإشعار مقروء؟
    public function isRead()
    {
        return $this->is_read === true;
    }

    // هل الإشعار غير مقروء؟
    public function isUnread()
    {
        return $this->is_read === false;
    }

    // وضع الإشعار كمقروء
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    // وضع الإشعار كغير مقروء
    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
    }
}
