<?php

namespace App\Controllers;

use \Core\View;
use \Core\PaymentValidator;
use \App\Auth;

/**
 * Items controller (example)
 * 
 * PHP version 7.4
 */
class MyCourses extends PaymentValidator
{

    /**
     * Items index
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('MyCourses/index.html');
    }

}