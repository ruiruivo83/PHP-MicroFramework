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
class FileUpload extends Authenticated 
{

    /** New file upload
     * 
     * @return void
     */
    public function newAction()
    {

        // MODEL
        $phpFileUploadErrors = array(
            0 => 'NO ERRORS',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );

        // changing the upload limits
        ini_set('upload_max_filesize', '5M');
        ini_set('post_max_size', '5M');
        ini_set('max_input_time', 300);
        ini_set('max_execution_time', 300);

        var_dump($_FILES);

        // UPLOAD
        $target_dir  = '../data/uploads/';
        $target_file  = $target_dir  . basename($_FILES['userfile']['name']);

        echo 'File Count=' . count($_FILES) . "<br>";
       

        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {

            $check = getimagesize($_FILES["userfile"]["tmp_name"]);

            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["userfile"]["size"] > $_POST['MAX_FILE_SIZE']) { // 1 000 000 = 1Mb - 3000000 = 3MB etc
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "sql" && $imageFileType != "pdf" && $imageFileType != "mov" && $imageFileType != "avi" && $imageFileType != "mp4" && $imageFileType != "txt" && $imageFileType != "zip" && $imageFileType != "rar") {
            echo "Sorry, only JPG, JPEG, PNG & GIF, sql, pdf, mov, avi, mp4, txt, zip, rar files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.<br>";            
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["userfile"]["name"])). " has been uploaded.<br>";
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
            }
        }

        echo "Error description: " . $phpFileUploadErrors[$_FILES['userfile']['error']]."<br>";

        View::renderTemplate('coursestudio/newchapter.html');

    }

}
