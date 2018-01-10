<?php
namespace Rairlie\PostageCalculator\Tests;

use PHPUnit\Framework\TestCase;
use Rairlie\PostageCalculator\PostageCalculator;
use Rairlie\PostageCalculator\ParcelForce;

class PostageCalculatorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->calc = new PostageCalculator();
    }

    public function testRoyalMail()
    {
        $this->assertEquals(
            340,
            $this->calc
                ->getService(PostageCalculator::SERVICE_ROYAL_MAIL)
                ->getPrice(1, [1, 1, 1])
        );
    }

    public function testParcelForce24()
    {
        $this->assertEquals(
            1649,
            $this->calc
                ->getService(PostageCalculator::SERVICE_PARCELFORCE_24)
                ->getPrice(1, ParcelForce::METHOD_COLLECT)
        );

        $this->assertEquals(
            1529,
            $this->calc
                ->getService(PostageCalculator::SERVICE_PARCELFORCE_24)
                ->getPrice(1, ParcelForce::METHOD_DROP_POST_OFFICE)
        );

        $this->assertEquals(
            1289,
            $this->calc
                ->getService(PostageCalculator::SERVICE_PARCELFORCE_24)
                ->getPrice(1, ParcelForce::METHOD_DEPOT_TO_DEPOT)
        );
    }

    public function testParcelForce48()
    {
        $this->assertEquals(
            1199,
            $this->calc
                ->getService(PostageCalculator::SERVICE_PARCELFORCE_48)
                ->getPrice(1, ParcelForce::METHOD_COLLECT)
        );

        $this->assertEquals(
            1070,
            $this->calc
                ->getService(PostageCalculator::SERVICE_PARCELFORCE_48)
                ->getPrice(1, ParcelForce::METHOD_DROP_POST_OFFICE)
        );

        $this->assertEquals(
            1010,
            $this->calc
                ->getService(PostageCalculator::SERVICE_PARCELFORCE_48)
                ->getPrice(1, ParcelForce::METHOD_DROP_DEPOT)
        );

        $this->assertEquals(
            480,
            $this->calc
                ->getService(PostageCalculator::SERVICE_PARCELFORCE_48)
                ->getPrice(1, ParcelForce::METHOD_DEPOT_TO_DEPOT)
        );
    }

    public function testSetCustomDefaults()
    {
        $calc = new PostageCalculator([
            PostageCalculator::SERVICE_PARCELFORCE_24 => [
                [
                    'minWeight' => 0,
                    'maxWeight' => 2000,
                    'prices' => [
                        100, 200, 300, 400,
                    ],
                ],
            ],
        ]);

        $this->assertEquals(
            100,
            $calc->getService(PostageCalculator::SERVICE_PARCELFORCE_24)
                ->getPrice(1, ParcelForce::METHOD_COLLECT)
        );
    }
}
