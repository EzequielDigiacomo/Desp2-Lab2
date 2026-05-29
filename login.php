<?php // Start PHP script for login page
session_start(); // Initialize session storage
require_once __DIR__ . '/funciones/conexion.php'; // Include connection builder utility
require_once __DIR__ . '/funciones/library.php'; // Include shared functions library
$vConexion = ConexionBD(); // Establish database connection
$Error_Credenciales = false; // Flag to track credential match errors
$Error_Permisos = false; // Flag to track role permission errors

if (!empty($_POST['BotonLogin'])) { // Check if login form was submitted
    $vUser = trim(strip_tags($_POST['txtUsuario'])); // Clean and sanitize username input
    $vClave = trim(strip_tags($_POST['txtClave'])); // Clean and sanitize password input
    $UsuarioLogueado = DatosLogin_Hash($vUser, $vClave, $vConexion); // Validate credentials against database
    
    if (!empty($UsuarioLogueado)) { // Verify if login returned valid user details
        if ($UsuarioLogueado['ACTIVO'] == 1) { // Verify if user account is active
            $RolesPermitidos = array(1, 2); // Define list of allowed roles (Admin and Lider)
            if (in_array($UsuarioLogueado['NIVEL_ID'], $RolesPermitidos)) { // Check if user's role is allowed access
                $_SESSION['Usuario'] = $vUser; // Store username in session for tracking
                $_SESSION['Usuario_Nombre'] = $UsuarioLogueado['NOMBRE']; // Store user's first name in session
                $_SESSION['Usuario_Apellido'] = $UsuarioLogueado['APELLIDO']; // Store user's last name in session
                $_SESSION['Usuario_Nivel'] = $UsuarioLogueado['NIVEL_ID']; // Store user's numeric role level
                $_SESSION['Usuario_NombreNivel'] = $UsuarioLogueado['NIVEL_NOMBRE']; // Store user's role string name
                $_SESSION['Usuario_Img'] = $UsuarioLogueado['IMG']; // Store user's avatar filename
                header('Location: index.php'); // Redirect user to home dashboard
                exit(); // Stop script execution
            } else { // Handle disallowed roles (Analista and Programador)
                $Error_Permisos = true; // Mark unauthorized role error flag as true
            } // End role access check
        } else { // Handle inactive account
            $Error_Permisos = true; // Mark account status block as true
        } // End active check
    } else { // Handle credential mismatches
        $Error_Credenciales = true; // Mark credential error flag as true
    } // End user details check
} // End POST check
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
                                        <?php if ($Error_Credenciales) { // Check if login attempt had wrong credentials ?>
                                        <h4 class="text-danger text-center">Datos incorrectos, intenta de nuevo.</h4>
                                        <?php } // End error display check ?>
                                        <?php if ($Error_Permisos) { // Check if login attempt was blocked by role privileges ?>
                                        <h4 class="text-danger text-center">No tienes permisos asignados para ingresar al panel</h4>
                                        <?php } // End permission display check ?>
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
