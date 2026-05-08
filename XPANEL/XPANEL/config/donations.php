<?php
return [

    'currency' => 'USD',
    'points' => '#DONAPOINTS',
    'packages' => [
        [
            'id' => 0,
            'name' => 'Regular Donation',
            'description' => 'Regular Donation on' . env('APP_NAME') . '',
            'extra_points' => 0,
            'points' => 5,
            'price' => 5,
        ],
        [
            'id' => 1,
            'name' => 'Rare Donation',
            'description' => 'Rare Donation on' . env('APP_NAME') . '',
            'extra_points' => 1,
            'points' => 15,
            'price' => 15,
        ],
        [
            'id' => 2,
            'name' => 'Special Donation',
            'description' => 'Special Donation on' . env('APP_NAME') . '',
            'extra_points' => 2,
            'points' => 25,
            'price' => 25,
        ],
        [
            'id' => 3,
            'name' => 'Deluxe Donation',
            'description' => 'Deluxe Donation on' . env('APP_NAME') . '',
            'extra_points' => 5,
            'points' => 50,
            'price' => 50,
        ],
        [
            'id' => 4,
            'name' => 'Epic Donation',
            'description' => 'Epic Donation on' . env('APP_NAME') . '',
            'extra_points' => 8,
            'points' => 80,
            'price' => 80,
        ],
        [
            'id' => 5,
            'name' => 'Legendary Donation',
            'description' => 'Legendary Donation on' . env('APP_NAME') . '',
            'extra_points' => 12,
            'points' => 120,
            'price' => 120,
        ],
    ]
];
