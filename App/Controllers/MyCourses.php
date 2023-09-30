<?php

namespace App\Controllers;

use App\Auth;
use Core\Control\SubscriptionControl;
use Core\View;
use App\Models\CourseModel;

/**
 * Items controller (example)
 * 
 * PHP version 7.4
 */
class MyCourses extends SubscriptionControl
{

    /**
     *  index
     * 
     * @return void
     */
    public function indexAction()
    {

        $courseModel = new CourseModel();
        $myCourses = $courseModel->getAllCourses(); // This gets all courses because when we pay we get access to all courses

        View::renderTemplate('MyCourses/index.html',[
             'userModel' => Auth::getUser(),
             'myCourses' => $myCourses
        ]);
    }

    /**
     *
     * @return void
     */
    public function courseWatcherAction()
    {

        $courseModel = new CourseModel();
        $courseDetails = $courseModel->getCourseDetails($_GET["course_id"]); // This gets all courses because when we pay we get access to all courses
        $SectionsAndChapters = $courseModel->getCourseSectionsAndChapters($_GET['course_id']);

        View::renderTemplate('MyCourses/courseWatcher.html',[
            'userModel' => Auth::getUser(),
            'courseDetails' => $courseDetails,
            'SectionsAndChapters' => $SectionsAndChapters
        ]);

    }

}