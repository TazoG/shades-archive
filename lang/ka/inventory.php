<?php

// lang/ka/inventory.php
declare(strict_types=1);

return [
    'movement_types' => [
        'initial' => 'საწყისი მარაგი',
        'sale' => 'გაყიდვა',
        'return' => 'დაბრუნება',
        'manual_adjustment' => 'ხელით კორექტირება',
        'reservation' => 'დარეზერვება',
    ],
    'exceptions' => [
        'insufficient_stock' => 'პროდუქტი ":product" აღარ არის ხელმისაწვდომი მოთხოვნილი რაოდენობით.',
        'zero_delta' => 'მარაგის ცვლილება არ შეიძლება იყოს 0.',
        'one_of_one_limit' => '1-of-1 უნიკალური პროდუქტის მარაგი ვერ იქნება 1-ზე მეტი.',
        'missing_archive_status' => 'სისტემაში არ არის კონფიგურირებული არქივის სტატუსი.',
    ],
];
