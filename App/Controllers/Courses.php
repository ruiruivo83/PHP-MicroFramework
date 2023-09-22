<?php

namespace App\Controllers;

use \Core\View;
use \Core\Controller;
use \App\Auth;

use App\Models\CourseModel;

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
        // Create an instance of the model
        $courseModel = new CourseModel();
        
        // Retrieve all courses from the database
        $courses = $courseModel->getAllCourses();

        // Render the view and pass the courses to the template
        View::renderTemplate('Courses/index.html', [
            'courses' => $courses
        ]);
    }


    public function detailsAction() {
        // Assuming you have a way to get the courseId from request parameters or elsewhere
        $courseId = $_GET['id']; // Replace with the correct way to get the courseId in your implementation
    
        // Get an instance of your model
        $courseModel = new CourseModel();
        $courseDetails = $courseModel->getCourseDetails($_GET['id']);
        $SectionsAndChapters = $courseModel->getCourseSectionsAndChapters($_GET['id']);


        View::renderTemplate('Courses/details.html', [
            'userModel' => Auth::getUser(),
            'courseDetails' => $courseDetails,
            'SectionsAndChapters' => $SectionsAndChapters
        ]);

    }
    




}