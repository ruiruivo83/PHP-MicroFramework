<?php

namespace App\Controllers;

use App\Models\UserModel;
use Core\View;

/**
 * Password controller
 * 
 * PHP version 7.4
 */
class Password extends \Core\Controller
{

    /**
     * Show the forgotten password page
     * 
     * @return void
     */
    public function forgotAction()
    {
        View::renderTemplate('Password/forgot.html.twig');
    }

    /**
     * Send the password reset link tot the supplied email
     * 
     * @return void
     */
    public function requestResetAction()
    {
        UserModel::sendPasswordReset($_POST['email']);

        View::renderTemplate('Password/reset_requested.html.twig');
    }



}