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
            1649, 1529, null, 1289,
        ],
    ],
    [
        'minWeight' => 2000,
        'maxWeight' => 5000,
        'prices' => [
            1748, 1628, null, 1388,
        ],
    ],
    [
        'minWeight' => 5000,
        'maxWeight' => 10000,
        'prices' => [
            2090, 1970, null, 1730,
        ],
    ],
    [
        'minWeight' => 10000,
        'maxWeight' => 15000,
        'prices' => [
            2764, 2644, null, 2404,
        ],
    ],
    [
        'minWeight' => 15000,
        'maxWeight' => 20000,
        'prices' => [
            3301, 3181, null, 2941,
        ],
    ],
    [
        'minWeight' => 20000,
        'maxWeight' => 25000,
        'prices' => [
            4414, null, null, 4054,
        ],
    ],
    [
        'minWeight' => 25000,
        'maxWeight' => 30000,
        'prices' => [
            4828, null, null, 4468,
        ],
    ],
    [
        'minWeight' => 30000,
        'maxWeight' => null,
        'prices' => [
            4828, null, null, 4468,
        ],
        'pricesAddPerKg' => [
            150, null, null, 150,
        ],
    ],
];
