<?php // Start PHP script to cancel a project
session_start(); // Initialize session storage
if (empty($_SESSION['Usuario'])) { // Check if user session variable is not set
    header('Location: login.php'); // Redirect unauthenticated user to login screen
    exit(); // Stop further script execution
} // End login guard check
if ($_SESSION['Usuario_Nivel'] != 1) { // Verify if the logged-in user is not an Administrator (Level 1)
    $_SESSION['Mensaje_Proyecto'] = "No tienes permisos asignados para cancelar proyectos."; // Set unauthorized access message
    $_SESSION['Estilo_Proyecto'] = "danger"; // Set danger alert style class
    header('Location: listado_proyectos.php'); // Redirect unauthorized user back to projects listing
    exit(); // Terminate further script execution
} // End Admin guard check

require_once __DIR__ . '/funciones/conexion.php'; // Include connection builder utility
require_once __DIR__ . '/funciones/library.php'; // Include shared functions library

$vConexion = ConexionBD(); // Establish database connection

if (isset($_GET['id']) && intval($_GET['id']) > 0) { // Check if a valid project ID parameter is provided in the URL
    $idProyecto = intval($_GET['id']); // Force ID to integer for validation safety
    $exito = Cancelar_Proyecto($vConexion, $idProyecto); // Attempt to change the project status to Cancelado (4)
    
    if ($exito) { // Check if update operation succeeded
        $_SESSION['Mensaje_Proyecto'] = "El proyecto ha sido cancelado con éxito."; // Set successful feedback message in session
        $_SESSION['Estilo_Proyecto'] = "success"; // Set success style class
    } else { // Handle database update failure
        $_SESSION['Mensaje_Proyecto'] = "No se pudo cancelar el proyecto. Intenta de nuevo."; // Set error feedback message in session
        $_SESSION['Estilo_Proyecto'] = "danger"; // Set danger alert style class
    } // End execution check
} else { // Handle missing or invalid parameters
    $_SESSION['Mensaje_Proyecto'] = "Identificador de proyecto no válido."; // Set warning message for invalid parameters
    $_SESSION['Estilo_Proyecto'] = "warning"; // Set warning alert style class
} // End project ID parameters check

header('Location: listado_proyectos.php'); // Redirect user back to the list of projects page
exit(); // Stop script execution
?>
