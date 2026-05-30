<?php // Inicia el archivo PHP de inclusión de cabecera
if (session_status() === PHP_SESSION_NONE) { // Verifica si la sesión aún no se ha iniciado
    session_start(); // Inicializa la sesión de forma segura para persistir el estado de inicio de sesión
} // Fin del chequeo del estado de la sesión
if (empty($_SESSION['Usuario'])) { // Verifica si la variable de sesión del usuario no está configurada
    header('Location: login.php'); // Redirecciona al usuario no autenticado a la pantalla de inicio de sesión
    exit(); // Detiene la ejecución adicional del script
} // Fin del chequeo de la guardia de inicio de sesión
require_once __DIR__ . '/../funciones/conexion.php'; // Incluye la función de conexión a la base de datos
require_once __DIR__ . '/../funciones/library.php'; // Incluye la biblioteca de operaciones de base de datos
$vConexion = ConexionBD(); // Establece la conexión a la base de datos
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="utf-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> 
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5"> 
    <meta name="author" content="AdminKit"> 
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web"> 
    <link rel="preconnect" href="https://fonts.gstatic.com"> 
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" /> 
    <link rel="canonical" href="https://demo-basic.adminkit.io/" /> 
    <title>2do Desempeño - AdminKit</title> 
    <link href="css/app.css" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet"> 
</head>
<body>
    <div class="wrapper"> 
        <?php // Comienza la inclusión del panel de navegación lateral
        require_once __DIR__ . '/sidebar.inc.php'; // Muestra el panel lateral de navegación
        ?> 
        <div class="main"> 
            <nav class="navbar navbar-expand navbar-light navbar-bg"> 
                <a class="sidebar-toggle js-sidebar-toggle"> 
                    <i class="hamburger align-self-center"></i> 
                </a> 
                <div class="navbar-collapse collapse"> 
                    <ul class="navbar-nav navbar-align"> 
                        <li class="nav-item dropdown"> 
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown"> 
                                <i class="align-middle" data-feather="settings"></i> 
                            </a> 
                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown"> 
                                <img src="img/avatars/<?php echo $_SESSION['Usuario_Img']; ?>" class="avatar img-fluid rounded me-1" alt="<?php echo $_SESSION['Usuario_Nombre']; ?>" /> <span class="text-dark"><?php echo $_SESSION['Usuario_Nombre'] . ' ' . $_SESSION['Usuario_Apellido']; ?></span> 
                            </a> 
                            <div class="dropdown-menu dropdown-menu-end"> 
                                <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="user"></i> <?php echo mb_strtoupper($_SESSION['Usuario_NombreNivel'], 'UTF-8'); // Muestra de forma segura el nombre del nivel del usuario en mayúsculas ?></a> 
                                <div class="dropdown-divider"></div> 
                                <a class="dropdown-item" href="index.php"><i class="align-middle me-1" data-feather="settings"></i> Configuración y Privacidad</a> 
                                <div class="dropdown-divider"></div> 
                                <a class="dropdown-item" href="cerrarsesion.php">Salir</a> 
                            </div> 
                        </li> 
                    </ul> 
                </div> 
            </nav> 
            <main class="content"> 
                <div class="container-fluid p-0"> 
