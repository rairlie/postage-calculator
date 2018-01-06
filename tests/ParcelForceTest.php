<?php
namespace Rairlie\PostageCalculator\Tests;

use PHPUnit\Framework\TestCase;
use Rairlie\PostageCalculator\ParcelForce;

class ParcelForceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->parcelForce = new ParcelForce([
            [
                'minWeight' => 0,
                'maxWeight' => 2000,
                'prices' => [
                    100, 200, 300, 400,
                ],
            ],
            [
                'minWeight' => 2000,
                'maxWeight' => 5000,
                'prices' => [
                    1000, 2000, 3000, 4000,
                ],
            ],
            [
                'minWeight' => 5000,
                'maxWeight' => 10000,
                'prices' => [
                    10000, null, 30000, 40000,
                ],
            ],
            [
                'minWeight' => 10000,
                'maxWeight' => null,
                'prices' => [
                    100000, null, 300000, 400000,
                ],
                'pricesAddPerKg' => [
                    100, null, 300, 400,
                ],
            ],
        ]);
    }

    private function getCollectPrice($weight)
    {
        return $this->parcelForce->getPrice($weight, ParcelForce::METHOD_COLLECT);
    }

    private function getDropPOPrice($weight)
    {
        return $this->parcelForce->getPrice($weight, ParcelForce::METHOD_DROP_POST_OFFICE);
    }

    private function getDropDepotPrice($weight)
    {
        return $this->parcelForce->getPrice($weight, ParcelForce::METHOD_DROP_DEPOT);
    }

    private function getDepotToDepotPrice($weight)
    {
        return $this->parcelForce->getPrice($weight, ParcelForce::METHOD_DEPOT_TO_DEPOT);
    }

    public function testItUsesFirstBandPricesWhenWeightInRange()
    {
        $this->assertEquals(100, $this->getCollectPrice(1));
        $this->assertEquals(200, $this->getDropPOPrice(1));
        $this->assertEquals(300, $this->getDropDepotPrice(1));
        $this->assertEquals(400, $this->getDepotToDepotPrice(1));

        $this->assertEquals(100, $this->getCollectPrice(2000));
        $this->assertEquals(200, $this->getDropPOPrice(2000));
        $this->assertEquals(300, $this->getDropDepotPrice(2000));
        $this->assertEquals(400, $this->getDepotToDepotPrice(2000));
    }

    public function testItUsesSecondBandPricingWhenWeightInRange()
    {
        $this->assertEquals(1000, $this->getCollectPrice(2001));
        $this->assertEquals(2000, $this->getDropPOPrice(2001));
        $this->assertEquals(3000, $this->getDropDepotPrice(2001));
        $this->assertEquals(4000, $this->getDepotToDepotPrice(2001));

        $this->assertEquals(1000, $this->getCollectPrice(5000));
        $this->assertEquals(2000, $this->getDropPOPrice(5000));
        $this->assertEquals(3000, $this->getDropDepotPrice(5000));
        $this->assertEquals(4000, $this->getDepotToDepotPrice(5000));
    }

    public function testItUsesThirdBandPricingWhenWeightInRange()
    {
        $this->assertEquals(10000, $this->getCollectPrice(5001));
        $this->assertEquals(30000, $this->getDropDepotPrice(5001));
        $this->assertEquals(40000, $this->getDepotToDepotPrice(5001));

        $this->assertEquals(10000, $this->getCollectPrice(10000));
        $this->assertEquals(30000, $this->getDropDepotPrice(10000));
        $this->assertEquals(40000, $this->getDepotToDepotPrice(10000));
    }

    /**
     * @expectedException Rairlie\PostageCalculator\Exceptions\ServiceUnavailableException
     */
    public function testItThrowsAnExceptionWhenServiceNotAvailable()
    {
        $this->assertNull($this->getDropPOPrice(5001));
    }

    public function testItUsesPerKgPricingWhenNoMaxWeight()
    {
        $this->assertEquals(100000 + (100 * 1), $this->getCollectPrice(10000 + 1));
        $this->assertEquals(300000 + (300 * 1), $this->getDropDepotPrice(10000 + 1));
        $this->assertEquals(400000 + (400 * 1), $this->getDepotToDepotPrice(10000 + 1));

        $this->assertEquals(100000 + (100 * 1), $this->getCollectPrice(10000 + 1000));
        $this->assertEquals(300000 + (300 * 1), $this->getDropDepotPrice(10000 + 1000));
        $this->assertEquals(400000 + (400 * 1), $this->getDepotToDepotPrice(10000 + 1000));

        $this->assertEquals(100000 + (100 * 2), $this->getCollectPrice(10000 + 1001));
        $this->assertEquals(300000 + (300 * 2), $this->getDropDepotPrice(10000 + 1001));
        $this->assertEquals(400000 + (400 * 2), $this->getDepotToDepotPrice(10000 + 1001));

        $this->assertEquals(100000 + (100 * 10), $this->getCollectPrice(10000 + 10000));
        $this->assertEquals(300000 + (300 * 10), $this->getDropDepotPrice(10000 + 10000));
        $this->assertEquals(400000 + (400 * 10), $this->getDepotToDepotPrice(10000 + 10000));
    }

    /**
     * @expectedException Rairlie\PostageCalculator\Exceptions\ServiceUnavailableException
     */
    public function testItThrowsAnExceptionWhenServiceNotAvailableWhenNoMaxWeight()
    {
        $this->assertNull($this->getDropPOPrice(10001));
    }

    /**
     * @expectedException Rairlie\PostageCalculator\Exceptions\PriceNotFoundException
     * @expectedExceptionMessage Could not find price for weight 2001g
     */
    public function testItThrowsAnExceptionWhenPriceNotFound()
    {
        $parcelForce = new ParcelForce([
            [
                'minWeight' => 0,
                'maxWeight' => 2000,
                'prices' => [
                    100, 200, 300, 400,
                ],
            ]
        ]);

        $parcelForce->getPrice(2001, ParcelForce::METHOD_COLLECT);
    }
}
