<?php

namespace Core;

use App\Auth;
use App\Subscription;
use App\Flash;

use App\Models\UserModel;


/**
 * Base controller
 * 
 * PHP version 7.4
 */
abstract class Controller
{

    /**
     * Parameters from the matched route
     * 
     * @var array
     */
    protected $route_params = [];

    /**
     * Class constructor
     * 
     * @param array $route_params Parameters from the route
     * 
     * @return void
     */
    public function __construct($route_params)
    {
        $this->route_params = $route_params;

        // TIMEZONE
        if (isset($_SESSION["user_id"])) {
            $userModel = new UserModel();
            $timeZone = $userModel->getUserTimeZone();
            date_default_timezone_set($timeZone);

            if (! $_SESSION["PROD"]) {
                $timezone = date_default_timezone_get();
                echo "<br>Current Time Zone: " . $timezone;
            }

        }


    }

    /**
     * 
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action';

        // The "$this" refers to the instance of this class, in other words the object running this code
        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            // echo "Method $method not found in controller " . get_class($this);
            throw new \Exception("Method $method not found in controller" . get_class($this));
        }
    }

    /**
     * Before filter - called before an action method.
     * 
     * @return void
     */
    protected function Before()
    {
       
        echo "<div style=\"color:#f70202; border-style: solid;\"> ACTION FILTER - (before in Core\Controller) </div>";
        // return false; // Will stop the execution
    }

    /**
     * After filter - called after an action method.
     * 
     * @return void
     */
    protected function After()
    {
        echo "<div style=\"color:#f70202; border-style: solid;\"> ACTION FILTER - (after in Core\Controller) </div>";
    }

    /**
     * Redirect to a different page
     * 
     * @param string $url The relative URL
     * 
     * @return void
     */
    public function redirect($url)
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
        exit;
    }

    /**
     * Require the user to be logged in before giving access to the requested page.
     * Remember the requested page for later, then redirect to the login page.
     * 
     * @return void
     */
    public function requireLogin()
    {
         // AUTHENTICATE - Protection d'une page
         if (! Auth::getUser()) {

            Flash::addMessage('Please login to access that page');
            
            Auth::rememberRequestedPage();

            $this->redirect('/login');
        }

    }


        /**
     * Require the user to an active Subscription on the platforma to access this page.
     * Remember the requested page for later, then redirect to the login page.
     * 
     * @return void
     */
    public function requireSubscription()
    {
         // SubscriptionS - Protection d'une page
         if (! Subscription::hasSubscription()) {

            Flash::addMessage('Please activate your Subscription to access this page.');
            
            Auth::rememberRequestedPage();

            $this->redirect('/Courses/index');
        }

    }

}