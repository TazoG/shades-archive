<?php

// lang/en/inventory.php
declare(strict_types=1);

return [
    'movement_types' => [
        'initial' => 'Initial Stock',
        'sale' => 'Sale',
        'return' => 'Return',
        'manual_adjustment' => 'Manual Adjustment',
        'reservation' => 'Reservation',
    ],
    'exceptions' => [
        'insufficient_stock' => 'Product ":product" is no longer available in the requested quantity.',
    ],
];
