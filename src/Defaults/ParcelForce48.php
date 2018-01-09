<?php
/**
 * Array of price bands used for a Parcelforce service
 *
 * For each price band we have:
 * 'minWeight': minimum weight for this price band
 * 'maxWeight': maximum weight for this price band
 * 'prices': array of:
 *     - price for ParcelForce::METHOD_COLLECT
 *     - price for ParcelForce::METHOD_DROP_POST_OFFICE
 *     - price for ParcelForce::METHOD_DROP_DEPOT
 *     - price for ParcelForce::METHOD_DEPOT_TO_DEPOT
 *
 * If a price is null it indicates the method is unavailable for that price band.
 *
 * If 'maxWeight' is null a price-per-kg value will be added to the base price.
 */
return [
    [
        'minWeight' => 0,
        'maxWeight' => 2000,
        'prices' => [
            1199, 1070, 1010, 480,
        ],
    ],
    [
        'minWeight' => 2000,
        'maxWeight' => 5000,
        'prices' => [
            1298, 1170, 1110, 580,
        ],
    ],
    [
        'minWeight' => 5000,
        'maxWeight' => 10000,
        'prices' => [
            1640, 1512, 1452, 922,
        ],
    ],
    [
        'minWeight' => 10000,
        'maxWeight' => 15000,
        'prices' => [
            2314, 2185, 2125, 1595,
        ],
    ],
    [
        'minWeight' => 15000,
        'maxWeight' => 20000,
        'prices' => [
            2851, 2723, 2663, 2132,
        ],
    ],
    [
        'minWeight' => 20000,
        'maxWeight' => 25000,
        'prices' => [
            3964, null, 3775, 3245,
        ],
    ],
    [
        'minWeight' => 25000,
        'maxWeight' => 30000,
        'prices' => [
            4378, null, 4189, 3659,
        ],
    ],
    [
        'minWeight' => 30000,
        'maxWeight' => null,
        'prices' => [
            4378, null, 4189, 3659,
        ],
        'pricesAddPerKg' => [
            150, null, 150, 150,
        ],
    ],
];
