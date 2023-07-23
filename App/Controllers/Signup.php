<?php

namespace App\Controllers;

use \Core\View;
use App\Models\UserModel;

/**
 * Signup controller
 * 
 * PHP version 7.4
 */

class Signup extends \Core\Controller
{
    /**
     * Show the signup page
     * 
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Signup/new.html.Twig');
    }

    /**
     * Sign up a new user
     * 
     * @return void
     */
    public function createAction()
    {
        // var_dump($_POST);
        $userModel = new UserModel($_POST);

        if ($userModel->save()) {

            $this->redirect('/signup/success');

        } else {

            View::renderTemplate('signup/new.html.twig', ['userModel' => $userModel]); // Render same form and pass the UserModel to it

        }

    }

    /**
     * Show the signup success page
     * 
     * @return void
     */
    public function successAction()
    {
        View::renderTemplate('Signup/success.html.twig');
    }

}