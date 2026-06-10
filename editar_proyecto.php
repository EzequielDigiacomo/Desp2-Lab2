<?php   
require_once 'funciones/conexion.php';
require_once 'funciones/library.php';

$vConexion = ConexionBD(); 

$lista_proyecto = Listar_Proyectos($vConexion);

$proyectos_lista = array();
foreach ($lista_proyecto as $proyecto) {
    if ($proyecto['ID'] == $_GET['id']) {
        $proyectos_lista = $proyecto;
        $proyectos_lista['Id'] = $proyecto['ID']; 
    }
}

?>
<h1>Pagina de muestra para editar Proyecto</h1>
<h3>Id proyecto:
    <?php echo isset($proyectos_lista['Id']) ? $proyectos_lista['Id'] : 'No seleccionado'; ?>
</h3>

