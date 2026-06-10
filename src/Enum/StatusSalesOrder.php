<?php

namespace App\Enum;

enum StatusSalesOrder: string
{
    case DRAFT = 'draft';
    case CONFIRMED = 'confirmed';
    case SHIPPED = 'shipped';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';
}
