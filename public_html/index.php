<?php

use App\Config;
use App\Config_prod;

/**
 * FRONT CONTROLLER
 *
 * PHP version 7.4
 */
ini_set('session.cookie_lifetime', '864000');

require '../vendor/autoload.php';

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();

setEnvironment();
setDatabaseConfig();
establishConnection();

// Define the folder path
$folderPath = '../Database/Scripts/';
checkAndRunDatabaseUpdates($folderPath);

$router = new Core\Router();
setRoutes($router);
$router->dispatch($_SERVER['QUERY_STRING']);

function setEnvironment()
{
    // Test if the string contains a specific string inside the host name
    $_SESSION["PROD"] = (strpos(gethostname(), "hosting.ovh.net") !== false);

    echo '<span class="badge bg-danger">In Development - DO NOT USE</span>';
}

function setDatabaseConfig()
{
    if ($_SESSION["PROD"]) {
        $_SESSION["db_servername"] = Config_prod::PROD_DB_HOST;
        $_SESSION["db_username"] = Config_prod::PROD_DB_USER;
        $_SESSION["db_password"] = Config_prod::PROD_DB_PASSWORD;
        $_SESSION["db_name"] = Config_prod::PROD_DB_NAME;
    } else {
        $_SESSION["db_servername"] = Config::DB_HOST;
        $_SESSION["db_username"] = Config::DB_USER;
        $_SESSION["db_password"] = Config::DB_PASSWORD;
        $_SESSION["db_name"] = Config::DB_NAME;
    }
}

function establishConnection()
{

    // Test SERVER connection
    $conn = new mysqli($_SESSION["db_servername"], $_SESSION["db_username"], $_SESSION["db_password"]);

    if ($conn->connect_error) {
        die("<br>MySQL Server Connection failed: " . $conn->connect_error);
    } elseif (!$_SESSION["PROD"]) { // Show info - Only if not in prod
        echo '<br>MySQL Server Connection <span class="badge rounded-pill text-bg-success">Success</span> <br>';
    }

    // Check if database exists
    $db_selected = mysqli_select_db($conn, $_SESSION["db_name"]);

    if (!$db_selected) {
        // Database does not exist, so create it

        // QUERY - Create database
        $sql = "CREATE DATABASE " .  $_SESSION["db_name"] . ";";

        if ($conn->query($sql) === TRUE) {
            echo "Database created successfully";
            // Select the created database
            $conn->select_db($_SESSION["db_name"]);
        } else {
            die("Error creating database: " . $conn->error);
        }

        // QUERY - Create AutoUpdate table
        $sql = "CREATE TABLE _database_updates (id INT AUTO_INCREMENT PRIMARY KEY, last_executed_version CHAR(255) );";

        if ($conn->query($sql) === TRUE) {
            echo "AutoUpdate table created successfully";
            // Select the created database
            $conn->select_db($_SESSION["db_name"]);
        } else {
            die("Error AutoUpdate table: " . $conn->error);
        }
    } else {
        echo "Database exists";
    }

    // Close the MySQL connection
    $conn->close();
}

function checkAndRunDatabaseUpdates($folderPath)
{


    if (is_dir($folderPath)) {
        $files = scandir($folderPath);
        $files = array_diff($files, ['.', '..']);
        sort($files);

        foreach ($files as $filename) {

            try {
                $pdo = new PDO(
                    "mysql:host=" . $_SESSION["db_servername"] . ";dbname=" . $_SESSION["db_name"],
                    $_SESSION["db_username"],
                    $_SESSION["db_password"]
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "SELECT COUNT(*) as count FROM _database_updates WHERE last_executed_version = :searchString";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':searchString', $filename, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result['count'] == 0) {
                    $sqlScriptFile = $folderPath . $filename;
                    $mysqli = new mysqli($_SESSION["db_servername"], $_SESSION["db_username"], $_SESSION["db_password"], $_SESSION["db_name"]);
                    $sqlScript = file_get_contents($sqlScriptFile);

                    if ($mysqli->multi_query($sqlScript)) {
                        echo '<br>Database Update - SQL script executed - <span class="badge bg-success">successfully!</span>';
                        $sql = "INSERT INTO _database_updates (last_executed_version) VALUES (:filename)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':filename', $filename, PDO::PARAM_STR);
                        $stmt->execute();
                        echo "<br>Database Update - Content inserted successfully.";
                    } else {
                        echo "<br>Error executing SQL script: " . $sqlScript . " - " . $mysqli->error;
                    }
                }
            } catch (PDOException $e) {
                echo "Error in file: " . $files . " - " . $e->getMessage();
            }
        }
    } else {
        echo " <br>The specified folder does not exist.";
    }
}

function setRoutes($router)
{
    $router->add('', ['controller' => 'Home', 'action' => 'index']);
    $router->add('login', ['controller' => 'Login', 'action' => 'new']);
    $router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
    $router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
    $router->add('signup/activate/{token:[\da-f]+}', ['controller' => 'signup', 'action' => 'activate']);
    $router->add('{controller}/{action}');
    // $router->add('buisness/{controller}/{action}', ['namespace' => 'Buisness']);
}
