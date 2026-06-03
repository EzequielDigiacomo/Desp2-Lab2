<?php // Inicia el script PHP para la página del listado de usuarios
if (session_status() === PHP_SESSION_NONE) { // Verifica si la sesión aún no se ha iniciado
    session_start(); // Inicializa la sesión de forma segura para comprobar roles
} // Fin del chequeo de la sesión
if (empty($_SESSION['Usuario'])) { // Verifica si el usuario no ha iniciado sesión
    header('Location: login.php'); // Redirecciona al usuario no autenticado a la pantalla de inicio de sesión
    exit(); // Detiene la ejecución adicional del script
} // Fin del chequeo de autenticación
if ($_SESSION['Usuario_Nivel'] != 1) { // Verifica si el usuario autenticado no es un Administrador (Nivel 1)
    header('Location: index.php'); // Redirecciona al usuario no autorizado de vuelta al listado de inicio
    exit(); // Finaliza la ejecución del script
} // Fin del chequeo del Administrador
require_once 'inc/header.inc.php'; // Incluye el encabezado con guardias de sesión y conexión a la base de datos
$usuarios_lista = Listar_Usuarios($vConexion); // Obtiene la lista de todos los usuarios activos no eliminados de la base de datos
?>
<h1 class="h3 mb-3"><strong>Usuarios</strong> Listado general.</h1> 
<div class="row"> 
    <div class="col-12 col-lg-12 col-xxl-12 d-flex"> 
        <div class="card flex-fill"> 
            <?php if (isset($_SESSION['Mensaje_Usuario'])) { // Verifica si hay un mensaje de eliminación para mostrar ?> 
            <div class="card-header pb-0"> 
                <div class="alert alert-<?php echo $_SESSION['Estilo_Usuario']; // Estilo dinámico de la alerta ?> alert-dismissible mb-0" role="alert"> 
                    <div class="alert-message"> 
                        <?php echo $_SESSION['Mensaje_Usuario']; // Muestra el mensaje de eliminación del usuario ?> 
                    </div> 
                </div> 
            </div> 
            <?php unset($_SESSION['Mensaje_Usuario']); // Limpia la clave del mensaje de la sesión ?> 
            <?php unset($_SESSION['Estilo_Usuario']); // Limpia la clave del estilo de la sesión ?> 
            <?php } // Fin del bloque de mensajes de sesión ?> 
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
                    <?php // Recorre cada usuario en la lista
                    $cnt = 1; // Inicializa el índice numérico de la fila
                    foreach ($usuarios_lista as $usuario) { // Recorre la colección de usuarios obtenidos
                    ?> 
                    <tr> 
                        <td><?php echo $cnt++; // Muestra el índice e incrementa ?></td> 
                        <td> 
                            <img src="img/avatars/<?php echo $usuario['IMG']; // Muestra el nombre de archivo de la imagen del avatar ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $usuario['USUARIO']; ?>"> 
                            <?php echo htmlspecialchars($usuario['APELLIDO'] . ' ' . $usuario['NOMBRE']); // Muestra el apellido y luego el nombre de forma segura ?> 
                        </td> 
                        <td> 
                            <?php echo htmlspecialchars($usuario['ROL']); // Muestra el nombre del rol del usuario de forma segura ?> 
                        </td> 
                        <td> 
                            <?php echo htmlspecialchars($usuario['USUARIO']); // Muestra el nombre de usuario de forma segura ?> 
                        </td> 
                        <td> 
                            <a class="btn btn-primary btn-sm success" href="editar_usuario.php?id=<?php echo $usuario['ID']; // Enlace parametrizado para editar el usuario ?>"><span data-feather="edit"></span> Editar</a> 
                            <a class="btn btn-danger btn-sm" href="borrar_usuario.php?id=<?php echo $usuario['ID']; // Enlace parametrizado para eliminar lógicamente al usuario ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');"><span data-feather="delete"></span> Borrar</a> 
                        </td> 
                    </tr> 
                    <?php } // Fin del bucle de recorrido de usuarios ?> 
                </tbody> 
            </table> 
        </div> 
    </div> 
</div> 
<?php // Cierra el cuerpo de la página e incluye las dependencias
require_once 'inc/footer.inc.php'; // Incluye el pie de página y las etiquetas de cierre
?>
