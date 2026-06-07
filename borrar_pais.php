<?php
session_start();

require_once 'funciones/conexion.php';
require_once 'funciones/library.php';

$vConexion = ConexionBD();
$idPais = $_GET['id'];

if (Borrar_Pais_Logico($vConexion, $idPais)) {
    $_SESSION['Mensaje_Pais'] = "El país ha sido eliminado con éxito.";
    $_SESSION['Estilo_Pais'] = "success";
} else {
    $_SESSION['Mensaje_Pais'] = "No se pudo eliminar el país.";
    $_SESSION['Estilo_Pais'] = "danger";
}

header('Location: listado_paises.php');
exit();
?>
