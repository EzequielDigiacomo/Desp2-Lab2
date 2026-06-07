<?php
session_start();

require_once 'funciones/conexion.php';
require_once 'funciones/library.php';

$vConexion = ConexionBD();
$idUsuario = $_GET['id'];

if (Borrar_Usuario_Logico($vConexion, $idUsuario)) {
    $_SESSION['Mensaje_Usuario'] = "El usuario ha sido eliminado con éxito.";
    $_SESSION['Estilo_Usuario'] = "success";
} else {
    $_SESSION['Mensaje_Usuario'] = "No se pudo eliminar el usuario.";
    $_SESSION['Estilo_Usuario'] = "danger";
}

header('Location: listado_usuarios.php');
exit();
?>
