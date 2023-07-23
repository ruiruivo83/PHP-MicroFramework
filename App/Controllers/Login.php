<?php

namespace App\Controllers;

use \App\Auth;
use App\Flash;
use \App\Models\UserModel;
use \Core\View;

/**
 * Login controller
 * 
 * PHP version 7.4
 */
class Login extends \Core\Controller
{

    /**
     * Show the login page
     * 
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Login/new.html.twig');
    }

    /**
     * Log in a user
     * 
     * @return void
     */
    public function createAction()
    {
        $user = UserModel::authenticate($_POST['email'], $_POST['password']);

        if ($user) {

            Auth::login($user);

            Flash::addMessage('Login successful', Flash::SUCCESS);

            $this->redirect(Auth::getReturnToPage());

        } else {

            Flash::addMessage('Login unsuccessful, please try again', Flash::WARNING);

            View::renderTemplate('Login/new.html.twig', [
                'email' => $_POST['email'],
            ]);

        }
    }

    /**
     * Log out a user
     * 
     * @return void
     */
    public function destroyAction()
    {

        Auth::logout();

        $this->redirect('/login/show-logout-message');

    }

    /**
     * Shows a message after the session has been destroyed. :D
     * 
     * Show a "logged out" flash message and redirect to the homepage. Necessary to use the flash messages
     * as they use the session and at the end of the logout method (destroyAction) the session is destroyed
     * so a new action needs to be called in order to use the session.
     * 
     * @return void
     */
    public function showLogoutMessageAction()
    {

        Flash::addMessage('You are logged out');

        $this->redirect('/');

    }

}