<?php

namespace App\Models;

use App\Auth;
use App\Mail;
use App\Token;
use Core\View;
use Error;
use PDO;

/**
 * Example user model
 * 
 * PHP version 7.4
 */
class GroupModel extends \Core\Model
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
     * Save the group model with the current property values
     * 
     * @return boolean True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {

            $user = Auth::getUser();

            $sql = 'INSERT INTO `groups`(`group_admin_id`, `creation_date`, `group_name`, `group_description`)
                    VALUES (:group_admin_id, :creation_date, :group_name, :group_description)';

            $db = static::getDB();

            $stmt = $db->prepare($sql);

            date_default_timezone_set("Europe/Paris");

            $stmt->bindValue(':group_admin_id', $user->id, PDO::PARAM_STR);
            $stmt->bindValue(':creation_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->bindValue(':group_name', $this->group_name, PDO::PARAM_STR);
            $stmt->bindValue(':group_description', $this->group_description, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    /**
     * Validate current property values, adding validation error messages to the errors array property
     * 
     * @return void
     */
    public function validate()
    {

        // Name
        if ($this->group_name == '') {
            $this->errors[] = 'Name is required';
        }

        // Name
        if ($this->group_description == '') {
            $this->errors[] = 'Name is required';
        }

    }

    /**
     * Save the group model with the current property values
     * 
     * @return boolean True if the user was saved, false otherwise
     */
    public function load()
    {
        $user = Auth::getUser();
        date_default_timezone_set("Europe/Paris");

        $db = static::getDB();

        $stmt = $db->query('SELECT * FROM `groups` WHERE group_admin_id = ' . $user->id);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }


}