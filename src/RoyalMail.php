<?php
namespace Rairlie\PostageCalculator;

use Rairlie\PostageCalculator\Exceptions\ServiceUnavailableException;

class RoyalMail
{
    public function __construct(array $parcelSizes)
    {
        $this->parcelSizes = $parcelSizes;
    }

    private function getParcelPriceBands($length, $width, $depth)
    {
        $dimSmall = $this->parcelSizes['small']['maxDimensions'];
        $dimMed = $this->parcelSizes['medium']['maxDimensions'];

        if ($length <= $dimSmall[0] && $width <= $dimSmall[1] && $depth <= $dimSmall[2]) {
            return $this->parcelSizes['small']['priceBands'];
        } elseif ($length <= $dimMed[0] && $width <= $dimMed[1] && $depth <= $dimMed[2]) {
            return $this->parcelSizes['medium']['priceBands'];
        }

        throw new ServiceUnavailableException("Cant deliver large dimension parcels ($length x $width x $depth) with Royal Mail");
    }

    private function getMaxParcelWeight()
    {
        return (end($this->parcelSizes['medium']['priceBands']))['maxWeight'];
    }

    public function getPrice($weight, $dimensions)
    {
        if ($weight > $this->getMaxParcelWeight()) {
            throw new ServiceUnavailableException("Cant deliver {$weight}g parcel with Royal Mail - exceeds max weight");
        }

        foreach ($this->getParcelPriceBands($dimensions[0], $dimensions[1], $dimensions[2]) as $band) {
            if ($weight >= $band['minWeight'] && $weight <= $band['maxWeight']) {
                return $band['price'];
            }
        }

        // Should never get here
        throw new \LogicException("Did not find a price");
    }
}
