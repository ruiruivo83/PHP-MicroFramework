<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use App\Models\PaymentsModel;
use App\Models\UserModel;
use Core\Control\AuthenticateControl;
use Core\View;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

/**
 * Posts Controller
 * 
 * PHP version 7.4
 */
class Payments extends AuthenticateControl
{

    private $ClientID =      'AUX4tfT1N4gT663UMCQwlOilWqyniR7F_yardreMUMUHI4xI-xQJvsA-Vpmvml6cQIOSNNWinJ-PBl7Z';
    private $ClientSecret =  'EFojir1fiQCVEZociZYAxsH_VnLaqXVr72GLtCAzqRkO2Cw0PqJuBec0q2R816k08esqTWkGFtH9zGz9';


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
        $Payments = $PaymentsModel->getAllPaymentsForUserId($userId);

        View::renderTemplate('Payments/index.html', [
            'userModel' => Auth::getUser(),
            'payments' => $Payments
        ]);
    }

    /** 
     * Shows the payment Page     
     */
    public function newPaymentAction()
    {
        View::renderTemplate('Payments/newPayment.html', [
            'userModel' => Auth::getUser()
        ]);
    }


    /**
     * Create Payment
     */
    public function createPaymentAction()
    {

        $paymentsModel = new PaymentsModel();

        // If there is no active payments, thene you may create one.
        if (! $paymentsModel->testForActivePayment($_SESSION['user_id'])) {

            // Import API context
            $apiContext = new ApiContext(
                new OAuthTokenCredential(
                    $this->ClientID,     // ClientID
                    $this->ClientSecret // ClientSecret
                )
            );

            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $amount = new Amount();
            $amount->setTotal('1') // Amount
                ->setCurrency('EUR');

            $transaction = new Transaction();
            $transaction->setAmount($amount);

            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl('http://jonfenmusic.com/payments/executePayment') // URL to redirect after successful payment
                ->setCancelUrl('http://jonfenmusic.com/payments/cancel'); // URL to redirect after the user cancels the payment

            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions([$transaction])
                ->setRedirectUrls($redirectUrls);

            try {
                $payment->create($apiContext);
                $approvalUrl = $payment->getApprovalLink();

                header("Location: $approvalUrl");
                exit;
            } catch (PayPalConnectionException $ex) {
                // Handle error
                echo "<pre>";
                echo $ex->getData();
                echo "</pre>";

                die($ex);
            }
        } else {
            Flash::addMessage('Payment already in place', Flash::INFO);
             header('Location: /Payments/index');
        }

       
    }


    /**
     * If payment success is successful
     */
    public function executePaymentAction()
    {

        // Import API context
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->ClientID,     // ClientID
                $this->ClientSecret  // ClientSecret
            )
        );


        if (isset($_GET['paymentId']) && isset($_GET['PayerID'])) {
            $paymentId = $_GET['paymentId'];
            $payerId = $_GET['PayerID'];

            $payment = Payment::get($paymentId, $apiContext);

            $execute = new PaymentExecution();
            $execute->setPayerId($payerId);

            try {
                $result = $payment->execute($execute, $apiContext);
                // Payment was successful, you can now process the order

                $expiration_date = time() + 60 * 60 * 24 * 30; // 30 days from now

                $userModel = new UserModel();
                $userModel->createNewUserPayment($expiration_date);
                header('Location: /Payments/index');
            } catch (PayPalConnectionException $ex) {
                // Handle error
                echo $ex->getData();
                die($ex);
            }
        } else {
            // The payment was not successful, you can return an error here
            Flash::addMessage('Payment error, please try again', Flash::WARNING);
        }
    }
}
