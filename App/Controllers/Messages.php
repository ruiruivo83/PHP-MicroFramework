<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use Core\View;
use Core\Authenticated;

use App\Models\TimeZoneModel;

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
    public function indexAction()
    {

        View::renderTemplate('Messages/index.html', [
            'userModel' => Auth::getUser()]);
            
    }


}