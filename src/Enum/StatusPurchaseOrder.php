<?php

namespace App\Enum;

enum StatusPurchaseOrder: string
{
    case Draft = 'Draft';
    case Sent = 'Sent';
    case Received = 'Received';
}
