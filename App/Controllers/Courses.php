<?php

namespace App\Controllers;

use \Core\View;
use \Core\Controller;
use \App\Auth;

/**
 * Items controller (example)
 * 
 * PHP version 7.4
 */
class Courses extends Controller
{

    /**
     * Courses index
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Courses/index.html');
    }

    /** Course Details
     * 
     * @return void
     */
    public function detailsAction()
    {
        View::renderTemplate('Courses/details.html');
    }

}