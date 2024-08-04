<?php

namespace App\Models;

use App\Mail;
use App\Token;
use Core\View;
use Error;
use PDO;

/**
 * Example user model
 * 
 * PHP version 7.4 / 8.2
 */
#[\AllowDynamicProperties] // FOR PHP 8.2
class TimeZoneModel extends \Core\Model
{

    /**
     * Array of Error messages
     * 
     * @var array
     */
    public $errors = [];

    /**
     * class constructor
     * 
     * @param array $data Initial property values
     * 
     * @return void
     */
    public function __construct($data = [])
    {
        // VARIABLE AUTO CREATION - Variable declaration based on the theys inside the parameters, one variables with the same name for each key.
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        ;

    }

    /**
     * Get all possible timezones
     * 
     * @return array Array if found, false otherwise
     */
    public static function getAllTimeZones()
    {

        try {            

            $db = static::getDB();

            $stmt = $db->query('SELECT * FROM timezones');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;

        } catch (PDOException $e) {

            echo $e->getMessage();
            
        }
        
    }




}