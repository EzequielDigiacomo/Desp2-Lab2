<?php // Inicia el script PHP para cancelar un proyecto
session_start(); // Inicializa el almacenamiento de sesiones
if (empty($_SESSION['Usuario'])) { // Verifica si la variable de sesión del usuario no está configurada
    header('Location: login.php'); // Redirecciona al usuario no autenticado a la pantalla de inicio de sesión
    exit(); // Detiene la ejecución del script
} // Fin del control de acceso de inicio de sesión
if ($_SESSION['Usuario_Nivel'] != 1) { // Verifica si el usuario autenticado no es un Administrador (Nivel 1)
    $_SESSION['Mensaje_Proyecto'] = "No tienes permisos asignados para cancelar proyectos."; // Define el mensaje de acceso no autorizado
    $_SESSION['Estilo_Proyecto'] = "danger"; // Define la clase de estilo de alerta como 'danger'
    header('Location: listado_proyectos.php'); // Redirecciona al usuario no autorizado de vuelta al listado de proyectos
    exit(); // Finaliza la ejecución del script
} // Fin del control de acceso del Administrador

require_once __DIR__ . '/funciones/conexion.php'; // Incluye la utilidad de conexión a la base de datos
require_once __DIR__ . '/funciones/library.php'; // Incluye la biblioteca de funciones compartidas

$vConexion = ConexionBD(); // Establece la conexión a la base de datos

if (isset($_GET['id']) && intval($_GET['id']) > 0) { // Verifica si se proporciona un parámetro de ID de proyecto válido en la URL
    $idProyecto = intval($_GET['id']); // Fuerza el ID a entero para mayor seguridad en la validación
    $exito = Cancelar_Proyecto($vConexion, $idProyecto); // Intenta cambiar el estado del proyecto a Cancelado (4)
    
    if ($exito) { // Verifica si la operación de actualización fue exitosa
        $_SESSION['Mensaje_Proyecto'] = "El proyecto ha sido cancelado con éxito."; // Guarda el mensaje de éxito en la sesión
        $_SESSION['Estilo_Proyecto'] = "success"; // Define la clase de estilo como 'success'
    } else { // Manejo de fallo en la actualización de la base de datos
        $_SESSION['Mensaje_Proyecto'] = "No se pudo cancelar el proyecto. Intenta de nuevo."; // Guarda el mensaje de error en la sesión
        $_SESSION['Estilo_Proyecto'] = "danger"; // Define la clase de estilo como 'danger'
    } // Fin del control de ejecución
} else { // Manejo de parámetros faltantes o no válidos
    $_SESSION['Mensaje_Proyecto'] = "Identificador de proyecto no válido."; // Define el mensaje de advertencia para parámetros no válidos
    $_SESSION['Estilo_Proyecto'] = "warning"; // Define la clase de estilo como 'warning'
} // Fin de la verificación de parámetros del ID del proyecto

header('Location: listado_proyectos.php'); // Redirecciona al usuario de vuelta a la página del listado de proyectos
exit(); // Detiene la ejecución del script
?>
