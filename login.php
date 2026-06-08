<?php 
session_start(); 
require_once 'funciones/conexion.php'; 
require_once 'funciones/library.php'; 
$vConexion = ConexionBD(); 
$Error_Credenciales = false; 
$Error_Permisos = false; 

if (!empty($_POST['BotonLogin'])) { 
    $vUser = trim($_POST['txtUsuario']); 
    $vClave = trim($_POST['txtClave']); 
    $UsuarioLogueado = DatosLogin_Hash($vUser, $vClave, $vConexion); 
    
    if (!empty($UsuarioLogueado)) { 
        if ($UsuarioLogueado['ACTIVO'] == 1) { 
            $RolesPermitidos = array(1, 2);
            if ($UsuarioLogueado['ID_ROL'] == 1 || $UsuarioLogueado['ID_ROL'] == 2) { 
                $_SESSION['Usuario'] = $vUser; 
                $_SESSION['Usuario_Nombre'] = $UsuarioLogueado['NOMBRE']; 
                $_SESSION['Usuario_Apellido'] = $UsuarioLogueado['APELLIDO']; 
                $_SESSION['Usuario_Nivel'] = $UsuarioLogueado['ID_ROL']; 
                $_SESSION['Usuario_NombreNivel'] = $UsuarioLogueado['NOMBRE_ROL']; 
                $_SESSION['Usuario_Img'] = $UsuarioLogueado['IMG']; 
                header('Location: index.php'); 
                exit(); 
            } else { 
                $Error_Permisos = true; 
            } 
        } else { 
            $Error_Permisos = true; 
        } 
    } else { 
        $Error_Credenciales = true; 
    } 
} 
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
                                        <?php if ($Error_Credenciales) { ?>
                                        <h4 class="text-danger text-center">Datos incorrectos, intenta de nuevo.</h4>
                                        <?php } ?>
                                        <?php if ($Error_Permisos) { ?>
                                        <h4 class="text-danger text-center">No tienes permisos asignados para ingresar al panel</h4>
                                        <?php } ?>
                                    </div>
                                    <form method="POST" action="login.php">
                                        <div class="mb-3">
                                            <label class="form-label">Login</label>
                                            <?php // Mantiene el usuario que se escribió en caso de error en el login ?>
                                            <input class="form-control form-control-lg" name="txtUsuario" type="text" placeholder="Ingresa tu email o usuario" required value="<?php echo isset($_POST['txtUsuario']) ? $_POST['txtUsuario'] : ''; ?>" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input class="form-control form-control-lg" name="txtClave" type="password" placeholder="Ingresa tu password" required />
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <input class="btn btn-lg btn-primary" type="submit" name="BotonLogin" value="Ingresar">
                                        </div>
                                    </form>
                                    <div class="text-center mt-3">
                                        ¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a>
                                    </div>
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
