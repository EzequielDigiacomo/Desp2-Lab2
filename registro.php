<?php
session_start();
require_once 'funciones/conexion.php';
require_once 'funciones/library.php';

$vConexion = ConexionBD();
$Msg_Error = "";
$Msg_Exito = "";

// Listamos los roles para el desplegable
$roles = Listar_Roles($vConexion);

if (!empty($_POST['BotonRegistrar'])) {
    $vNombre = trim(strip_tags($_POST['txtNombre']));
    $vApellido = trim(strip_tags($_POST['txtApellido']));
    $vUsuario = trim(strip_tags($_POST['txtUsuario']));
    $vClave = $_POST['txtClave'];
    $vReClave = $_POST['txtReClave'];
    $vRol = intval($_POST['selRol']);

    // Validaciones
    if (empty($vNombre) || empty($vApellido) || empty($vUsuario) || empty($vClave) || empty($vReClave) || $vRol <= 0) {
        $Msg_Error = "Todos los campos son obligatorios.";
    // Valida largo del nombre
    } elseif (strlen($vNombre) < 2) {
        $Msg_Error = "El nombre debe tener al menos 2 caracteres.";
    // Valida largo del apellido
    } elseif (strlen($vApellido) < 2) {
        $Msg_Error = "El apellido debe tener al menos 2 caracteres.";
    // Valida largo del usuario
    } elseif (strlen($vUsuario) < 4) {
        $Msg_Error = "El usuario debe tener al menos 4 caracteres.";
    // Valida largo de la clave
    } elseif (strlen($vClave) < 4) {
        $Msg_Error = "La contraseña debe tener al menos 4 caracteres.";
    } elseif ($vClave !== $vReClave) {
        $Msg_Error = "Las contraseñas ingresadas no coinciden.";
    } elseif (Existe_Usuario($vUsuario, $vConexion)) {
        $Msg_Error = "El nombre de usuario ya está registrado en el sistema.";
    } else {
        // Todo OK, procedemos a insertar
        $exito = Insertar_Usuario_Hash($vConexion, $vNombre, $vApellido, $vUsuario, $vClave, $vRol);
        if ($exito) {
            $Msg_Exito = "Usuario registrado correctamente. Ya puedes <a href='login.php'>iniciar sesión</a>.";
            // Limpiamos los campos para que no queden en el form
            $_POST = array();
        } else {
            $Msg_Error = "Hubo un error al registrar el usuario en el sistema. Intenta de nuevo.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registro | AdminKit Demo</title>
    <link href="css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <main class="d-flex w-100 my-5">
        <div class="container d-flex flex-column">
            <div class="row">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-3">
                                <div class="text-center mt-4">
                                    <h1 class="h2">Crea tu cuenta</h1>
                                    <p class="lead">Completa tus datos para registrarte.</p>
                                </div>
                                
                                <?php if (!empty($Msg_Error)) { ?>
                                    <div class="alert alert-danger text-center text-white bg-danger mb-3 py-2" role="alert">
                                        <?php echo $Msg_Error; ?>
                                    </div>
                                <?php } ?>
                                
                                <?php if (!empty($Msg_Exito)) { ?>
                                    <div class="alert alert-success text-center text-white bg-success mb-3 py-2" role="alert">
                                        <?php echo $Msg_Exito; ?>
                                    </div>
                                <?php } ?>

                                <form method="POST" action="registro.php">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input class="form-control form-control-lg" type="text" name="txtNombre" placeholder="Ingresa tu nombre" required value="<?php echo isset($_POST['txtNombre']) ? $_POST['txtNombre'] : ''; /* isset verifica si existe el dato enviado para conservarlo en el campo */ ?>" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Apellido</label>
                                        <input class="form-control form-control-lg" type="text" name="txtApellido" placeholder="Ingresa tu apellido" required value="<?php echo isset($_POST['txtApellido']) ? $_POST['txtApellido'] : ''; /* isset verifica si existe el dato enviado para conservarlo en el campo */ ?>" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Usuario</label>
                                        <input class="form-control form-control-lg" type="text" name="txtUsuario" placeholder="Ingresa tu nombre de usuario" required value="<?php echo isset($_POST['txtUsuario']) ? $_POST['txtUsuario'] : ''; /* isset verifica si existe el dato enviado para conservarlo en el campo */ ?>" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Rol / Nivel</label>
                                        <select class="form-select form-select-lg" name="selRol" required>
                                            <option value="">Selecciona tu rol...</option>
                                            <?php foreach ($roles as $rol) { ?>
                                                <option value="<?php echo $rol['ID']; ?>" <?php echo (isset($_POST['selRol']) && $_POST['selRol'] == $rol['ID']) ? 'selected' : ''; /* isset comprueba si existe el rol enviado para dejarlo seleccionado */ ?>>
                                                    <?php echo $rol['NOMBRE']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input class="form-control form-control-lg" type="password" name="txtClave" placeholder="Crea tu password" required />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirmar Password</label>
                                        <input class="form-control form-control-lg" type="password" name="txtReClave" placeholder="Repite tu password" required />
                                    </div>
                                    <div class="d-grid gap-2 mt-4">
                                        <input class="btn btn-lg btn-primary" type="submit" name="BotonRegistrar" value="Registrarme" />
                                    </div>
                                </form>
                                <div class="text-center mt-3">
                                    ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="js/app.js"></script>
</body>
</html>
