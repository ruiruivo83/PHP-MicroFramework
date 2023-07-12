<?php

namespace App\Controllers;

/**
 * Home Controller
 * 
 * PHP version 7.4
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     * 
     * @return void
     */
    public function indexAction()
    {
        echo 'Hello from the index action in the Home controller!';
    }

    /**
     * Before filter - called before an action method.
     * 
     * @return void
     */
    protected function Before()
    {
        echo " (before)";
        return false; // Will stop the execution
    }

    /**
     * After filter - called after an action method.
     * 
     * @return void
     */
    protected function After()
    {
        echo " (after)";
    }

}
