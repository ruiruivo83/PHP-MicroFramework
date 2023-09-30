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
class SysAdmin
{

    
    /**
     * Verifies if the user has an active Subscription plan
     * 

     * @return boolean  Return true if Subscription for current user is active, false otherwise.
     */
    public static function isSysAdmin()
    {
       
        if (isset($_SESSION['user_id'])) {

            $userModel = new UserModel();

            $userModel = $userModel->testIfUserIsSysAdmin();

            if ($userModel[0]->is_sys_admin == 0) {
               return false;
            } else {
              return true;
            }

        } else {

            return false;

        }
      
    }


}