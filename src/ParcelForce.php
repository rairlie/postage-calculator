<?php
namespace Rairlie\PostageCalculator;

use Rairlie\PostageCalculator\Exceptions\MethodUnavailableException;
use Rairlie\PostageCalculator\Exceptions\ParcelTooHeavyException;

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

    /**
     * Calculate the price for a parcel
     *
     * @param int    $weight  Weight of the parcel in grams
     * @param string $method  Method const (collect, drop post office, etc)
     * @return int            Price in pence
     *
     * @throws Rairlie\PostageCalculator\Exceptions\ParcelTooHeavyException
     * @throws Rairlie\PostageCalculator\Exceptions\MethodUnavailableException
     */
    public function getPrice(int $weight, string $method)
    {
        $priceIndex = $this->getPriceIndex($method);

        foreach ($this->priceBands as $band) {
            $basePrice = $band['prices'][$priceIndex];

            if ($weight >= $band['minWeight'] && $band['maxWeight'] === null) {
                // Price band has unbounded weight - use a price-per-kg method
                if ($basePrice === null) {
                    throw new MethodUnavailableException("Cant send {$weight}g package with $method");
                }
                $additionalWeightKg = ($weight - $band['minWeight']) / 1000;
                $additionalWeightKg = ceil($additionalWeightKg);

                return $basePrice + ($additionalWeightKg * $band['pricesAddPerKg'][$priceIndex]);
            } elseif ($weight >= $band['minWeight'] && $weight <= $band['maxWeight']) {
                if ($basePrice === null) {
                    throw new MethodUnavailableException("Cant send {$weight}g package with $method");
                }
                return $band['prices'][$priceIndex];
            }
        }

        // Will only get here if the config does not have unbounded max weight
        throw new ParcelTooHeavyException("Could not find a price for weight {$weight}g");
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
