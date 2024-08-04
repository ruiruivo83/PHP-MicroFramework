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
 * PHP version 7.4 / 8.2
 */
#[\AllowDynamicProperties] // FOR PHP 8.2
class PaymentsModel extends \Core\Model
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
        };
    }


    public function getAllPaymentsForUserId($userId)
    {
        try {
            $sql = 'SELECT * FROM payments WHERE user_id = :userId ORDER BY payment_date DESC';
            $pdo = $this->getDB();
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);


            foreach ($payments as $key => &$payment) {


                $expirationTimestamp = strtotime($payment['payment_expiration_date']);

                if ($expirationTimestamp > time()) {
                    $payment['isActive'] = 'Active';
                } else {
                    $payment['isActive'] = 'Not Active';
                }

                $payments[$key] = $payment; // Update the original array with the modified $payment
            }
            unset($payment); // Unset the reference when the loop is complete

            return $payments;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function testForActivePayment($userId)
    {
        try {
            // Fetch all payments for the user from the database
            $sql = 'SELECT payment_expiration_date FROM payments WHERE user_id = :userId';
            $pdo = $this->getDB();
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            // Fetch all payment expiration dates
            $payments = $stmt->fetchAll(PDO::FETCH_COLUMN, 0); // Get only the 'payment_expiration_date' column
            
            // Check if there's at least one active payment
            foreach ($payments as $payment) {
                $expirationTimestamp = strtotime($payment);
                if ($expirationTimestamp > time()) {
                    return true; // An active subscription exists
                }
            }
            
            return false; // No active subscription exists
        } catch (\PDOException $e) {
            // Log this error to a file or error tracking service
            // return some generic error message or code
            return false;
        }
    }

    public function getAllPayments()
    {
        try {
            $sql = 'SELECT * FROM payments ORDER BY payment_date DESC';
            $pdo = $this->getDB();
            $stmt = $pdo->prepare($sql);

            $stmt->execute();
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);


            foreach ($payments as $key => &$payment) {


                $expirationTimestamp = strtotime($payment['payment_expiration_date']);

                if ($expirationTimestamp > time()) {
                    $payment['isActive'] = 'Active';
                } else {
                    $payment['isActive'] = 'Not Active';
                }

                $payments[$key] = $payment; // Update the original array with the modified $payment
            }
            unset($payment); // Unset the reference when the loop is complete

            return $payments;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }


}
