<?php // Start PHP script for users listing page
if (session_status() === PHP_SESSION_NONE) { // Verify if session has not been started yet
    session_start(); // Initialize session safely to check roles
} // End session check
if (empty($_SESSION['Usuario'])) { // Verify if user is not logged in
    header('Location: login.php'); // Redirect unauthenticated user to login screen
    exit(); // Stop further script execution
} // End check
if ($_SESSION['Usuario_Nivel'] != 1) { // Verify if the logged-in user is not an Administrator (Level 1)
    header('Location: index.php'); // Redirect unauthorized user back to dashboard home
    exit(); // Terminate further script execution
} // End Admin check
require_once __DIR__ . '/inc/header.inc.php'; // Include header with session guards and database connection
$usuarios_lista = Listar_Usuarios($vConexion); // Retrieve list of all active non-deleted users from database
?>
<h1 class="h3 mb-3"><strong>Usuarios</strong> Listado general.</h1> 
<div class="row"> 
    <div class="col-12 col-lg-12 col-xxl-12 d-flex"> 
        <div class="card flex-fill"> 
            <?php if (isset($_SESSION['Mensaje_Usuario'])) { // Check if there is a deletion message to show ?> 
            <div class="card-header pb-0"> 
                <div class="alert alert-<?php echo $_SESSION['Estilo_Usuario']; // Dynamic alert style ?> alert-dismissible mb-0" role="alert"> 
                    <div class="alert-message"> 
                        <?php echo $_SESSION['Mensaje_Usuario']; // Output user deletion message ?> 
                    </div> 
                </div> 
            </div> 
            <?php unset($_SESSION['Mensaje_Usuario']); // Clear message key from session ?> 
            <?php unset($_SESSION['Estilo_Usuario']); // Clear style key from session ?> 
            <?php } // End session message block ?> 
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
                    <?php // Loop through each user in the list
                    $cnt = 1; // Initialize row numbering index
                    foreach ($usuarios_lista as $usuario) { // Traverse the retrieved users collection
                    ?> 
                    <tr> 
                        <td><?php echo $cnt++; // Output index and increment ?></td> 
                        <td> 
                            <img src="img/avatars/<?php echo $usuario['IMG']; // Output avatar image filename ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $usuario['USUARIO']; ?>"> 
                            <?php echo htmlspecialchars($usuario['APELLIDO'] . ' ' . $usuario['NOMBRE']); // Output last name then first name safely ?> 
                        </td> 
                        <td> 
                            <?php echo htmlspecialchars($usuario['ROL']); // Output user role name safely ?> 
                        </td> 
                        <td> 
                            <?php echo htmlspecialchars($usuario['USUARIO']); // Output username safely ?> 
                        </td> 
                        <td> 
                            <a class="btn btn-primary btn-sm success" href="editar_usuario.php?id=<?php echo $usuario['ID']; // Parametrized link to edit user ?>"><span data-feather="edit"></span> Editar</a> 
                            <a class="btn btn-danger btn-sm" href="borrar_usuario.php?id=<?php echo $usuario['ID']; // Parametrized link to logically delete user ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');"><span data-feather="delete"></span> Borrar</a> 
                        </td> 
                    </tr> 
                    <?php } // End user traversal loop ?> 
                </tbody> 
            </table> 
        </div> 
    </div> 
</div> 
<?php // Close the page body and include dependencies
require_once __DIR__ . '/inc/footer.inc.php'; // Include footer and closing tags
?>
