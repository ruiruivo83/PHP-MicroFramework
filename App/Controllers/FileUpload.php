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
class FileUpload
{

    /** New file upload
     * 
     * @return void
     */
    public function newAction()
    {

        $uploaddir = '../data/uploads/';
        $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

        var_dump($uploadfile);


        /** Get the name of the uploaded file  */
        // $filename =$_FILES['userfile']['name']

        echo '<pre>';

        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            echo "File is valid, and was successfully uploaded.\n";
        } else {
            echo "Error uploading file!\n";         
        }
        
        echo 'Here is some more debugging info:';
        print_r($_FILES);        
        print "</pre>";

        // View::renderTemplate('coursestudio/newchapter.html');

    }

}