<?php

namespace App;

use App\Models\RememberedLoginModel;
use App\Models\UserModel;

/**
 * Authentication
 * 
 * PHP version 7.4
 */
class Breadcrums
{

    public static function getCurPageURL() {        

        // Get the current URI
        $currentURI = $_SERVER['REQUEST_URI'];

        // Explode it into its components
        $uriComponents = explode("/", $currentURI);

        // Start building the breadcrumb
        $string = '<nav aria-label="breadcrumb" style="padding-left: 15px; padding-right: 15px;">';
        $string .= '<ol class="breadcrumb border shadow rounded">';

        // Loop through the URI components and display each as a part of the breadcrumb
        for ($i = 0; $i < count($uriComponents); $i++) {
            
            $path = implode('/', array_slice($uriComponents, 0, $i + 1));
            
            if ($i === count($uriComponents) - 1) {
                // If it's the last element, it's the current page.
                $string .= '<li class="breadcrumb-item active" aria-current="page">' . ucfirst($uriComponents[$i]) . '</li>';
            } else {
                // Otherwise, it's a parent page and should link there.
                $string .= '<li class="breadcrumb-item"><a href="' . $path . "/index" . '">' . ucfirst($uriComponents[$i]) . '</a></li>';
            }
        }

        $string .= '</ol>';
        $string .= '</nav>';

        return $string;

    }
    
}