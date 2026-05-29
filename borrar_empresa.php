<?php // Start PHP script to logically delete a company
session_start(); // Initialize session storage
if (empty($_SESSION['Usuario'])) { // Check if user session variable is not set
    header('Location: login.php'); // Redirect unauthenticated user to login screen
    exit(); // Stop further script execution
} // End login guard check
if ($_SESSION['Usuario_Nivel'] != 1) { // Verify if the logged-in user is not an Administrator (Level 1)
    $_SESSION['Mensaje_Empresa'] = "No tienes permisos asignados para eliminar empresas."; // Set unauthorized access message
    $_SESSION['Estilo_Empresa'] = "danger"; // Set danger alert style class
    header('Location: listado_empresas.php'); // Redirect unauthorized user back to companies listing
    exit(); // Terminate further script execution
} // End Admin guard check

require_once __DIR__ . '/funciones/conexion.php'; // Include connection builder utility
require_once __DIR__ . '/funciones/library.php'; // Include shared functions library

$vConexion = ConexionBD(); // Establish database connection

if (isset($_GET['id']) && intval($_GET['id']) > 0) { // Check if a valid company ID parameter is provided in the URL
    $idEmpresa = intval($_GET['id']); // Force ID to integer for validation safety
    $exito = BorrarLogico($vConexion, 'empresas', $idEmpresa); // Attempt to logically delete company (sets Eliminado flag to 1)
    
    if ($exito) { // Check if logical deletion operation succeeded
        $_SESSION['Mensaje_Empresa'] = "La empresa ha sido eliminada con éxito."; // Set successful feedback message in session
        $_SESSION['Estilo_Empresa'] = "success"; // Set success style class
    } else { // Handle database operation failure
        $_SESSION['Mensaje_Empresa'] = "No se pudo eliminar la empresa. Intenta de nuevo."; // Set error feedback message in session
        $_SESSION['Estilo_Empresa'] = "danger"; // Set danger alert style class
    } // End execution check
} else { // Handle missing or invalid parameters
    $_SESSION['Mensaje_Empresa'] = "Identificador de empresa no válido."; // Set warning message for invalid parameters
    $_SESSION['Estilo_Empresa'] = "warning"; // Set warning alert style class
} // End company ID parameters check

header('Location: listado_empresas.php'); // Redirect user back to the list of companies page
exit(); // Stop script execution
?>
