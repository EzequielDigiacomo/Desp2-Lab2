<?php // Inicia el script PHP para configurar la base de datos
// Habilita el reporte de errores para diagnosticar problemas
ini_set('display_errors', 1); // Habilita la visualización de errores en la página
ini_set('display_startup_errors', 1); // Habilita la visualización de errores de inicio
error_reporting(E_ALL); // Reporta todos los tipos de errores

// Conecta al servidor MariaDB/MySQL sin el parámetro de base de datos primero
$link = mysqli_connect('localhost', 'root', ''); // Intenta la conexión al servidor MySQL
if (!$link) { // Verifica si la conexión falló
    die('Could not connect to MySQL: ' . mysqli_connect_error()); // Termina y muestra el error
} // Fin del chequeo de conexión

// Crea la base de datos si no existe
$sql_db = "CREATE DATABASE IF NOT EXISTS `consultora` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci"; // Consulta de creación de base de datos
if (mysqli_query($link, $sql_db)) { // Ejecuta la consulta de creación de base de datos
    echo "Database consultora created or already exists.\n"; // Muestra un mensaje de éxito
} else { // Manejo de fallas en la creación de la base de datos
    die('Error creating database: ' . mysqli_error($link)); // Termina y muestra el error
} // Fin del chequeo de creación de base de datos

// Selecciona la base de datos
if (!mysqli_select_db($link, 'consultora')) { // Intenta seleccionar la base de datos
    die('Error selecting database: ' . mysqli_error($link)); // Termina en caso de fallo
} // Fin del chequeo de selección de base de datos

// Verifica si las tablas básicas ya existen, si no, importa consultora.sql
$table_check = mysqli_query($link, "SHOW TABLES LIKE 'roles'"); // Verifica si la tabla roles existe
if (mysqli_num_rows($table_check) == 0) { // Si la tabla no existe
    echo "Importing base schema from consultora.sql...\n"; // Muestra el estado de la importación
    $base_sql = file_get_contents('consultora.sql'); // Lee el archivo del esquema base
    if ($base_sql === false) { // Verifica si no se pudo leer el archivo
        die('Error reading consultora.sql'); // Termina en caso de fallo en la lectura
    } // Fin del chequeo de lectura
    if (mysqli_multi_query($link, $base_sql)) { // Ejecuta la importación con multi-consulta
        do { // Bucle para procesar todas las sentencias de consulta
            if ($result = mysqli_store_result($link)) { // Almacena el resultado si existe alguno
                mysqli_free_result($result); // Libera el conjunto de resultados
            } // Fin del chequeo de almacenamiento
        } while (mysqli_next_result($link)); // Pasa a la siguiente sentencia de consulta
        echo "Base schema imported successfully.\n"; // Muestra mensaje de éxito de importación
    } else { // Manejo de fallas en las consultas del esquema base
        die('Error importing base schema: ' . mysqli_error($link)); // Termina y muestra el error
    } // Fin del envío de la multi-consulta
} else { // Si la tabla roles ya existe
    echo "Base schema already present. Skipping consultora.sql import.\n"; // Muestra estado de omisión
} // Fin del chequeo de existencia de tablas

// Vuelve a conectar para asegurar que las multi-consultas previas estén limpias y cerradas
mysqli_close($link); // Cierra la conexión a la base de datos
$link = mysqli_connect('localhost', 'root', '', 'consultora'); // Abre una nueva conexión
if (!$link) { // Verifica si la nueva conexión falló
    die('Could not reconnect to database: ' . mysqli_connect_error()); // Termina en caso de fallo
} // Fin del chequeo de reconexión

// Importa la extensión de tablas y los datos de prueba
echo "Importing table extensions from consultora_tablas.sql...\n"; // Muestra estado de la importación
$ext_sql = file_get_contents('consultora_tablas.sql'); // Lee el archivo de extensión del esquema
if ($ext_sql === false) { // Verifica si el archivo no pudo ser leído
    die('Error reading consultora_tablas.sql'); // Termina en caso de fallo en la lectura
} // Fin del chequeo de lectura
if (mysqli_multi_query($link, $ext_sql)) { // Ejecuta la importación de extensión con multi-consulta
    do { // Bucle para recorrer todas las consultas
        if ($result = mysqli_store_result($link)) { // Almacena el resultado si existe alguno
            mysqli_free_result($result); // Libera el conjunto de resultados
        } // Fin del chequeo de almacenamiento
    } while (mysqli_next_result($link)); // Pasa a la siguiente sentencia de consulta
    echo "Table extensions and sample data imported successfully.\n"; // Muestra mensaje de éxito
} else { // Manejo de fallas en las consultas de extensión
    die('Error importing table extensions: ' . mysqli_error($link)); // Termina y muestra el error
} // Fin de la ejecución de la multi-consulta

mysqli_close($link); // Cierra la conexión a la base de datos
echo "Database setup completed successfully!\n"; // Muestra mensaje final de finalización
?>
