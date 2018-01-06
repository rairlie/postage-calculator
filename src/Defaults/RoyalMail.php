<?php
return [
    'small' => [
        'maxDimensions' => [45, 35, 16],
        'priceBands' => [
            [
                'minWeight' => 0,
                'maxWeight' => 1000,
                'price' => 340,
            ],
            [
                'minWeight' => 1000,
                'maxWeight' => 2000,
                'price' => 550,
            ],
        ],
    ],
    'medium' => [
        'maxDimensions' => [61, 46, 46],
        'priceBands' => [
            [
                'minWeight' => 0,
                'maxWeight' => 1000,
                'price' => 570,
            ],
            [
                'minWeight' => 1000,
                'maxWeight' => 2000,
                'price' => 895,
            ],
            [
                'minWeight' => 2000,
                'maxWeight' => 5000,
                'price' => 1585,
            ],
            [
                'minWeight' => 5000,
                'maxWeight' => 10000,
                'price' => 2190,
            ],
            [
                'minWeight' => 10000,
                'maxWeight' => 20000,
                'price' => 3340,
            ],
        ],
    ]
];
