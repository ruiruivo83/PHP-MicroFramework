<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use Core\View;
use Core\Authenticated;

/**
 * Profile controller
 * 
 * PHP version 7.4
 */
class CourseWatcher extends Authenticated
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