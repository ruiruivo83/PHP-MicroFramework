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


    public function getPaymentsByUserId($userId) {
        try {
            $sql = 'SELECT * FROM payments WHERE userId = :userId ORDER BY PaymentDate DESC';
            $pdo = $this->getDB();
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($payments as $key => &$payment) {
                $expirationTimestamp = strtotime($payment['PaymentExpirationDate']);
    
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