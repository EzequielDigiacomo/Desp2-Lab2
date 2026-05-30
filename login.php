<?php // Inicia el script PHP para la página de inicio de sesión
session_start(); // Inicializa el almacenamiento de sesiones
require_once __DIR__ . '/funciones/conexion.php'; // Incluye la utilidad para construir la conexión
require_once __DIR__ . '/funciones/library.php'; // Incluye la biblioteca de funciones compartidas
$vConexion = ConexionBD(); // Establece la conexión a la base de datos
$Error_Credenciales = false; // Bandera para rastrear errores de coincidencia de credenciales
$Error_Permisos = false; // Bandera para rastrear errores de permisos de rol

if (!empty($_POST['BotonLogin'])) { // Verifica si el formulario de inicio de sesión fue enviado
    $vUser = trim(strip_tags($_POST['txtUsuario'])); // Limpia y sanitiza la entrada del nombre de usuario
    $vClave = trim(strip_tags($_POST['txtClave'])); // Limpia y sanitiza la entrada de la contraseña
    $UsuarioLogueado = DatosLogin_Hash($vUser, $vClave, $vConexion); // Valida las credenciales contra la base de datos
    
    if (!empty($UsuarioLogueado)) { // Verifica si el inicio de sesión devolvió detalles de usuario válidos
        if ($UsuarioLogueado['ACTIVO'] == 1) { // Verifica si la cuenta del usuario está activa
            $RolesPermitidos = array(1, 2); // Define la lista de roles permitidos (Admin y Líder)
            if (in_array($UsuarioLogueado['NIVEL_ID'], $RolesPermitidos)) { // Verifica si el rol del usuario tiene permitido el acceso
                $_SESSION['Usuario'] = $vUser; // Almacena el nombre de usuario en la sesión para el seguimiento
                $_SESSION['Usuario_Nombre'] = $UsuarioLogueado['NOMBRE']; // Almacena el nombre del usuario en la sesión
                $_SESSION['Usuario_Apellido'] = $UsuarioLogueado['APELLIDO']; // Almacena el apellido del usuario en la sesión
                $_SESSION['Usuario_Nivel'] = $UsuarioLogueado['NIVEL_ID']; // Almacena el nivel de rol numérico en la sesión
                $_SESSION['Usuario_NombreNivel'] = $UsuarioLogueado['NIVEL_NOMBRE']; // Almacena el nombre del rol en la sesión
                $_SESSION['Usuario_Img'] = $UsuarioLogueado['IMG']; // Almacena el nombre de archivo del avatar del usuario
                header('Location: index.php'); // Redirecciona al usuario al panel de control de inicio
                exit(); // Detiene la ejecución del script
            } else { // Manejo de roles no permitidos (Analista y Programador)
                $Error_Permisos = true; // Establece la bandera de error de rol no autorizado en verdadero
            } // Fin de la verificación de acceso por rol
        } else { // Manejo de cuenta inactiva
            $Error_Permisos = true; // Establece el bloqueo por estado de cuenta en verdadero
        } // Fin de la verificación de cuenta activa
    } else { // Manejo de desajuste de credenciales
        $Error_Credenciales = true; // Establece la bandera de error de credenciales en verdadero
    } // Fin de la verificación de detalles del usuario
} // Fin del chequeo de POST
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />
    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-in.html" />
    <title>Sign In | AdminKit Demo</title>
    <link href="css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    <div class="text-center mt-4">
                                        <p class="lead">
                                            <img src="img/avatars/login.png" width="150" height="150">
                                            <h1 class="h2">Ingresa tus datos.</h1>
                                        </p>
                                    </div>
                                    <div class="card-header border-0 pb-0">
                                        <?php if ($Error_Credenciales) { // Verifica si el intento de inicio de sesión tuvo credenciales incorrectas ?>
                                        <h4 class="text-danger text-center">Datos incorrectos, intenta de nuevo.</h4>
                                        <?php } // Fin de la verificación de visualización de error ?>
                                        <?php if ($Error_Permisos) { // Verifica si el inicio de sesión fue bloqueado por privilegios de rol ?>
                                        <h4 class="text-danger text-center">No tienes permisos asignados para ingresar al panel</h4>
                                        <?php } // Fin de la verificación de visualización de permisos ?>
                                    </div>
                                    <form method="POST" action="login.php">
                                        <div class="mb-3">
                                            <label class="form-label">Login</label>
                                            <input class="form-control form-control-lg" name="txtUsuario" type="text" placeholder="Ingresa tu email o usuario" required value="<?php echo isset($_POST['txtUsuario']) ? htmlspecialchars($_POST['txtUsuario']) : ''; ?>" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input class="form-control form-control-lg" name="txtClave" type="password" placeholder="Ingresa tu password" required />
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <input class="btn btn-lg btn-primary" type="submit" name="BotonLogin" value="Ingresar">
                                        </div>
                                    </form>
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
