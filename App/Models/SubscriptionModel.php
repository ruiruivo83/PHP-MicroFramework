<?php

namespace App\Models;

use App\Auth;
use App\Mail;
use App\Token;
use Core\View;
use Error;
use PDO;

/**
 * Example user model
 * 
 * PHP version 7.4
 */
class SubscriptionModel extends \Core\Model
{

    /**
     * Test if user has Subscription
     * 
     * @return boolean True if the user has Subscription, false otherwise
     */
    public function testForActiveSubscription()
    {

        $user = Auth::getUser();        
        $db = static::getDB();

        $stmt = $db->query('SELECT Subscription_expires_at FROM `users` WHERE id = ' . $user->id);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If current time > Subscription_expires_at return false

        // FIX ME - Nust be a better way to return this properly
        if ($result = NULL) {
            return false;
        } else {
           if (time() > $result) {
            false;
           } else {
            return true;
           }
        }
    }


}