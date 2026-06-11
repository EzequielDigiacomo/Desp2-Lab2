<?php
require_once 'funciones/conexion.php';
require_once 'funciones/library.php';
$vConexion = ConexionBD(); 

$lista_paises = Listar_Paises($vConexion);

$listado_paises = array();
foreach ($lista_paises as $pais) {
    if ($pais['ID'] == $_GET['id']) {
        $listado_paises = $pais;
        $listado_paises['Id'] = $pais['ID']; 
    }
}

?>

<h1>
    ID Pais: <?php echo ($listado_paises['Id']); ?>
</h1>
