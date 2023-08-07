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
        View::renderTemplate('Signup/new.html');
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

            $userModel->sendActivationEmail();

            $this->redirect('/signup/success');

        } else {

            View::renderTemplate('signup/new.html', ['userModel' => $userModel]); // Render same form and pass the UserModel to it

        }

    }

    /**
     * Show the signup success page
     * 
     * @return void
     */
    public function successAction()
    {
        View::renderTemplate('Signup/success.html');
    }

    /**
     * Activate a new account
     * 
     * @return void
     */
    public function activateAction()
    {
        UserModel::activate($this->route_params['token']);

        $this->redirect('/signup/activated');

    }

    /**
     * Show the activation success page
     * 
     * @return void
     */
    public function activatedAction()
    {
        View::renderTemplate('Signup/activated.html.twig');
    }


}