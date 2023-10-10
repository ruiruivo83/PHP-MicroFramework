<?php

namespace Core;

use App\Models\UserModel;

/**
 * View
 *
 * PHP version 7.4
 */
class View
{

    /**
     * Render a view file
     * 
     * @param string $view The view file
     * 
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view"; // Relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            // echo "$file not found";
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     * 
     * @param string $template The template file
     * @param array $args Associative array of data to display in the view (optional)
     * 
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        echo static::getTemplate($template, $args);
    }



    /**
     * Return a view template using Twig - FOR MAIL PURPOSE - GLOBAL stuff
     * 
     * @param string $template The template file
     * @param array $args Associative array of data to display in the view (optional)
     * 
     * @return void
     */
    public static function getTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('current_user', \App\Auth::getUser());
            $twig->addGlobal('flash_messages', \App\Flash::getMessages());
            $twig->addGlobal('breadcrumbs', \App\Breadcrumbs::getCurPageURL());
        }

        // TODO - Save BreadCrumb to user History
        $userModel = new UserModel();
        $userModel->save_breadcrumb_to_history($_SERVER['REQUEST_URI']);

        return $twig->render($template, $args);
    }

}