<?php
namespace Rairlie\PostageCalculator;

class PostageCalculator
{
    const SERVICE_ROYAL_MAIL = 'ROYAL_MAIL';
    const SERVICE_PARCELFORCE_24 = 'PARCELFORCE24';
    const SERVICE_PARCELFORCE_48 = 'PARCELFORCE48';

    public function __construct($defaults = [])
    {
        if ($defaults) {
            $this->defaults = $defaults;
        } else {
            $this->defaults = [
                self::SERVICE_ROYAL_MAIL => include __DIR__ . '/Defaults/RoyalMail.php',
                self::SERVICE_PARCELFORCE_24 => include __DIR__ . '/Defaults/ParcelForce24.php',
                self::SERVICE_PARCELFORCE_48 => include __DIR__ . '/Defaults/ParcelForce48.php',
            ];
        }
    }

    public function getService($service)
    {
        switch ($service) {
            case self::SERVICE_ROYAL_MAIL:
                return new RoyalMail($this->defaults[$service]);

            case self::SERVICE_PARCELFORCE_24:
            case self::SERVICE_PARCELFORCE_48:
                return new ParcelForce($this->defaults[$service]);
        }
    }
}
