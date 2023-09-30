<?php

namespace App\Controllers;

use Core\View;

/**
 * Items controller (example)
 * 
 * PHP version 7.4
 */
class Items extends \Core\Control\AuthenticateControl
{

    /**
     * Items index
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Items/index.html');
    }

}