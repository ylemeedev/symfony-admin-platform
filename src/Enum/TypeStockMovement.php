<?php

namespace App\Enum;

enum TypeStockMovement: string
{
    case in = 'IN';
    case out = 'OUT';
    case transfer = 'TRANSFER';
}
