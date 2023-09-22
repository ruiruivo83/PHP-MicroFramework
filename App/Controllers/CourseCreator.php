<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use Core\View;
use Core\Authenticated;
use App\Models\CourseModel;

/**
 * Profile controller
 * 
 * PHP version 7.4
 */
class CourseCreator extends Authenticated // TODO - Replace by SysAdmin for SysAdmin pages - Create new control for SysAdmin
{

/**
 * Show the profile
 * 
 * @return void
 */
public function indexAction()
{
    // Create an instance of the CourseModel
    $courseModel = new CourseModel();

    // Fetch data from the database using a method in CourseModel (e.g., getAllCourses())
    $courses = $courseModel->getAllCourses();


    // Pass the fetched data to the view
    View::renderTemplate('CourseCreator/index.html', [
        'userModel' => Auth::getUser(),
        'courses' => $courses // Add the 'courses' variable to the view
    ]);
}


    /** New
     * 
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('CourseCreator/new.html');
    }

        /** New
     * 
     * @return void
     */
    public function detailsAction($courseId = [])
    {
        
        $courseModel = new CourseModel();
        $courseDetails = $courseModel->getCourseDetails($_GET['course_id']);
        $SectionsAndChapters = $courseModel->getCourseSectionsAndChapters($_GET['course_id']);

        View::renderTemplate('CourseCreator/details.html', [
            'userModel' => Auth::getUser(),
            'courseDetails' => $courseDetails,
            'SectionsAndChapters' => $SectionsAndChapters
        ]);

    }

    /** New Section
     * 
     * @return void
     */
    public function newSectionAction()
    {

        $courseModel = new CourseModel();
        $courseDetails = $courseModel->getCourseDetails($_GET['id']);        

        View::renderTemplate('CourseCreator/newSection.html', [
            'userModel' => Auth::getUser(),
            'courseDetails' => $courseDetails
        ]);
      
    }

    /** New Chapter
     * 
     * @return void
     */
    public function newChapterAction()
    {       

        $course_id = $_GET['course_id'];
        $course_section_id = $_GET['course_section_id'];


        View::renderTemplate('CourseCreator/newChapter.html', [
            'course_id' => $course_id,
            'course_section_id' => $course_section_id
        ]);

    }

    /** createCourse
     * 
     */
    public function createCourse() {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if the form was submitted using POST
        
            // Assuming you have sanitized and validated the POST data before using it
            $courseTitle = $_POST['courseTitle'];
            $courseDescription = $_POST['courseDescription'];
        
          
        
            // Call the CreateNewCourse method and pass the data
            $courseModel = new CourseModel();
            $result = $courseModel->CreateNewCourse($courseTitle, $courseDescription);
        
            // Check the result or perform further actions based on the result
            if ($result) {

                // Course creation was successful
                // You can redirect or display a success message
                // For example, you can redirect to a success page

                header('Location: /CourseCreator/index');

                exit(); // Terminate script execution after the redirect

            } else {

                // Course creation failed
                // Handle the error, display an error message, or redirect to an error page
                echo "Course creation failed. Please try again.";

            }
        } else {

            // Handle cases where the form was not submitted via POST
            // You can display the form or perform other actions as needed

        }      
    }

    /** Create new section to current course
     * 
     */
    public function createSection() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if the form was submitted using POST
    
            // Assuming you have sanitized and validated the POST data before using it
            $sectionTitle = $_POST['sectionTitle'];
            $sectionDescription = $_POST['sectionDescription'];
            $courseId = $_POST['courseId'];
    
            // Call the CreateNewSection method and pass the data
            $courseModel = new CourseModel(); // Assuming you have a SectionModel class
            $result = $courseModel->CreateNewSection($courseId, $sectionTitle, $sectionDescription);
    
            // Check the result or perform further actions based on the result
            if ($result) {
                // Section creation was successful
                // You can redirect or display a success message
                // For example, you can redirect to a success page

                header('Location: /CourseCreator/index');
    
                exit(); // Terminate script execution after the redirect

            } else {
                // Section creation failed
                // Handle the error, display an error message, or redirect to an error page
                echo "Section creation failed. Please try again.";
            }
        } else {
            // Handle cases where the form was not submitted via POST
            // You can display the form or perform other actions as needed
        }
    }

    /**
     * 
     */
    public function createChapter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if the form was submitted using POST
    
            // Assuming you have sanitized and validated the POST data before using it
            $course_id = $_POST['course_id'];
            $course_section_id = $_POST['course_section_id'];
            $chapterTitle = $_POST['chapterTitle'];
            $chapterDescription = $_POST['chapterDescription'];
            $videoURL = $_POST['VideoURL'];
            $fileURL = $_POST['FileURL'];
    
            // Initialize the CourseModel and call a hypothetical CreateNewChapter method
            $courseModel = new CourseModel(); // Assuming you have a CourseModel class
            $result = $courseModel->CreateNewChapter($course_id, $course_section_id, $chapterTitle, $chapterDescription, $videoURL, $fileURL);
    
            // Check the result or perform further actions based on the result
            if ($result) {
                // Chapter creation was successful
                // You can redirect or display a success message
                // For example, you can redirect to a success page
                header('Location: /coursecreator/details?course_id=' . $course_id);
                exit(); // Terminate script execution after the redirect
            } else {
                // Chapter creation failed
                // Handle the error, display an error message, or redirect to an error page
                echo "Chapter creation failed. Please try again.";
            }
        } else {
            // Handle cases where the form was not submitted via POST
            // You can display the form or perform other actions as needed
        }
    }

    /**
     * 
     */
    public function editAction() {

        $courseModel = new CourseModel();
        $courseInfo = $courseModel->GetCourseDetails($_GET['course_id']);

        View::renderTemplate('CourseCreator/edit.html', [
            'courseInfo' => $courseInfo
        ]);

    }

        /**
     * 
     */
    public function editSectionAction() {

        $courseModel = new CourseModel();
        $courseSectionInfo = $courseModel->GetSpecificCourseSection($_GET['course_id'], $_GET['section_id']);

        View::renderTemplate('CourseCreator/editsection.html', [
            'courseSectionInfo' => $courseSectionInfo,
            'courseId' => $_GET['course_id']
        ]);

    }

    /**
     * 
     */
    public function updateCourse() {

        $courseModel = new CourseModel();

        if ( $courseModel->UpdateCourse($_POST['courseId'], $_POST['courseTitle'], $_POST['courseDescription'])) {
            Flash::addMessage('Changes saved');
            $this->redirect('/coursecreator/details?course_id=' . $_POST['courseId']);
        } else {
            echo "Failed to update course";
        }

    }

/**
 * updateSection
 * 
 * Receives form data and calls the method to update the section information in the database.
 */
public function updateSection() {
    // Get the form data from the POST request
    $sectionId = $_POST['sectionId'];
    $sectionTitle = $_POST['sectionTitle'];
    $sectionDescription = $_POST['sectionDescription'];
    
    // Create an instance of the model that has the UpdateSection method
    $courseModel = new CourseModel();

    // Call the UpdateSection method to update the section in the database
    $result = $courseModel->UpdateSection($sectionId, $sectionTitle, $sectionDescription);

    // Check the result and proceed accordingly
    if ($result) {
        // Successfully updated, you might want to redirect or send a success message
        Flash::addMessage('Changes saved');
        $this->redirect('/coursecreator/details?course_id=' . $_POST['courseId']);
    } else {
        // Update failed, you might want to show an error message
        echo "Failed to update section";
    }
}

    

            /**
     * 
     */
    public function editChapterAction() {

        $courseModel = new CourseModel();
        $courseChapterInfo = $courseModel->GetSpecificChapterSection($_GET['course_id'], $_GET['section_id'], $_GET['chapter_id']);

        View::renderTemplate('CourseCreator/editchapter.html', [
            'courseChapterInfo' => $courseChapterInfo         
        ]);

    }






/**
 * updateChapter
 * 
 * Receives form data and calls the method to update the chapter information in the database.
 */
public function updateChapter() {
    // Get the form data from the POST request
    $chapterId = $_POST['chapter_id'];
    $courseId = $_POST['course_id'];
    $chapterTitle = $_POST['chapterTitle'];
    $chapterDescription = $_POST['chapterDescription'];
    $videoURL = $_POST['VideoURL'];
    $fileURL = $_POST['FileURL'];
    
    // Create an instance of the model that has the updateChapter method
    $courseModel = new CourseModel();

    // Call the updateChapter method to update the chapter in the database
    $result = $courseModel->UpdateChapter($chapterId, $courseId, $chapterTitle, $chapterDescription, $videoURL, $fileURL);

    // Check the result and proceed accordingly
    if ($result) {
        // Successfully updated, you might want to redirect or send a success message
        Flash::addMessage('Chapter updated successfully');
        $this->redirect('/coursecreator/details?course_id=' . $_POST['course_id']);
    } else {
        // Update failed, you might want to show an error message
        echo "Failed to update chapter";
    }
}


}