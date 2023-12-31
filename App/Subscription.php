<?php

namespace App;

use App\Models\RememberedLoginModel;
use App\Models\UserModel;
use App\Models\SubscriptionModel;
use App\Models\PaymentsModel;

/**
 * Authentication
 * 
 * PHP version 7.4
 */
class Subscription
{

    
    /**
     * Verifies if the user has an active Subscription plan
     * 

     * @return boolean  Return true if Subscription for current user is active, false otherwise.
     */
    public static function hasSubscription()
    {
       
        if (isset($_SESSION['user_id'])) {

            $paymentsModel = new PaymentsModel();

            if ($paymentsModel->testForActivePayment($_SESSION['user_id'])) {
               return true;
            } else {
              return false;
            }

        } else {

            return false;

        }
      
    }


}