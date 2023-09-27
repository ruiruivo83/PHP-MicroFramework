<?php

namespace App\Controllers;

use Core\Authenticated;
use \Core\View;
use App\Models\UserModel;
use App\Models\TimeZoneModel;


/**
 * Posts Controller
 * 
 * PHP version 7.4
 */
class Timezones extends Authenticated
{


    /**
     * Before filter - called before an action method.
     * 
     * @return void
     */
    /*
    protected function Before()
    {
        echo " (before in \Controllers\Posts)";
        // return false; // Will stop the execution
    }
    */

    /**
     * After filter - called after an action method.
     * 
     * @return void
     */
    /*
    protected function After()
    {
        echo " (after in \Controllers\Posts)";
    }
    */


    /**
     * Show the index page
     * 
     * @return void
     */
    public function editAction()
    {

        $timezones = TimeZoneModel::getAllTimeZones();

        View::renderTemplate('Timezones/edit.html', [
            'timezones' => $timezones
        ]);
    }

    /**
     * Set specific timezone for user
     * 
     * @return void
     */
    public function makeactiveAction() {
        
        $userModel = new UserModel();
        $userModel->setTimeZone();

        $this->redirect('/profile/index');

    }

}
