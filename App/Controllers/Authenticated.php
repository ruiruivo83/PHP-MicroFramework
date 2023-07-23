<?php

namespace App\Controllers;

/**
 * Authenticated base controller
 * 
 * PHP version 7.4
 */
abstract class Authenticated extends \Core\Controller
{
    
    /**
     * Before filter - called before an action method.
     * 
     * @return void
     */
    protected function Before()
    {
        $this->requireLogin();
    }

}