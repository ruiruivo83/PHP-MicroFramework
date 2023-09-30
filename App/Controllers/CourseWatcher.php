<?php

namespace App\Controllers;

use App\Auth;
use Core\Control\AuthenticateControl;
use Core\View;

/**
 * Profile controller
 * 
 * PHP version 7.4
 */
class CourseWatcher extends AuthenticateControl
{

    /**
     * Show the main course page for the course (Chapter and )
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('CourseWatcher/index.html', [
            'userModel' => Auth::getUser()]);
    }


}