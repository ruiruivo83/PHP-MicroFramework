<?php

namespace App\Controllers;

use \App\Auth;
use \Core\View;

/**
 * Home Controller
 * 
 * PHP version 7.4
 */
class Home extends \Core\Controller
{

    /**
     * Before filter - called before an action method.
     * 
     * @return void
     */
    /*
    protected function Before()
    {
        echo " (before in \Controllers\Home)";
        // return false; // Will stop the execution
    }
    */

    /**
     * After filter - called after an action method.
     * 
     * @return void
     */
    /*
    protected function After()
    {
        echo " (after in \Controllers\Home)";
    }
    */

    /**
     * Show the index page
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html', [
            'userModel' => Auth::getUser()
        ]);
    }
}