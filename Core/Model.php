<?php

namespace Core;

use App\Config;
use PDO;
use PDOException;

/**
 * Base model
 * 
 * PHP version 7.4
 */
abstract class Model
{

    /**
     * Get the PDO database connection
     * 
     * @return mixed
     */
    protected static function getDB()
    {
        static $pdo = null;

        if ($pdo === null) {

            $dsn = 'mysql:host=' . $_SESSION["db_servername"] . ';dbname=' . $_SESSION["db_name"] . ';charset=utf8';
            $pdo = new PDO($dsn, $_SESSION["db_username"], $_SESSION["db_password"]);
            //Throw an exception when an error occurs
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           
        }

        return $pdo;

    }
}