<?php
session_start();

require_once 'funciones/conexion.php';
require_once 'funciones/library.php';

$vConexion = ConexionBD();
$idProyecto = $_GET['id'];

if (Cancelar_Proyecto($vConexion, $idProyecto)) {
    $_SESSION['Mensaje_Proyecto'] = "El proyecto ha sido cancelado con éxito.";
    $_SESSION['Estilo_Proyecto'] = "success";
} else {
    $_SESSION['Mensaje_Proyecto'] = "No se pudo cancelar el proyecto.";
    $_SESSION['Estilo_Proyecto'] = "danger";
}

header('Location: listado_proyectos.php');
exit();
?>