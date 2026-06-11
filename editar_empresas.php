<?php
require_once 'funciones/conexion.php';
require_once 'funciones/library.php';
$vConexion = ConexionBD(); 

$lista_empresas = Listar_Empresas($vConexion);

$listado_empresas = array();
foreach ($lista_empresas as $empresa) {
    if ($empresa['ID'] == $_GET['id']) {
        $listado_empresas = $empresa;
        $listado_empresas['Id'] = $empresa['ID']; 
    }
}

?>

<h1>
    ID EMPRESA: <?php echo ($listado_empresas['Id']); ?>
</h1>
