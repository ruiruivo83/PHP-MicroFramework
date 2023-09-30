<?php

namespace App\Controllers;

use App\Auth;
use App\Models\PaymentsModel;
use App\Models\UserModel;
use Core\Control\SysAdminControl;
use Core\View;

/**
 * Items controller (example)
 * 
 * PHP version 7.4
 */
class SystemStats extends SysAdminControl
{

    /**
     * System Stats - index
     * 
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('SystemStats/index.html');
    }


    /**
     * System Stats - Table Users
     *
     * @return void
     */
    public function tableUsersAction()
    {

        $userModel = new UserModel();
        $users = $userModel->getAllUsers();

        View::renderTemplate('SystemStats/tableUsers.html', [
            'userModel' => Auth::getUser(),
            'users' => $users
        ]);
    }

    /**
     * System Stats - Table Users
     *
     * @return void
     */
    public function tablePaymentsAction()
    {
        $paymentModel = new PaymentsModel();
        $payments = $paymentModel->getAllPayments();

        View::renderTemplate('SystemStats/tablePayments.html', [
            'userModel' => Auth::getUser(),
            'payments' => $payments
        ]);
    }

}