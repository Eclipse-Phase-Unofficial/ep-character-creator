<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 5/30/18
 * Time: 10:31 PM
 */

use App\Creator\Database;
use \App\Creator\EPCharacterCreator;

/**
 * A globally available helper function for accessing the creator
 * @return EPCharacterCreator|null
 */
function creator(): ?EPCharacterCreator
{
    return session('cc') ?? null;
}

/**
 * Get the location of the configuration file
 * TO Override this, set the 'EPCC_CONFIG_PATH' environment variable to the location of whatever config file you want.
 *
 * @return string
 */
function getConfigLocation(): string
{
    // Allow file path to be overridden by environment. (For some docker builds)
    if(isset($_ENV['EPCC_CONFIG_PATH'])) {
        return $_ENV['EPCC_CONFIG_PATH'];
    }
    return __DIR__ . '/../../config.ini';
}

/**
 * Returns a singleton of the Database class
 * @return Database
 */
function EpDatabase(): Database
{
    if(empty($_SESSION['db']))
    {
        session()->put('db', new Database());
    }
    return session('db');
}