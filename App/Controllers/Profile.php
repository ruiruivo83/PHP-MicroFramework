<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use Core\View;

/**
 * Profile controller
 * 
 * PHP version 7.4
 */
class Profile extends Authenticated
{

    /**
     * Show the profile
     * 
     * @return void
     */
    public function showAction()
    {
        View::renderTemplate('Profile/show.html.twig', [
            'userModel' => Auth::getUser()]);
    }

    /**
     * Show the form for editing the profile
     * 
     * @return void
     */
    public function editAction()
    {
        View::renderTemplate('Profile/edit.html.twig', [
            'userModel' => Auth::getUser()
        ]);
    }

    /**
     * Update the profile
     * 
     * @return void
     */
    public function updateAction()
    {
        $user = Auth::getUser();

        if ($user->updateProfile($_POST)) {
            Flash::addMessage('Changes saved');
            $this->redirect('/profile/show');
        } else {

            View::renderTemplate('Profile/edit.html.twig', ['userModel' => $user]);

        }
    }

}