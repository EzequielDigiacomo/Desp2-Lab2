<?php // Start PHP script for database connection utility
// Define the ConexionBD function to connect to the MySQL database
function ConexionBD($Host = 'localhost', $User = 'root', $Password = '', $BaseDeDatos = 'consultora') { // Function definition with default parameters
    $linkConexion = mysqli_connect($Host, $User, $Password, $BaseDeDatos); // Attempt connection with parameters
    if ($linkConexion != false) { // Verify if connection was successful
        return $linkConexion; // Return connection link if successful
    } else { // Handle connection failure
        die('No se pudo establecer la conexión con la base de datos.'); // Terminate script with error message
    } // End verification
} // End ConexionBD function definition
?>
