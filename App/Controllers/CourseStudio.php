<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use Core\View;
use Core\Authenticated;

use App\Models\TimeZoneModel;

/**
 * Profile controller
 * 
 * PHP version 7.4
 */
class CourseStudio extends Authenticated
{

    /**
     * Show the profile
     * 
     * @return void
     */
    public function indexAction()
    {

        View::renderTemplate('CourseStudio/index.html', [
            'userModel' => Auth::getUser()]);
    }

    /** New
     * 
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('coursestudio/new.html');
    }

      /** New Chapter
     * 
     * @return void
     */
    public function newChapterAction()
    {
        View::renderTemplate('coursestudio/newChapter.html');
    }



}