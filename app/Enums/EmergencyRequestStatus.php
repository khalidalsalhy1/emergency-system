<?php

namespace App\Enums;

final class EmergencyRequestStatus
{
    public const PENDING = 'pending';
    public const CANCELED = 'canceled'; // 🚨 تم التعديل إلى CANCELED
    public const IN_PROGRESS = 'in_progress'; 
    public const COMPLETED = 'completed';

    public const ALL_STATUSES = [
        self::PENDING,
        self::CANCELED, // 🚨 تم استخدام CANCELED
        self::IN_PROGRESS,
        self::COMPLETED,
    ];

    public const VALID_TRANSITIONS = [
        // 🚨 الانتقال من PENDING إلى CANCELED أصبح مسموحاً
        self::PENDING => [self::IN_PROGRESS, self::CANCELED], 
        
        self::CANCELED => [], // لا يمكن التغيير بعد الإلغاء
        self::IN_PROGRESS => [self::COMPLETED, self::CANCELED],  // يمكن إلغاؤها أثناء المعالجة
        self::COMPLETED => [],                                  
    ];
}
