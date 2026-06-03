<?php // Inicia el script PHP para eliminar lógicamente un usuario
session_start(); // Inicializa el almacenamiento de sesiones
if (empty($_SESSION['Usuario'])) { // Verifica si la variable de sesión del usuario no está configurada
    header('Location: login.php'); // Redirecciona al usuario no autenticado a la pantalla de inicio de sesión
    exit(); // Detiene la ejecución del script
} // Fin del control de acceso de inicio de sesión
if ($_SESSION['Usuario_Nivel'] != 1) { // Verifica si el usuario autenticado no es un Administrador (Nivel 1)
    $_SESSION['Mensaje_Usuario'] = "No tienes permisos asignados para eliminar usuarios."; // Define el mensaje de acceso no autorizado
    $_SESSION['Estilo_Usuario'] = "danger"; // Define la clase de estilo de alerta como 'danger'
    header('Location: listado_usuarios.php'); // Redirecciona al usuario no autorizado de vuelta al listado de usuarios
    exit(); // Finaliza la ejecución del script
} // Fin del control de acceso del Administrador

require_once 'funciones/conexion.php'; // Incluye la utilidad de conexión a la base de datos
require_once 'funciones/library.php'; // Incluye la biblioteca de funciones compartidas

$vConexion = ConexionBD(); // Establece la conexión a la base de datos

if (isset($_GET['id']) && intval($_GET['id']) > 0) { // Verifica si se proporciona un parámetro de ID de usuario válido en la URL
    $idUsuario = intval($_GET['id']); // Fuerza el ID a entero para mayor seguridad en la validación
    $exito = BorrarLogico($vConexion, 'usuarios', $idUsuario); // Intenta eliminar lógicamente al usuario (bandera Eliminado a 1, Activo a 0)
    
    if ($exito) { // Verifica si la operación de borrado lógico fue exitosa
        $_SESSION['Mensaje_Usuario'] = "El usuario ha sido eliminado con éxito."; // Guarda el mensaje de éxito en la sesión
        $_SESSION['Estilo_Usuario'] = "success"; // Define la clase de estilo como 'success'
    } else { // Manejo de fallo en la operación de base de datos
        $_SESSION['Mensaje_Usuario'] = "No se pudo eliminar el usuario. Intenta de nuevo."; // Guarda el mensaje de error en la sesión
        $_SESSION['Estilo_Usuario'] = "danger"; // Define la clase de estilo como 'danger'
    } // Fin del control de ejecución
} else { // Manejo de parámetros faltantes o no válidos
    $_SESSION['Mensaje_Usuario'] = "Identificador de usuario no válido."; // Define el mensaje de advertencia para parámetros no válidos
    $_SESSION['Estilo_Usuario'] = "warning"; // Define la clase de estilo como 'warning'
} // Fin de la verificación de parámetros del ID del usuario

header('Location: listado_usuarios.php'); // Redirecciona al usuario de vuelta a la página del listado de usuarios
exit(); // Detiene la ejecución del script
?>
