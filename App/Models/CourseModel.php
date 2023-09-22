<?php

namespace App\Models;

use App\Auth;
use Core\View;
use Error;
use PDO;

/**
 * Example user model
 * 
 * PHP version 7.4
 */
class CourseModel extends \Core\Model
{

    /**
     * Array of Error messages
     * 
     * @var array
     */
    public $errors = [];

    /**
     * class constructor
     * 
     * @param array $data Initial property values
     * 
     * @return void
     */
    public function __construct($data = [])
    {
        // VARIABLE AUTO CREATION - Variable declaration based on the theys inside the parameters, one variables with the same name for each key.
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        ;

    }


    public function CreateNewCourse($title, $description) {

        try {

            // Prepare an SQL statement for insertion
            $sql = 'INSERT INTO courses (title, description) VALUES (:title, :description)';
    
            // Get a database connection (You should have this part set up)
            $pdo = static::getDB();
    
            // Prepare the SQL statement
            $stmt = $pdo->prepare($sql);
    
            // Bind values to placeholders
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
    
            // Execute the statement
            $result = $stmt->execute();
    
            // Check if the insertion was successful
            if ($result) {
                return true; // Return true if the insertion was successful
            } else {
                return false; // Return false if the insertion failed
            }

        } catch (PDOException $e) {
            // Handle any database errors            
            echo "Error: " . $e->getMessage();
            return false; // Return false in case of an error
        }

    }

    /** Get all courses
     * 
     * @return 
     */
    public function getAllCourses() {
        try {
            // Prepare an SQL statement to retrieve all courses
            $sql = 'SELECT * FROM courses';
    
            // Get a database connection (You should have this part set up)
            $pdo = static::getDB();
    
            // Prepare the SQL statement
            $stmt = $pdo->prepare($sql);
    
            // Execute the statement
            $stmt->execute();
    
            // Fetch all courses as an associative array
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $courses;
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return []; // Return an empty array in case of an error
        }
    }


   /**
 * Gets all details of a specific course by its ID from the 'courses' table.
 *
 * @param int $courseId The ID of the course to retrieve.
 * 
 * @return array|null An associative array containing all course details, or null if the course is not found.
 * 
 * @throws PDOException If there is a problem executing the SQL query.
 */
public function getCourseDetails($courseId) {
    try {
        // Get a database connection (assuming you have this part set up)
        $pdo = static::getDB();
        
        // Prepare SQL to fetch course details
        $sql = 'SELECT * FROM courses WHERE id = :courseId';
        
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        
        // Bind the course ID to the placeholder
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        
        // Execute the SQL statement
        $stmt->execute();
        
        // Fetch the course details into an associative array
        $courseDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If no record is found, return null
        if (!$courseDetails) {
            return null;
        }
        
        return $courseDetails;
    } catch (PDOException $e) {
        // Handle any database errors
        echo "Error: " . $e->getMessage();
        return null;
    }
}

    /**
     * Create a new section in the 'sections' table.
     * 
     * @param int $courseId The ID of the course to which the section belongs
     * @param string $sectionTitle The title of the section to create
     * @param string $sectionDescription The description of the section to create
     *
     * @return void
     */
    public function CreateNewSection($courseId, $sectionTitle, $sectionDescription) {
        try {
            // Prepare the SQL statement for insertion into the 'sections' table.
            $sql = 'INSERT INTO sections (course_id, title, description) VALUES (:courseId, :sectionTitle, :sectionDescription)';

            // Obtain a database connection. This part should already be set up.
            $pdo = static::getDB();

            // Prepare the SQL statement for execution.
            $stmt = $pdo->prepare($sql);

            // Bind the variables to the placeholders in the SQL statement.
            $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            $stmt->bindParam(':sectionTitle', $sectionTitle, PDO::PARAM_STR);
            $stmt->bindParam(':sectionDescription', $sectionDescription, PDO::PARAM_STR);

            // Execute the SQL statement.
            $result = $stmt->execute();

            // Evaluate the result of the SQL statement execution.
            if ($result) {
                // Insertion was successful, redirect to a success page.
                header('Location: /coursecreator/details?course_id=' . $courseId);
                exit();  // Terminate the script execution after the redirect.
            } else {
                // Insertion failed, redirect to an error page.
                header('Location: /coursecreator/details?course_id=' . $courseId);
                exit();  // Terminate the script execution after the redirect.
            }
        } catch (PDOException $e) {
            // Handle any errors related to database operations.
            // Log the error message for debugging or audit trail.
            echo "Error: " . $e->getMessage();

            // Redirect to an error page in case of an exception.
            header('Location: /coursecreator/details?course_id=' . $courseId);
            exit();  // Terminate the script execution after the redirect.
        }
    }


    /**
     * Get section details for a specific group
     * 
     * @param int $courseId The ID of the group
     * @return array|null An array containing section details or null if not found/error
     */
    public function getCourseSections($courseId) {
        try {
            // Prepare an SQL statement to retrieve section details for a specific group
            $sql = 'SELECT * FROM sections WHERE course_id = :courseId';
            
            // Get a database connection (You should have this part set up)
            $pdo = static::getDB();
            
            // Prepare the SQL statement
            $stmt = $pdo->prepare($sql);
            
            // Bind the group ID to the placeholder
            $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            
            // Execute the statement
            $stmt->execute();
            
            // Fetch the section details as an associative array
            $sectionDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $sectionDetails;
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            header('Location: /coursecreator/details?course_id=' . $courseId);
            return null; // Return null in case of an error
        }
    }


/**
    * Create a new chapter in the 'chapters' table.
    * 
    * @param int $courseId The ID of the course to which the chapter belongs.
    * @param int $courseSectionId The ID of the section to which the chapter belongs.
    * @param string $chapterTitle The title of the chapter to create.
    * @param string $chapterDescription The description of the chapter to create.
    * @param string $videoURL The URL of the video associated with the chapter.
    * @param string $fileURL The URL of the file associated with the chapter.
    * 
    * @return bool True if the insertion is successful, false otherwise.
    */
    public function CreateNewChapter($courseId, $courseSectionId, $chapterTitle, $chapterDescription, $videoURL, $fileURL) {

        try {
            // Prepare an SQL statement for insertion
            $sql = 'INSERT INTO chapters (course_id, section_id, title, description, VideoURL, FileURL) VALUES (:courseId, :courseSectionId, :chapterTitle, :chapterDescription, :videoURL, :fileURL)';
    
            // Get a database connection (You should have this part set up)
            $pdo = static::getDB();
    
            // Prepare the SQL statement
            $stmt = $pdo->prepare($sql);
    
            // Bind values to placeholders
            $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            $stmt->bindParam(':courseSectionId', $courseSectionId, PDO::PARAM_INT);
            $stmt->bindParam(':chapterTitle', $chapterTitle, PDO::PARAM_STR);
            $stmt->bindParam(':chapterDescription', $chapterDescription, PDO::PARAM_STR);
            $stmt->bindParam(':videoURL', $videoURL, PDO::PARAM_STR);
            $stmt->bindParam(':fileURL', $fileURL, PDO::PARAM_STR);
    
            // Execute the statement
            $result = $stmt->execute();
    
            // Check if the insertion was successful
            if ($result) {
                return true; // Return true if the insertion was successful
            } else {
                return false; // Return false if the insertion failed
            }
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return false; // Return false in case of an error
        }
        
    }

    
    /**
     * Retrieve all sections and their corresponding chapters for a specific course.
     * 
     * @param int $courseId The ID of the course for which to fetch the sections and chapters.
     * 
     * @return array|null An array containing the details of all sections and their chapters, or null if an error occurs.
     */
    public function getCourseSectionsAndChapters($courseId) {
        try {
            // Array to hold all sections and their chapters
            $sectionsAndChapters = [];
    
            // Get a database connection (You should have this part set up)
            $pdo = static::getDB();
    
            // First, fetch all sections for the course
            $sectionSql = 'SELECT * FROM sections WHERE course_id = :courseId';
            $sectionStmt = $pdo->prepare($sectionSql);
            $sectionStmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            $sectionStmt->execute();
            $sections = $sectionStmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Reverse the section order
            $sections = array_reverse($sections);
    
            // Now, for each section, fetch its chapters
            foreach ($sections as $section) {
                $sectionId = $section['id'];  // Assuming 'id' is the column name for section IDs
                
                $chapterSql = 'SELECT * FROM chapters WHERE course_id = :courseId AND section_id = :sectionId';
                $chapterStmt = $pdo->prepare($chapterSql);
                $chapterStmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
                $chapterStmt->bindParam(':sectionId', $sectionId, PDO::PARAM_INT);
                $chapterStmt->execute();
                $chapters = $chapterStmt->fetchAll(PDO::FETCH_ASSOC);
    
                // Append chapters to their corresponding section
                $section['chapters'] = $chapters;
                
                // Add the section (now containing its chapters) to the result array
                $sectionsAndChapters[] = $section;
            }
    
            return $sectionsAndChapters;
    
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return null; // Return null in case of an error
        }
    }
    



    /**
     * GetAllCourseInfo
     * 
     * Fetches all information about a specific course with the given $courseId,
     * including details from the 'courses', 'sections', and 'chapters' tables.
     * 
     * @param int $courseId The ID of the course.
     * 
     * @return array|null Returns an associative array with all course information if successful,
     * or null in case of an error.
     */
    public function GetAllCourseInfo($courseId) {
        try {
            $allCourseInfo = [];
    
            // Get a database connection (You should have this part set up)
            $pdo = static::getDB();
    
            // First, fetch course details
            $courseSql = 'SELECT * FROM courses WHERE id = :courseId';
            $courseStmt = $pdo->prepare($courseSql);
            $courseStmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            $courseStmt->execute();
            $course = $courseStmt->fetch(PDO::FETCH_ASSOC);
    
            // Populate initial course info
            $allCourseInfo['courseDetails'] = $course;
    
            // Then, fetch all sections for the course
            $sectionSql = 'SELECT * FROM sections WHERE course_id = :courseId';
            $sectionStmt = $pdo->prepare($sectionSql);
            $sectionStmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
            $sectionStmt->execute();
            $sections = $sectionStmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Now, for each section, fetch its chapters
            foreach ($sections as $section) {
                $sectionId = $section['id'];
    
                $chapterSql = 'SELECT * FROM chapters WHERE course_id = :courseId AND section_id = :sectionId';
                $chapterStmt = $pdo->prepare($chapterSql);
                $chapterStmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
                $chapterStmt->bindParam(':sectionId', $sectionId, PDO::PARAM_INT);
                $chapterStmt->execute();
                $chapters = $chapterStmt->fetchAll(PDO::FETCH_ASSOC);
    
                // Append chapters to their corresponding section
                $section['chapters'] = $chapters;
    
                // Add the section to the array
                $allCourseInfo['sections'][] = $section;
            }
    
            return $allCourseInfo;
    
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return null; // Return null in case of an error
        }
    }



/**
 * Updates the details of a specific course in the 'courses' table.
 * 
 * @param int $courseId The ID of the course to update.
 * @param string $newTitle The new title for the course.
 * @param string $newDescription The new description for the course.
 * 
 * @return bool True if the update is successful, false otherwise.
 * 
 * @throws PDOException If there is a problem executing the SQL query.
 */
public function updateCourse($courseId, $newTitle, $newDescription) {
    try {
        // Get a database connection (assuming you have this part set up)
        $pdo = static::getDB();

        // Prepare SQL to update course details
        $sql = 'UPDATE courses SET title = :newTitle, description = :newDescription WHERE id = :courseId';
        
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // Bind values to placeholders
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':newTitle', $newTitle, PDO::PARAM_STR);
        $stmt->bindParam(':newDescription', $newDescription, PDO::PARAM_STR);

        // Execute the statement
        $result = $stmt->execute();

        // Check if the update was successful
        return $result ? true : false;

    } catch (PDOException $e) {
        // Handle any database errors
        echo "Error: " . $e->getMessage();
        return false;
    }
}

/**
 * GetSpecificCourseSection
 * 
 * Fetches a specific section with the given $sectionId, associated with a specific course 
 * with the given $courseId, from the 'sections' table.
 * 
 * @param int $courseId The ID of the course.
 * @param int $sectionId The ID of the section.
 * 
 * @return array|null Returns an associative array with the section information if successful,
 * or null in case of an error.
 */
public function GetSpecificCourseSection($courseId, $sectionId) {
    try {
        // Get a database connection (Assuming you have this part set up)
        $pdo = static::getDB();

        // Prepare SQL to fetch the specific section for the specific course
        $sql = 'SELECT * FROM sections WHERE course_id = :courseId AND id = :sectionId';
        $stmt = $pdo->prepare($sql);

        // Bind the $courseId and $sectionId to the placeholders in the SQL query
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':sectionId', $sectionId, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Fetch the section as an associative array
        $section = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no section is found, return null
        if (!$section) {
            return null;
        }

        // Return the section
        return $section;

    } catch (PDOException $e) {
        // Handle any database errors
        echo "Error: " . $e->getMessage();
        return null; // Return null in case of an error
    }
}

/**
 * UpdateSection
 * 
 * Updates the information for a specific section with the given $sectionId 
 * in the 'sections' table.
 * 
 * @param int $sectionId The ID of the section to update.
 * @param string $sectionTitle The new title for the section.
 * @param string $sectionDescription The new description for the section.
 * 
 * @return bool Returns true if the update was successful, false otherwise.
 */
public function UpdateSection($sectionId, $sectionTitle, $sectionDescription) {
    try {
        // Get a database connection (Assuming you have this part set up)
        $pdo = static::getDB();

        // Prepare SQL to update the section
        $sql = 'UPDATE sections SET title = :sectionTitle, description = :sectionDescription WHERE id = :sectionId';
        $stmt = $pdo->prepare($sql);

        // Bind the new values along with the section ID to the placeholders in the SQL query
        $stmt->bindParam(':sectionId', $sectionId, PDO::PARAM_INT);
        $stmt->bindParam(':sectionTitle', $sectionTitle, PDO::PARAM_STR);
        $stmt->bindParam(':sectionDescription', $sectionDescription, PDO::PARAM_STR);

        // Execute the statement
        $result = $stmt->execute();

        // Return the result
        return $result;

    } catch (PDOException $e) {
        // Handle any database errors
        echo "Error: " . $e->getMessage();
        return false; // Return false in case of an error
    }
}


/**
 * GetSpecificChapterSection
 * 
 * Fetches a specific chapter with the given $chapterId, associated with a specific section 
 * with the given $sectionId and a specific course with the given $courseId, from the 'chapters' table.
 * 
 * @param int $courseId The ID of the course.
 * @param int $sectionId The ID of the section.
 * @param int $chapterId The ID of the chapter.
 * 
 * @return array|null Returns an associative array with the chapter information if successful,
 * or null in case of an error.
 */
public function GetSpecificChapterSection($courseId, $sectionId, $chapterId) {
    try {
        // Get a database connection (Assuming you have this part set up)
        $pdo = static::getDB();

        // Prepare SQL to fetch the specific chapter for the specific section and course
        $sql = 'SELECT * FROM chapters WHERE course_id = :courseId AND section_id = :sectionId AND id = :chapterId';
        $stmt = $pdo->prepare($sql);

        // Bind the $courseId, $sectionId, and $chapterId to the placeholders in the SQL query
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':sectionId', $sectionId, PDO::PARAM_INT);
        $stmt->bindParam(':chapterId', $chapterId, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Fetch the chapter as an associative array
        $chapter = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no chapter is found, return null
        if (!$chapter) {
            return null;
        }

        // Return the chapter
        return $chapter;

    } catch (PDOException $e) {
        // Handle any database errors
        echo "Error: " . $e->getMessage();
        return null; // Return null in case of an error
    }
}


    /**
     * UpdateChapter
     * 
     * Updates the chapter information in the database with the given parameters.
     * 
     * @param int $chapterId The ID of the chapter to be updated.
     * @param string $chapterTitle The new title of the chapter.
     * @param string $chapterDescription The new description of the chapter.
     * @param string|null $videoURL The new video URL of the chapter.
     * @param string|null $fileURL The new file URL of the chapter.
     * 
     * @return bool Returns true if the update was successful, otherwise false.
     */
    public function UpdateChapter($chapterId, $chapterTitle, $chapterDescription, $videoURL = null, $fileURL = null) {

        try {
            // Get a database connection
            $pdo = $this->getDB();

            // Prepare SQL to update the specific chapter
            $sql = 'UPDATE chapters SET 
                        title = :chapterTitle, 
                        `description` = :chapterDescription, 
                        videoURL = :videoURL, 
                        fileURL = :fileURL
                    WHERE id = :chapterId';

            $stmt = $pdo->prepare($sql);

            // Bind the parameters to the placeholders in the SQL query
            $stmt->bindParam(':chapterId', $chapterId, PDO::PARAM_INT);
            $stmt->bindParam(':chapterTitle', $chapterTitle, PDO::PARAM_STR);
            $stmt->bindParam(':chapterDescription', $chapterDescription, PDO::PARAM_STR);
            $stmt->bindParam(':videoURL', $videoURL, PDO::PARAM_STR);
            $stmt->bindParam(':fileURL', $fileURL, PDO::PARAM_STR);

            // Execute the statement
            return $stmt->execute();

        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getSections($course_id) {
     
    }




}