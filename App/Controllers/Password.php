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
        View::renderTemplate('Password/forgot.html',[
            'userModel' => Auth::getUser()
        ]);
    }

    /**
     * Send the password reset link tot the supplied email
     * 
     * @return void
     */
    public function requestResetAction()
    {
        UserModel::sendPasswordReset($_POST['email']);

        View::renderTemplate('Password/reset_requested.html',[
            'userModel' => Auth::getUser()
        ]);
    }

    /**
     * Show the reset password form
     * 
     * @return void
     */
    public function resetAction()
    {

        $token = $this->route_params['token'];

        $user = $this->getUserOrExit($token);

        $user = UserModel::getUserByPasswordResetToken($token);

        View::renderTemplate('Password/reset.html', [
            'userModel' => Auth::getUser(),
            'token' => $token
        ]);

    }

    /**
     * Reset the user's password
     * 
     * @return void
     */
    public function resetUserPasswordAction()
    {
        $token = $_POST['token'];

        $user = $this->getUserOrExit($token);

        if ($user->resetUserPassword($_POST['password'], $_POST['password_confirmation'])) {

           View::renderTemplate('Password/reset_success.html', [
               'userModel' => Auth::getUser(),
           ]);

        } else {

            View::renderTemplate('Password/reset.html', [
                'token' => $token,
                'userModel' => $user
            ]);

        }

    }

    /**
     * Find the user model associated with the password reset token, or end the request with a message
     * 
     * @param string $token Password reset token sent to user
     * 
     * @return mixed User object if found and the token hasn't expired, null otherwise
     */
    protected function getUserOrExit($token)
    {
        $user = UserModel::getUserByPasswordResetToken($token);

        if ($user) {

            return $user;

        } else {

            View::renderTemplate('Password/token_expired.html', [
                'userModel' => Auth::getUser()
            ]);
            exit;

        }

    }



}