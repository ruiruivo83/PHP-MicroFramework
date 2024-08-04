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
class UserModel extends \Core\Model
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
        // VARIABLE AUTO CREATION - Variable declaration based on the keys inside the parameters, one variables with the same name for each key.
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Save the user model with the current property values
     * 
     * @return boolean True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $activation_expiration_date =   time() + 60 * 60 * 24 ; // 24 houres

            $token = new Token();
            $hashed_token = $token->gethash();
            $this->activation_token = $token->getValue();

            $sql = 'INSERT INTO users (name, email, password_hash, activation_hash, activation_expiration_date)
                    VALUES (:name, :email, :password_hash, :activation_hash, :activation_expiration_date)';

            $pdo = static::getDB();

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);
            $stmt->bindValue(':activation_expiration_date', date('Y-m-d H:i:s', $activation_expiration_date), PDO::PARAM_STR);

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
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }

        // Email Address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) { // Uses PHP own Validate filters
            $this->errors[] = 'Invalid email';
        }
        if ($this->emailExists($this->email, $this->id ?? null)) {
            $this->errors[] = 'email already taken';
        }

        // Password - We only validate if isset
        if (isset($this->password)) {
            if ($this->password != $this->password_confirmation) {
                $this->errors[] = 'Password must match confirmation';
            }

            if (strlen($this->password) < 6) {
                $this->errors[] = 'Please enter at least 6 characters for the password';
            }

            if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
                $this->errors[] = 'Password need at least one letter';
            }

            if (preg_match('/.*\d+.*/i', $this->password) == 0) {
                $this->errors[] = 'Passwords needs at least one number';
            }
        }
    }

    /**
     * See if a user record already exists with the specified email
     * 
     * @param string $email email address to search for
     * 
     * @return boolean True if a record already exists with the specified email, false otherwise
     */
    protected function emailExists($email, $ignore_id = null)
    {
        $user = static::findByEmail($email);

        if ($user) {
            if ($user->id != $ignore_id) {
                return true;
            }
        }

        return false;
    }


    /**
     * See if a user record already exists with the specified email
     * 
     * @param string $email email address to search for
     * 
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        // Return an object based on the model called, in this case the 'get_called_class()' will automaticly detect the self class, in this case UserModel, 
        // and fill it with the values returned from the database in the construct, when the class is runed the construct creates variables of the data as parameters.
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }


    /**
     * Authenticate a user by email and password
     * 
     * @param string $email email address
     * @param string $password password
     * 
     * @return mixed Return the user object or false if authentication fails
     */
    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if ($user && $user->is_active) {
            if (password_verify($password, $user->password_hash)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Get a user model by ID
     * 
     * @param string $id The user ID
     * 
     * @return mixed User object if found, null otherwise
     */
    public static function getUserByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Return an object based on the model called, in this case the 'get_called_class()' will automatically detect the self class, in this case UserModel, 
        // and fill it with the values returned from the database in the construct, when the class is run the construct creates variables of the data as
        // parameters with the names from the keys and its values, from the value.
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $result = $stmt->fetch();
        
        // Check if $result is false and return null if so.
        if ($result === false) {
            return null;
        }

        return $result;
    }


    /**
     * Remember the login by inserting a new unique token into the remembered_logins table
     * for this user record
     * 
     * @return boolean True if the login was remembered successfully, false otherwise
     */
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30; // 30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at) VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Send password reset instructions to the user specified
     * 
     * @param string $email The email address
     * 
     * @return void
     */
    public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);

        if ($user) {

            if ($user->startPasswordReset()) {

                $user->sendPasswordResetEmail();
            }
        }
    }

    /**
     * Start the password reset process by generating a new token and expiry
     * 
     * @return void
     */
    protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();

        $expiry_timestamp = time() + 60 * 60 * 2; // 2 hours from now

        $sql = 'UPDATE users 
                SET password_reset_hash = :token_hash, password_reset_expires_at = :expires_at
                WHERE id = :id';

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }


    /**
     * Send password reset instructions in an email to the user
     * 
     * @return void
     */
    protected function sendPasswordResetEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;

        $text = View::getTemplate('Password/Mail Templates/reset_email.txt', ['url' => $url]);
        $html = View::getTemplate('Password/Mail Templates/reset_email.html', ['url' => $url]);

        Mail::send($this->email, 'Password reset', $text, $html);
    }

    /**
     * Find a user model by password reset token and expiry
     * 
     * @param string $token Password reset token sent to user
     * 
     * @return mixed User object if found and the token hasn't expired, null otherwise
     */
    public static function getUserByPasswordResetToken($token)
    {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users
                WHERE password_reset_hash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $user = $stmt->fetch();

        if ($user) {

            // Check password reset token hasn't expired
            if (strtotime($user->password_reset_expires_at) > time()) {

                return $user;
            }
        }
    }

    /**
     * Reset the password
     * 
     * @param string $password The new password
     * 
     * @return boolean True if the password was updated successfully, false otherwise
     */
    public function resetUserPassword($password, $password_confirmation)
    {
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;

        $this->validate();

        if (empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users
                    SET password_hash = :password_hash,
                        password_reset_hash = NULL,
                        password_reset_expires_at = NULL
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }


    /**
     * Send account activation instructions in an email to the user
     * 
     * @return void
     */
    public function sendUserActivationEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token;

        $text = View::getTemplate('Signup/Mail Templates/activation_email.txt', ['url' => $url]);
        $html = View::getTemplate('Signup/Mail Templates/activation_email.html', ['url' => $url]);

        Mail::send($this->email, 'Account Activation', $text, $html);
    }


    /**
     * Activate the user account with the specified activation token
     * 
     * @param string $value Activation token from the url
     * 
     * @return void
     */
    public static function activateUserAccount($value)
    {
        $token = new Token($value);
        $hashed_token = $token->getHash();

        $sql = 'UPDATE users
                SET is_active = 1,
                    activation_hash = null
                WHERE activation_hash = :hashed_token';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * update the user's profile
     * 
     * @param array $data Data from the edit profile form
     * 
     * @return boolean True if the data was updated, false otherwise
     */
    public function updateUserProfile($data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];


        // Only validate and updatethe password if a value provided
        if ($data['password'] != '') {
            $this->password = $data['password'];
            $this->password_confirmation = $data['password_confirmation'];
        }

        $this->validate();

        if (empty($this->errors)) {

            $sql = 'UPDATE users
                    SET name = :name,
                        email = :email';

            if (isset($this->password)) {
                $sql .= ', password_hash = :password_hash';
            }

            $sql .= ' WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);


            // Add password if it's set
            if (isset($this->password)) {
                $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            }


            return $stmt->execute();
        }

        return false;
    }

    /**
     * Get user timezone
     * 
     * @param $user_id The current user id logged in
     * 
     * @return string Array if found, false otherwise
     */
    public static function getUserTimeZone()
    {

        try {

            $db = static::getDB();

            // Use prepared statement to avoid SQL injection
            $stmt = $db->prepare('SELECT time_zone FROM users WHERE id = :user_id');
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();

            // Fetch the result as an associative array
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Check if $result is an array and has 'time_zone' key
            if (is_array($result) && array_key_exists('time_zone', $result)) {
                return $result['time_zone'];
            } else {
                return null;  // Return null if no timezone is found
            }
            
        } catch (\PDOException $e) {

            echo $e->getMessage();
        }
    }


    /**
     * Set a specific time zone for user
     * 
     * @return void
     */
    public static function setUserTimeZone()
    {

        $user_id = $_SESSION['user_id'];
        $timeZone = $_GET["timezone"];

        $sql = 'UPDATE users 
                SET time_zone = :timezone
                WHERE id = :user_id';

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':timezone', $timeZone, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * 
     */
    public static function createNewUserPayment($expiration_date)
    {
        try {
            $sql = 'INSERT INTO payments (user_id, payment_date, payment_expiration_date) VALUES (:user_id, :payment_date, :payment_expiration_date)';  // Corrected here

            $db = static::getDb();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':payment_date', date('Y-m-d H:i:s', time()), PDO::PARAM_STR);
            $stmt->bindValue(':payment_expiration_date', date('Y-m-d H:i:s', $expiration_date), PDO::PARAM_STR);  // Corrected here

            return $stmt->execute();
        } catch (\PDOException $e) {

            echo $e->getMessage();
        }
    }


    /**
     * Get all payments for current user
     */
    public function getAllPaymentsForCurrentUser()
    {

        $sql = 'SELECT * FROM payments WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT); // Notice that we bind user_id instead of id

        // Set the fetch mode to class. This way, each row returned from the database will be converted into an object of the calling class.
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchAll(); // Using fetchAll() instead of fetch() to get all records

    }








    /**
     * Test if user is Sys Admin
     */
    public function testIfUserIsSysAdmin()
    {

        $sql = 'SELECT is_sys_admin FROM users WHERE id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT); // Notice that we bind user_id instead of id

        // Set the fetch mode to class. This way, each row returned from the database will be converted into an object of the calling class.
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchAll(); // Using fetchAll() instead of fetch() to get all records

    }

    /**
     * @return void
     */
    public function getAllUsers()
    {
        $sql = 'SELECT * FROM users';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        // Set the fetch mode to class. This way, each row returned from the database will be converted into an object of the calling class.
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetchAll(); // Using fetchAll() instead of fetch() to get all records
    }

    /**
     * Remember the user breadcrumb to user_history by inserting every breadcrumb into history    
     * 
     * @return boolean True if successful, false otherwise
    */
    public function save_breadcrumb_to_history($breadcrumb)
    {

        if (isset($_SESSION['user_id'])) {
            $sql = 'INSERT INTO user_history (user_id, `date`, breadcrumb) VALUES (:user_id, :date, :breadcrumb)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
    
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':date', date('Y-m-d H:i:s', time()), PDO::PARAM_STR);
            $stmt->bindValue(':breadcrumb', $breadcrumb, PDO::PARAM_STR);
            return $stmt->execute();
        }

       

     
    }


}
