<?php

namespace App;

use App\Models\RememberedLoginModel;
use App\Models\UserModel;
use App\Models\SubscriptionModel;

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

            $SubscriptionModel = new SubscriptionModel();

            if ($SubscriptionModel->testForActiveSubscription()) {
               return true;
            } else {
              return false;
            }

        } else {

            return false;

        }
      
    }


}