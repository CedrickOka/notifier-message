<?php

namespace Oka\Notifier\Enum;

/**
 * @author Cedrick Oka Baidai <okacedrick@gmail.com>
 */
enum NotificationPriority: string
{
    case High = 'high';
    case Low = 'low';
    case Normal = 'normal';
}
