<?php

namespace App\database\drivers;

/**
 * get instance of from choosen driver database.
 *
 */
class DriverInstance
{
    private static $instances = [];

    private const PREFIX_DRIVER = 'Driver';
    private const NAMESPACE_DRIVERS = 'App\\database\\drivers\\';

    public static function get(): DriverContract{
        // get choosen database from .env file.
        $choosenDriver = _env('DATABASE', 'mysql');

        if(! isset(self::$instances[$choosenDriver])){
            $driverClassName = self::NAMESPACE_DRIVERS . ucfirst($choosenDriver) . self::PREFIX_DRIVER;
            if(! class_exists($driverClassName))
                throw new \Exception("Driver {$choosenDriver} Not Exists");

            $newDriver = new $driverClassName();
            self::$instances[$choosenDriver] = $newDriver;
        }

        return self::$instances[$choosenDriver];
    }
}