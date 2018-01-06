<?php
namespace Rairlie\PostageCalculator;

use Rairlie\PostageCalculator\Exceptions\ServiceUnavailableException;
use Rairlie\PostageCalculator\Exceptions\PriceNotFoundException;

class ParcelForce
{
    const METHOD_COLLECT = 'COLLECT';
    const METHOD_DROP_POST_OFFICE = 'DROP_POST_OFFICE';
    const METHOD_DROP_DEPOT = 'DROP_DEPOT';
    const METHOD_DEPOT_TO_DEPOT = 'DEPOT_TO_DEPOT';

    public function __construct(array $priceBands)
    {
        $this->priceBands = $priceBands;
    }

    public function getPrice($weight, $method)
    {
        $priceIndex = $this->getPriceIndex($method);

        foreach ($this->priceBands as $band) {
            $basePrice = $band['prices'][$priceIndex];

            if ($weight >= $band['minWeight'] && $band['maxWeight'] === null) {
                if ($basePrice === null) {
                    throw new ServiceUnavailableException();
                }
                $additionalWeightKg = ($weight - $band['minWeight']) / 1000;
                $additionalWeightKg = ceil($additionalWeightKg);

                return $basePrice + ($additionalWeightKg * $band['pricesAddPerKg'][$priceIndex]);
            } elseif ($weight >= $band['minWeight'] && $weight <= $band['maxWeight']) {
                if ($basePrice === null) {
                    throw new ServiceUnavailableException();
                }
                return $band['prices'][$priceIndex];
            }
        }

        throw new PriceNotFoundException("Could not find price for weight {$weight}g");
    }

    private function getPriceIndex($method)
    {
        $methodToPriceIndex = [
            self::METHOD_COLLECT => 0,
            self::METHOD_DROP_POST_OFFICE => 1,
            self::METHOD_DROP_DEPOT => 2,
            self::METHOD_DEPOT_TO_DEPOT => 3,
        ];

        return $methodToPriceIndex[$method];
    }
}
