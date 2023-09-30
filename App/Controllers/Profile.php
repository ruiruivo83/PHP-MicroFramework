<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use App\Models\TimeZoneModel;
use Core\Control\AuthenticateControl;
use Core\View;

/**
 * Profile controller
 * 
 * PHP version 7.4
 */
class Profile extends AuthenticateControl
{

    /**
     * Show the profile
     * 
     * @return void
     */
    public function indexAction()
    {

        View::renderTemplate('Profile/index.html', [
            'userModel' => Auth::getUser()]);
    }

    /**
     * Show the form for editing the profile
     * 
     * @return void
     */
    public function editAction()
    {
               

        View::renderTemplate('Profile/edit.html', [
            'userModel' => Auth::getUser()
        ]);
    }

    /**
     * Show the form for editing the profile
     * 
     * @return void
     */
    public function timezonesAction()
    {
        $timeZoneModel = new TimeZoneModel();
        $timeZones = $timeZoneModel->getTimeZones();

        View::renderTemplate('TimeZones/index.html', [
            'userModel' => Auth::getUser(),
            'TimeZoneJson' => $timeZones
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

        if ($user->updateUserProfile($_POST)) {
            Flash::addMessage('Changes saved');
            $this->redirect('/profile/index');
        } else {

            View::renderTemplate('Profile/edit.html', ['userModel' => $user]);

        }
    }

}