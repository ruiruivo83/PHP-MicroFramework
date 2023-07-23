<?php

namespace App\Controllers;

use App\Models\UserModel;
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

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user->id;

            $this->redirect('/');

        } else {

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

        // Unset all of the session variables
        $_SESSION = [];

        // Delete the session cookie
        if (ini_get('session.user_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );

        }

        // Finally destroy the session
        session_destroy();

        $this->redirect('/');

    }

}