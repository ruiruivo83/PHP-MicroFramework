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
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );

        // UPLOAD
        $target_dir  = '../data/uploads/';
        $target_file  = $target_dir  . basename($_FILES['userfile']['name']);

        echo 'file count=', count($_FILES),"\n";
        echo "File Count: " . var_dump($_FILES);

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
        if ($_FILES["userfile"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "sql" && $imageFileType != "pdf" && $imageFileType != "mov" && $imageFileType != "avi" && $imageFileType != "mp4") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["userfile"]["name"])). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        // MODEL
        var_dump($_POST);
        var_dump($_FILES['userfile']['name']);
        var_dump("File Size: " . $_FILES['userfile']['size']);
        
        // MODEL
        echo 'Here is some more debugging info:';
        print_r($_FILES);
        echo "Error description: " . $phpFileUploadErrors[$_FILES['userfile']['error']]."\n";

        print "</pre>";

        View::renderTemplate('coursestudio/newchapter.html');

    }

}
