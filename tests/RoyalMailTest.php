<?php
namespace Rairlie\PostageCalculator\Tests;

use PHPUnit\Framework\TestCase;
use Rairlie\PostageCalculator\RoyalMail;

class RoyalMailTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->royalMail = new RoyalMail(
            [
                'small' => [
                    'maxDimensions' => [45, 35, 16],
                    'priceBands' => [
                        [
                            'minWeight' => 0,
                            'maxWeight' => 1000,
                            'price' => 100,
                        ],
                        [
                            'minWeight' => 1000,
                            'maxWeight' => 2000,
                            'price' => 200,
                        ],
                    ],
                ],
                'medium' => [
                    'maxDimensions' => [61, 46, 46],
                    'priceBands' => [
                        [
                            'minWeight' => 0,
                            'maxWeight' => 1000,
                            'price' => 300,
                        ],
                        [
                            'minWeight' => 1000,
                            'maxWeight' => 2000,
                            'price' => 400,
                        ],
                        [
                            'minWeight' => 1000,
                            'maxWeight' => 20000,
                            'price' => 500,
                        ],
                    ],
                ]
            ]
        );
    }

    public function testGetPriceForSmallPackageUpTo1Kg()
    {
        $rm = $this->royalMail;

        $this->assertEquals(100, $rm->getPrice(1, [1, 1, 1]));
        $this->assertEquals(100, $rm->getPrice(1000, [1, 1, 1]));

        $this->assertEquals(100, $rm->getPrice(1, [45, 35, 16]));
        $this->assertEquals(100, $rm->getPrice(1000, [45, 35, 16]));
    }

    public function testGetPriceForSmallPackageUpTo2Kg()
    {
        $rm = $this->royalMail;

        $this->assertEquals(200, $rm->getPrice(1001, [1, 1, 1]));
        $this->assertEquals(200, $rm->getPrice(2000, [1, 1, 1]));

        $this->assertEquals(200, $rm->getPrice(1001, [45, 35, 16]));
        $this->assertEquals(200, $rm->getPrice(2000, [45, 35, 16]));
    }

    public function testGetPriceForMediumPackageInFirstPriceBand()
    {
        $rm = $this->royalMail;

        // Check we use medium pricing when any dimension exceeds limit
        $this->assertEquals(300, $rm->getPrice(1, [46, 35, 16]));
        $this->assertEquals(300, $rm->getPrice(1, [45, 36, 16]));
        $this->assertEquals(300, $rm->getPrice(1, [45, 35, 17]));

        $this->assertEquals(300, $rm->getPrice(1000, [46, 35, 16]));
        $this->assertEquals(300, $rm->getPrice(1000, [45, 36, 16]));
        $this->assertEquals(300, $rm->getPrice(1000, [45, 35, 17]));
    }

    public function testGetPriceForMediumPackageInSecondPriceBand()
    {
        $rm = $this->royalMail;

        // Check we use medium pricing when any dimension exceeds limit
        $this->assertEquals(400, $rm->getPrice(1001, [46, 35, 16]));
        $this->assertEquals(400, $rm->getPrice(1001, [45, 36, 16]));
        $this->assertEquals(400, $rm->getPrice(1001, [45, 35, 17]));

        $this->assertEquals(400, $rm->getPrice(2000, [46, 35, 16]));
        $this->assertEquals(400, $rm->getPrice(2000, [45, 36, 16]));
        $this->assertEquals(400, $rm->getPrice(2000, [45, 35, 17]));
    }

    /**
     * @expectedException Rairlie\PostageCalculator\Exceptions\ServiceUnavailableException
     * @expectedExceptionMessage Cant deliver 20001g parcel with Royal Mail - exceeds max weight
     */
    public function testItThrowsExceptionIfExeedsMaxWeight()
    {
        $this->royalMail->getPrice(20001, [1, 1, 1]);
    }

    /**
     * @expectedException Rairlie\PostageCalculator\Exceptions\ServiceUnavailableException
     * @expectedExceptionMessage Cant deliver large dimension parcels (62 x 46 x 46) with Royal Mail
     */
    public function testItThrowsExceptionIfExeedsMaxDimensions()
    {
        $this->royalMail->getPrice(1, [62, 46, 46]);
    }
}
