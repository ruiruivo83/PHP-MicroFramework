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

            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $pdo = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
            //Throw an exception when an error occurs
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           
        }

        return $pdo;

    }
}