<?php

namespace Core;

use \Core\Authenticated;

/**
 * Authenticated base controller
 * 
 * PHP version 7.4
 */
abstract class SubscriptionControl extends Authenticated
{
    
    /**
     * Before filter - called before an action method.
     * 
     * @return void
     */
    protected function Before()
    {        
       $this->requireSubscription();    
        echo "<div style=\"color:#f70202; border-style: solid;\"> ACTION FILTER - INSIDE AUTHENTICATED CONTROLLER </div>";
    }

       /**
     * Before filter - called before an action method.
     * 
     * @return void
     */
    protected function After()
    {        
        echo "<div style=\"color:#f70202; border-style: solid;\"> ACTION FILTER - INSIDE AUTHENTICATED CONTROLLER </div>";
    }

}