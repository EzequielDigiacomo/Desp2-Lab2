<?php // Start PHP script to logically delete a country
session_start(); // Initialize session storage
if (empty($_SESSION['Usuario'])) { // Check if user session variable is not set
    header('Location: login.php'); // Redirect unauthenticated user to login screen
    exit(); // Stop further script execution
} // End login guard check
if ($_SESSION['Usuario_Nivel'] != 1) { // Verify if the logged-in user is not an Administrator (Level 1)
    $_SESSION['Mensaje_Pais'] = "No tienes permisos asignados para eliminar países."; // Set unauthorized access message
    $_SESSION['Estilo_Pais'] = "danger"; // Set danger alert style class
    header('Location: listado_paises.php'); // Redirect unauthorized user back to countries listing
    exit(); // Terminate further script execution
} // End Admin guard check

require_once __DIR__ . '/funciones/conexion.php'; // Include connection builder utility
require_once __DIR__ . '/funciones/library.php'; // Include shared functions library

$vConexion = ConexionBD(); // Establish database connection

if (isset($_GET['id']) && intval($_GET['id']) > 0) { // Check if a valid country ID parameter is provided in the URL
    $idPais = intval($_GET['id']); // Force ID to integer for validation safety
    $exito = BorrarLogico($vConexion, 'paises', $idPais); // Attempt to logically delete country (sets Eliminado flag to 1)
    
    if ($exito) { // Check if logical deletion operation succeeded
        $_SESSION['Mensaje_Pais'] = "El país ha sido eliminado con éxito."; // Set successful feedback message in session
        $_SESSION['Estilo_Pais'] = "success"; // Set success style class
    } else { // Handle database operation failure
        $_SESSION['Mensaje_Pais'] = "No se pudo eliminar el país. Intenta de nuevo."; // Set error feedback message in session
        $_SESSION['Estilo_Pais'] = "danger"; // Set danger alert style class
    } // End execution check
} else { // Handle missing or invalid parameters
    $_SESSION['Mensaje_Pais'] = "Identificador de país no válido."; // Set warning message for invalid parameters
    $_SESSION['Estilo_Pais'] = "warning"; // Set warning alert style class
} // End country ID parameters check

header('Location: listado_paises.php'); // Redirect user back to the list of countries page
exit(); // Stop script execution
?>
