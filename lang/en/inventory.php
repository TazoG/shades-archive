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
        'zero_delta' => 'Stock adjustment delta cannot be zero.',
        'one_of_one_limit' => 'A 1-of-1 archive piece cannot have stock greater than 1.',
        'missing_archive_status' => 'No active archive status (is_archived) is configured in the system.',
    ],
];
