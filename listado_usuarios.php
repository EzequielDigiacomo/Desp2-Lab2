<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
} 
if (empty($_SESSION['Usuario'])) { 
    header('Location: login.php'); 
    exit(); 
} 
if ($_SESSION['Usuario_Nivel'] != 1) { 
    header('Location: index.php'); 
    exit(); 
} 
require_once 'inc/header.inc.php'; 
$usuarios_lista = Listar_Usuarios($vConexion); 
?>
<h1 class="h3 mb-3"><strong>Usuarios</strong> Listado general.</h1> 
<div class="row"> 
    <div class="col-12 col-lg-12 col-xxl-12 d-flex"> 
        <div class="card flex-fill"> 
            <?php // isset verifica si la variable de sesion existe ?>
            <?php if (isset($_SESSION['Mensaje_Usuario'])) { ?> 
            <div class="card-header pb-0"> 
                <div class="alert alert-<?php echo $_SESSION['Estilo_Usuario']; ?> alert-dismissible mb-0" role="alert"> 
                    <div class="alert-message"> 
                        <?php echo $_SESSION['Mensaje_Usuario']; ?> 
                    </div> 
                </div> 
            </div> 
            <?php // unset elimina la variable de sesion para que no se repita el cartel ?>
            <?php unset($_SESSION['Mensaje_Usuario']); ?> 
            <?php unset($_SESSION['Estilo_Usuario']); ?> 
            <?php } ?> 
            <table class="table table-hover my-0"> 
                <thead> 
                    <tr> 
                        <th>#</th> 
                        <th>Apellido y Nombre</th> 
                        <th>Rol</th> 
                        <th>Usuario</th> 
                        <th>Acciones</th> 
                    </tr> 
                </thead> 
                <tbody> 
                    <?php 
                    $contador = 1; 
                    foreach ($usuarios_lista as $usuario) { 
                    ?> 
                    <tr> 
                        <td><?php echo $contador++; ?></td> 
                        <td> 
                            <img src="img/avatars/<?php echo $usuario['IMG']; ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $usuario['USUARIO']; ?>"> 
                            <?php echo $usuario['APELLIDO'] . ' ' . $usuario['NOMBRE']; ?> 
                        </td> 
                        <td> 
                            <?php echo $usuario['ROL']; ?> 
                        </td> 
                        <td> 
                            <?php echo $usuario['USUARIO']; ?> 
                        </td> 
                        <td> 
                            <a class="btn btn-primary btn-sm success" href="editar_usuario.php?id=<?php echo $usuario['ID']; ?>"><span data-feather="edit"></span> Editar</a> 
                            <a class="btn btn-danger btn-sm" href="borrar_usuario.php?id=<?php echo $usuario['ID']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');"><span data-feather="delete"></span> Borrar</a> 
                        </td> 
                    </tr> 
                    <?php } ?> 
                </tbody> 
            </table> 
        </div> 
    </div> 
</div> 
<?php 
require_once 'inc/footer.inc.php'; 
?>
