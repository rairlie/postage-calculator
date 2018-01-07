<?php
namespace Rairlie\PostageCalculator;

use Rairlie\PostageCalculator\Exceptions\ServiceUnavailableException;

class RoyalMail
{
    const PACKAGE_SMALL = 'small';
    const PACKAGE_MEDIUM = 'medium';

    public function __construct(array $parcelSizes)
    {
        $this->parcelSizes = $parcelSizes;
    }

    private function getParcelPriceBands($weight, $length, $width, $depth)
    {
        $dimSmall = $this->parcelSizes[self::PACKAGE_SMALL]['maxDimensions'];
        $dimMed = $this->parcelSizes[self::PACKAGE_MEDIUM]['maxDimensions'];

        if ($length <= $dimSmall[0] && $width <= $dimSmall[1] && $depth <= $dimSmall[2]) {
            $packageSize = self::PACKAGE_SMALL;
        } elseif ($length <= $dimMed[0] && $width <= $dimMed[1] && $depth <= $dimMed[2]) {
            $packageSize = self::PACKAGE_MEDIUM;
        } else {
            throw new ServiceUnavailableException("Cant deliver large dimension parcels ($length x $width x $depth) with Royal Mail");
        }

        if ($weight > $this->getMaxParcelWeight($packageSize)) {
            if ($packageSize == self::PACKAGE_SMALL &&
                $weight <= $this->getMaxParcelWeight(self::PACKAGE_MEDIUM)
            ) {
                // Exceeds max weight for this size - bump to next band
                $packageSize = self::PACKAGE_MEDIUM;
            } else {
                throw new ServiceUnavailableException("Cant deliver {$weight}g parcel with Royal Mail - exceeds max weight");
            }
        }

        return $this->parcelSizes[$packageSize]['priceBands'];
    }

    private function getMaxParcelWeight(string $packageSize)
    {
        return (end($this->parcelSizes[$packageSize]['priceBands']))['maxWeight'];
    }

    public function getPrice($weight, $dimensions)
    {
        foreach ($this->getParcelPriceBands($weight, $dimensions[0], $dimensions[1], $dimensions[2]) as $band) {
            if ($weight >= $band['minWeight'] && $weight <= $band['maxWeight']) {
                return $band['price'];
            }
        }

        // Should never get here
        throw new \LogicException("Did not find a price");
    }
}
