<?php
/**
 * Array of price bands used for a RoyalMail service
 *
 * Price bands are grouped by parcel dimension.
 *
 * In each band we have:
 * 'minWeight': minimum weight for this price band
 * 'maxWeight': maximum weight for this price band
 * 'price': price for parcel falling within this range
 */
return [
    'small' => [
        'maxDimensions' => [45, 35, 16],
        'priceBands' => [
            [
                'minWeight' => 0,
                'maxWeight' => 1000,
                'price' => 370,
            ],
            [
                'minWeight' => 1000,
                'maxWeight' => 2000,
                'price' => 557,
            ],
        ],
    ],
    'medium' => [
        'maxDimensions' => [61, 46, 46],
        'priceBands' => [
            [
                'minWeight' => 0,
                'maxWeight' => 1000,
                'price' => 590,
            ],
            [
                'minWeight' => 1000,
                'maxWeight' => 2000,
                'price' => 902,
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
