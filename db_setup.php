<?php 

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 

$link = mysqli_connect('localhost', 'root', ''); 
if (!$link) { 
    die('Could not connect to MySQL: ' . mysqli_connect_error()); 
} 

$sql_db = "CREATE DATABASE IF NOT EXISTS `consultora` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci"; 
if (mysqli_query($link, $sql_db)) { 
    echo "Database consultora created or already exists.\n"; 
} else { 
    die('Error creating database: ' . mysqli_error($link)); 
} 

if (!mysqli_select_db($link, 'consultora')) { 
    die('Error selecting database: ' . mysqli_error($link)); 
} 

$table_check = mysqli_query($link, "SHOW TABLES LIKE 'roles'"); 
if (mysqli_num_rows($table_check) == 0) { 
    echo "Importing base schema from consultora.sql...\n"; 
    $base_sql = file_get_contents('consultora.sql'); 
    if ($base_sql === false) { 
        die('Error reading consultora.sql'); 
    } 
    if (mysqli_multi_query($link, $base_sql)) { 
        do { 
            if ($result = mysqli_store_result($link)) { 
                mysqli_free_result($result); 
            } 
        } while (mysqli_next_result($link)); 
        echo "Base schema imported successfully.\n"; 
    } else { 
        die('Error importing base schema: ' . mysqli_error($link)); 
    } 
} else { 
    echo "Base schema already present. Skipping consultora.sql import.\n"; 
} 

mysqli_close($link); 
$link = mysqli_connect('localhost', 'root', '', 'consultora'); 
if (!$link) { 
    die('Could not reconnect to database: ' . mysqli_connect_error()); 
} 

echo "Importing table extensions from consultora_tablas.sql...\n"; 
$ext_sql = file_get_contents('consultora_tablas.sql'); 
if ($ext_sql === false) { 
    die('Error reading consultora_tablas.sql'); 
} 
if (mysqli_multi_query($link, $ext_sql)) { 
    do { 
        if ($result = mysqli_store_result($link)) { 
            mysqli_free_result($result); 
        } 
    } while (mysqli_next_result($link)); 
    echo "Table extensions and sample data imported successfully.\n"; 
} else { 
    die('Error importing table extensions: ' . mysqli_error($link)); 
} 

mysqli_close($link); 
echo "Database setup completed successfully!\n"; 
?>
