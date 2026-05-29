<?php // Start PHP script to setup database
// Enable error reporting to diagnose issues
ini_set('display_errors', 1); // Enable display of errors on page
ini_set('display_startup_errors', 1); // Enable display of startup errors
error_reporting(E_ALL); // Report all types of errors

// Connect to MariaDB/MySQL server without database parameter first
$link = mysqli_connect('localhost', 'root', ''); // Attempt connection to MySQL server
if (!$link) { // Check if connection failed
    die('Could not connect to MySQL: ' . mysqli_connect_error()); // Terminate and show error
} // End connection check

// Create the database if it does not exist
$sql_db = "CREATE DATABASE IF NOT EXISTS `consultora` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci"; // DB creation query
if (mysqli_query($link, $sql_db)) { // Execute database creation query
    echo "Database consultora created or already exists.\n"; // Output success message
} else { // Handle database creation failure
    die('Error creating database: ' . mysqli_error($link)); // Terminate and show error
} // End DB creation check

// Select the database
if (!mysqli_select_db($link, 'consultora')) { // Attempt to select database
    die('Error selecting database: ' . mysqli_error($link)); // Terminate on failure
} // End select DB check

// Check if basic tables already exist, if not, import consultora.sql
$table_check = mysqli_query($link, "SHOW TABLES LIKE 'roles'"); // Check if table roles exists
if (mysqli_num_rows($table_check) == 0) { // If table does not exist
    echo "Importing base schema from consultora.sql...\n"; // Output status update
    $base_sql = file_get_contents(__DIR__ . '/consultora.sql'); // Read base schema file
    if ($base_sql === false) { // Check if file could not be read
        die('Error reading consultora.sql'); // Terminate on read failure
    } // End read check
    if (mysqli_multi_query($link, $base_sql)) { // Execute multi-query import
        do { // Loop through all query statements
            if ($result = mysqli_store_result($link)) { // Store result if any
                mysqli_free_result($result); // Free result set
            } // End store check
        } while (mysqli_next_result($link)); // Proceed to next query statement
        echo "Base schema imported successfully.\n"; // Output success update
    } else { // Handle base schema query failures
        die('Error importing base schema: ' . mysqli_error($link)); // Terminate and show error
    } // End multi-query execution
} else { // If table roles already exists
    echo "Base schema already present. Skipping consultora.sql import.\n"; // Output status skip
} // End table existence check

// Re-connect to ensure previous multi-queries are clean and closed
mysqli_close($link); // Close database connection
$link = mysqli_connect('localhost', 'root', '', 'consultora'); // Open fresh connection
if (!$link) { // Check if fresh connection failed
    die('Could not reconnect to database: ' . mysqli_connect_error()); // Terminate on failure
} // End fresh connection check

// Import the tables extension and sample data
echo "Importing table extensions from consultora_tablas.sql...\n"; // Output status update
$ext_sql = file_get_contents(__DIR__ . '/consultora_tablas.sql'); // Read extension schema file
if ($ext_sql === false) { // Check if file could not be read
    die('Error reading consultora_tablas.sql'); // Terminate on read failure
} // End read check
if (mysqli_multi_query($link, $ext_sql)) { // Execute multi-query extension import
    do { // Loop through all queries
        if ($result = mysqli_store_result($link)) { // Store result if any
            mysqli_free_result($result); // Free result set
        } // End store check
    } while (mysqli_next_result($link)); // Proceed to next query statement
    echo "Table extensions and sample data imported successfully.\n"; // Output success message
} else { // Handle extension schema query failures
    die('Error importing table extensions: ' . mysqli_error($link)); // Terminate and show error
} // End multi-query execution

mysqli_close($link); // Close database connection
echo "Database setup completed successfully!\n"; // Output final completion message
?>
