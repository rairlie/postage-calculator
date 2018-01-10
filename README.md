# postage-calculator [![Build Status](https://travis-ci.org/rairlie/postage-calculator.svg?branch=master)](https://travis-ci.org/rairlie/postage-calculator) [![Coverage Status](https://coveralls.io/repos/github/rairlie/postage-calculator/badge.svg?branch=master)](https://coveralls.io/github/rairlie/postage-calculator?branch=master)
Calculates price for UK postal services

## Requires
PHP 7.0+

## Installation
    $ composer require rairlie/postage-calculator
    
## Usage
    use Rairlie\PostageCalculator\PostageCalculator;
    use Rairlie\PostageCalculator\ParcelForce;
    
    // Calculate postage for a Royal Mail parcel
    $postageCalculator = new PostageCalculator();
    $price = $postageCalculator
        ->getService(PostageCalculator::SERVICE_ROYAL_MAIL)
        ->getPrice(
            450, // Weight in grams
            [10, 22, 8] // Dimensions in cm - length, width, depth
        );
        
    // Calculate postage for a Parcelforce parcel
    $price = $postageCalculator
        ->getService(PostageCalculator::SERVICE_PARCELFORCE_24)
        ->getPrice(
            450, // Weight in grams
            ParcelForce::METHOD_DROP_POST_OFFICE
        );
        
Parcelforce parcels can be sent by four methods:

* ParcelForce::METHOD_COLLECT - Parcel to be collected from a pick-up address
* ParcelForce::METHOD_DROP_POST_OFFICE - Parcel to be dropped at a PostOffice
* ParcelForce::METHOD_DROP_DEPOT - Parcel to be dropped at a depot
* ParcelForce::METHOD_DEPOT_TO_DEPOT - Parcel to be dropped at and collected from a depot

## Configuration
The prices are derived from configuration files in **src/Defaults:**

    src/Defaults/RoyalMail.php
    src/Defaults/ParcelForce24.php
    src/Defaults/ParcelForce48.php

The format should be fairly self-explanatory, and prices are correct as of January 2018. If you need to customise them, for example to load them from a DB or to facilitate unit testing, you can pass your own price configs into the constructor:

    $postageCalculator = new PostageCalculator([
        PostageCalculator::SERVICE_ROYAL_MAIL => $myRoyalMailPrices,
        PostageCalculator::SERVICE_PARCELFORCE_24 => $myParcelForce24Prices,
        PostageCalculator::SERVICE_PARCELFORCE_48 => $myParcelForce48Prices,
    ]);
        
## Exceptions
The following exceptions may be thrown:
* ParcelTooHeavyException - the parcel is too heavy to send with the service
* ParcelTooLargeException - the parcel is too large (dimensions) to send with the service
* MethodUnavailableException - its not possible to use the specified delivery method to send this parcel (Parcelforce only)
