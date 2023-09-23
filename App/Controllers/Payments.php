<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\PaymentsModel;
use \Core\Authenticated;
use \App\Auth;

/**
 * Posts Controller
 * 
 * PHP version 7.4
 */
class Payments extends Authenticated{


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
    public function indexAction()
    {

        $user = Auth::getUser();  // Get the currently authenticated user
        $userId = $user->id;  // Get the ID of the authenticated user

        $PaymentsModel = new PaymentsModel();
        $Payments = $PaymentsModel -> getPaymentsByUserId($userId);

        View::renderTemplate('Payments/index.html', [
            'Payments' => $Payments
        ]);
    }

    /** Shos the payment Page
     * 
     */
    public function newPaymentAction() {

        View::renderTemplate('Payments/newPayment.html');

    }




}
