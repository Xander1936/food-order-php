<?php 
    // Create Constants to store non Repeating Values; CONSTANTS are always named with Capital Letters and Variables with small letters.
    //3. Execute Query and Save Data in Database
        define('LOCALHOST',  'localhost');
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', '');
        define('DB_NAME', 'food-order');
        
        $conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die(mysqli_error()); // Database Connection
        $db_select = mysqli_select_db($conn, DB_NAME) or die(mysqli_error()); // Selecting Database

?>