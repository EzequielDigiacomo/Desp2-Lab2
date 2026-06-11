<?php
require_once 'funciones/conexion.php';
require_once 'funciones/library.php';
$vConexion = ConexionBD(); 

$lista_usuarios = Listar_Usuarios($vConexion);

$listado_usuarios = array();
foreach ($lista_usuarios as $usuario) {
    if ($usuario['ID'] == $_GET['id']) {
        $listado_usuarios = $usuario;
        $listado_usuarios['Id'] = $usuario['ID']; 
    }
}

?>

<h1>
    ID USUARIO: <?php echo ($listado_usuarios['Id']); ?>
</h1>
