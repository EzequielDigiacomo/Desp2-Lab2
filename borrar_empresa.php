<?php
session_start();

require_once 'funciones/conexion.php';
require_once 'funciones/library.php';

$vConexion = ConexionBD();
$idEmpresa = $_GET['id'];

if (Borrar_Empresa_Logico($vConexion, $idEmpresa)) {
    $_SESSION['Mensaje_Empresa'] = "La empresa ha sido eliminada con éxito.";
    $_SESSION['Estilo_Empresa'] = "success";
} else {
    $_SESSION['Mensaje_Empresa'] = "No se pudo eliminar la empresa.";
    $_SESSION['Estilo_Empresa'] = "danger";
}

header('Location: listado_empresas.php');
exit();
?>
