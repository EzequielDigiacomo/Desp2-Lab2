<?php // Inicia el script PHP para limpiar la sesión del usuario
session_start(); // Inicializa la sesión para acceder a las variables activas
session_destroy(); // Destruye todos los datos de la sesión por completo
header('Location: login.php'); // Redirecciona al usuario a la pantalla de inicio de sesión
exit(); // Detiene la ejecución adicional del script
?>
