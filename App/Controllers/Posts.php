<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\PostModel;

/**
 * Posts Controller
 * 
 * PHP version 7.4
 */
class Posts extends \Core\Controller
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
    public function indexAction()
    {

        $posts = PostModel::getAll();

        View::renderTemplate('Posts/index.html', [
            'userModel' => Auth::getUser(),
            'posts' => $posts
        ]);
    }

    /**
     * Show the add new page
     * 
     * @return void
     */
    public function addNewAction()
    {
        echo 'Hello from the addNew action in the Posts controller!';
    }

    /**
     * Show the edit page
     * 
     * @return void
     */
    public function editAction()
    {
        echo 'Hello from the edit action in the posts controller';
        echo '<p>Route parameters: <pre>' . htmlspecialchars(print_r($this->route_params, true)) . '</pre></p>';
    }
}
