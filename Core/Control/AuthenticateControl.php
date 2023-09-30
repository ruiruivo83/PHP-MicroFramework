<?php

namespace Core\Control;

/**
 * AuthenticateControl base controller
 * 
 * PHP version 7.4
 */
abstract class AuthenticateControl extends \Core\Controller
{
    
    /**
     * Before filter - called before an action method.
     * 
     * @return void
     */
    protected function Before()
    {
        $this->requireLogin();
          if (! $_SESSION["PROD"]) echo "<div style=\"color:#f70202; border-style: solid;\"> ACTION FILTER - INSIDE AUTHENTICATED CONTROLLER </div>";
          

       

    }

       /**
     * Before filter - called before an action method.
     * 
     * @return void
     */
    protected function After()
    {        
        if (! $_SESSION["PROD"]) echo "<div style=\"color:#f70202; border-style: solid;\"> ACTION FILTER - INSIDE AUTHENTICATED CONTROLLER </div>";
    }

}