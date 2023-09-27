<?php


use App\Config;
use App\Config_prod;

/**
 * FRONT CONTROLLER
 *
 * PHP version 7.4
 */
 ini_set('session.cookie_lifetime', '864000'); // Ten days in seconds


/**
 * Composer - Autoload
 */
require '../vendor/autoload.php';

/** 
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Session
 */
session_start();


/**
 * ENVIRONMENT
 */
if (gethostname() == "p3plzcpnl489517.prod.phx3.secureserver.net") {
    $_SESSION["PROD"] = true;
}
// DEV
else {
    $_SESSION["PROD"] = false;
}


##########################################################################################
##################################### PROD MESSAGES ######################################
##########################################################################################
echo '<span class="badge bg-danger">In Development - DO NOT USE</span>';


/** ######################################################################################
 * #######################################################################################
 * #######################################################################################
 * DATABASE TESTS AND UPDATES
 */

// Test if database exists - If not create
if ($_SESSION["PROD"]) {
    // $servername = Config_prod::PROD_DB_HOST; // MySQL server hostname
    // $username = Config_prod::PROD_DB_USER; // MySQL username
    // $password = Config_prod::PROD_DB_PASSWORD; // MySQL password
    // $dbname_to_check = Config_prod::PROD_DB_NAME; // Database name to check
    $_SESSION["db_servername"] = Config_prod::PROD_DB_HOST; // MySQL server hostname
    $_SESSION["db_username"] = Config_prod::PROD_DB_USER; // MySQL username
    $_SESSION["db_password"] = Config_prod::PROD_DB_PASSWORD; // MySQL password
    $_SESSION["db_name"] = Config_prod::PROD_DB_NAME; // Database name to check
} else {
    // $servername = Config::DB_HOST; // MySQL server hostname
    // $username = Config::DB_USER; // MySQL username
    // $password = Config::DB_PASSWORD; // MySQL password
    // $dbname_to_check = Config::DB_NAME; // Database name to check
    $_SESSION["db_servername"] = Config::DB_HOST; // MySQL server hostname
    $_SESSION["db_username"] = Config::DB_USER; // MySQL username
    $_SESSION["db_password"] = Config::DB_PASSWORD; // MySQL password
    $_SESSION["db_name"] = Config::DB_NAME; // Database name to check
}

// Test SERVER connection
// Create a connection to the MySQL server
$conn = new mysqli($_SESSION["db_servername"], $_SESSION["db_username"] , $_SESSION["db_password"]);

// Check if the connection was successful
if ($conn->connect_error) {
    echo "<br>MySQL Server Connection failed. <br>". $conn->connect_error;
    // die("<br>MySQL Server Connection failed: " . $conn->connect_error);
} else {
    if (!$_SESSION["PROD"]) echo '<br>MySQL Server Connection <span class="badge rounded-pill text-bg-success">Success</span> <br>';
}

// Test DATABASE connection
// Create a connection to the MySQL server
$conn = new mysqli($_SESSION["db_servername"], $_SESSION["db_username"] , $_SESSION["db_password"], $_SESSION["db_name"]);

// Check if the connection was successful
if ($conn->connect_error) {
    echo "Database Connection failed. <br>";
    die("Database Connection failed: " . $conn->connect_error . "<br>");
} else {
    if (!$_SESSION["PROD"])
        echo 'Database Connection <span class="badge rounded-pill text-bg-success">Success</span> <br>';
}

// Verify what version is the database at
// Get last script version/Date in "\Database\Scripts"
$folderPath = '../Database/Scripts/'; // Replace with the path to your folder

if (is_dir($folderPath)) {
    $files = scandir($folderPath);

    // Remove "." and ".." entries from the list
    $files = array_diff($files, ['.', '..']);

    // Sort the array in ascending order
    sort($files);

    // Now, $files contains an array of filenames in the specified folder in ascending order
    foreach ($files as $filename) {

        // For every file, test if file name exists in the database.[_database_updates][last_executed_version]
        $searchString = $filename; // Replace with the string you want to search for

        if (!$_SESSION["PROD"])
            echo "<br>Searching For: " . $searchString . ": - ";

        try {
            // Create a PDO connection to MySQL
            $pdo = new PDO("mysql:host=" . $_SESSION["db_servername"] . ";dbname=" . $_SESSION["db_name"] , $_SESSION["db_username"] , $_SESSION["db_password"] );

            // Set PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL query to check if the string exists in the column
            $sql = "SELECT COUNT(*) as count FROM _database_updates WHERE last_executed_version = :searchString";

            // Prepare the statement
            $stmt = $pdo->prepare($sql);

            // Bind the search string as a parameter
            $stmt->bindParam(':searchString', $searchString, PDO::PARAM_STR);

            // Execute the query
            $stmt->execute();

            // Fetch the result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if the count is greater than zero to determine if the string exists
            if ($result['count'] > 0) {
                if (!$_SESSION["PROD"])
                    echo '<span class="badge bg-success">The string exists in the column</span>';
            } else {
                // Execute the current filename content SQL script

                // IF NOT - Run the script to update the database

                $sqlScriptFile = $folderPath . $filename; // Change this to the path of your SQL script file

                // Create a connection to MySQL
                $mysqli = new mysqli($_SESSION["db_servername"], $_SESSION["db_username"], $_SESSION["db_password"], $_SESSION["db_name"]);

                // Check if the connection was successful
                if ($mysqli->connect_error) {
                    die("Connection failed: " . $mysqli->connect_error);
                }

                // Read the SQL script file
                $sqlScript = file_get_contents($sqlScriptFile);

                // Execute the SQL script
                if ($mysqli->multi_query($sqlScript)) {
                    echo '<br>Database Update - SQL script executed - <span class="badge bg-success">successfully!</span>';

                    // Write this filename into table "last_executed_version"
                    try {
                        
                        // Create a PDO connection to MySQL
                        $pdo = new PDO("mysql:host=" . $_SESSION["db_servername"] . ";dbname=" . $_SESSION["db_name"], $_SESSION["db_username"], $_SESSION["db_password"]);

                        // Set PDO error mode to exception
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Prepare the SQL query to insert the content into the column
                        $sql = "INSERT INTO _database_updates (last_executed_version) VALUES (:filename)";

                        // Prepare the statement
                        $stmt = $pdo->prepare($sql);

                        // Bind the filename as a parameter
                        $stmt->bindParam(':filename', $filename, PDO::PARAM_STR);

                        // Execute the query
                        $stmt->execute();

                        echo "<br>Database Update - Content inserted successfully.";
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }

                } else {
                    if (!$_SESSION["PROD"]) echo "<br>Error executing SQL script: " . $mysqli->error;
                    // IMPORTANT - IF ERROR - BIG PROBLEME - RESTORE DATABASE TO PREVIOUS STATE - NEED DATABASE BACKUPS - THIS SHOULD ONLY BE DONE RIGHT AFTER A BACKUP
                    // EVENTUALLY VERIFY IF THE TIME IS CORRECT AFTER A BACKUP OR EVENTUALLY TEST IF A NEW BACKUP HAS BEEN DONE AND AVAILABLE IN WORST CASE SCENARIO
                    // ALSO SEND IMMEDIATLY MULTIPLE ALETS AND MAILS WITH MESSGAES INFORMINT THE DATABASE UPDATE DID NOT RUN PROPERLY
                }

                // Close the PDO connection
                $pdo = null;

            }


        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // Close the PDO connection
        $pdo = null;

    }

} else {
    echo " <br>The specified folder does not exist.";
}

// Close the MySQL connection
$conn->close();





/**
 * Routing
 */
$router = new Core\Router();

// echo get_class($router);

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);

$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('signup/activate/{token:[\da-f]+}', ['controller' => 'signup', 'action' => 'activate']);

$router->add('{controller}/{action}');

/**
 * Buisness Namespace Controllers
 */
$router->add('buisness/{controller}/{action}', ['namespace' => 'Buisness']);


// $router->add('{controller}/{id:\d+}/{action}');
// $router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

// Display the routing table
/*
echo '<pre>';
var_dump($router->getRoutes());
echo '</pre>';
*/

$router->dispatch($_SERVER['QUERY_STRING']);

// For debug
// phpinfo();
// phpinfo(INFO_MODULES);
if (\App\Config::SHOW_ERRORS) {
    var_dump(get_defined_vars());
}