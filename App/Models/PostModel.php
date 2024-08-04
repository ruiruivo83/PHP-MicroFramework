<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * Post Model
 * 
 * PHP version 7.4 / 8.2
 */
#[\AllowDynamicProperties] // FOR PHP 8.2
class PostModel extends \Core\Model
{

    /**
     * Get all the posts as an associative array
     * 
     * @return array
     */
    public static function getAll()
    {

        try {            

            $db = static::getDB();

            $stmt = $db->query('SELECT id, title, content FROM posts ORDER by created_at');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;

        } catch (PDOException $e) {

            echo $e->getMessage();
            
        }
        
    }
}
