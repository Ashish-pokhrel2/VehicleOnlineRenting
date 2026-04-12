<?php

namespace App\Enums;

enum ContactStatus: string
{
    case PENDING = 'Pending';
    case REPLIED = 'Replied';
    case CLOSED = 'Closed';
}
