<?php

namespace App;

use App\Models\RememberedLoginModel;
use App\Models\UserModel;
use App\Models\PaymentModel;

/**
 * Authentication
 * 
 * PHP version 7.4
 */
class Payment
{

    
    /**
     * Verifies if the user has an active payment plan
     * 

     * @return boolean  Return true if payment for current user is active, false otherwise.
     */
    public static function hasPayment()
    {
       
        if (isset($_SESSION['user_id'])) {

            $PaymentModel = new PaymentModel();

            if ($PaymentModel->testForActivePayment()) {
               return true;
            } else {
              return false;
            }

        } else {

            return false;

        }
      
    }


}