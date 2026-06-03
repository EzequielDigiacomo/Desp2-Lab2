<?php // Inicia el script PHP para la página del listado de países
if (session_status() === PHP_SESSION_NONE) { // Verifica si la sesión aún no se ha iniciado
    session_start(); // Inicializa la sesión de forma segura para comprobar roles
} // Fin del chequeo de la sesión
if (empty($_SESSION['Usuario'])) { // Verifica si el usuario no ha iniciado sesión
    header('Location: login.php'); // Redirecciona al usuario no autenticado a la pantalla de inicio de sesión
    exit(); // Detiene la ejecución del script
} // Fin del chequeo de autenticación
if ($_SESSION['Usuario_Nivel'] != 1) { // Verifica si el usuario autenticado no es un Administrador (Nivel 1)
    header('Location: index.php'); // Redirecciona al usuario no autorizado al panel de control
    exit(); // Detiene la ejecución del script
} // Fin del chequeo de Administrador
require_once 'inc/header.inc.php'; // Incluye el encabezado con guardias de sesión y conexión a la base de datos
$paises_lista = Listar_Paises($vConexion); // Obtiene la lista de países activos de la base de datos
$total_paises = count($paises_lista); // Cuenta la cantidad total de países activos
?>
<h1 class="h3 mb-3"><strong>Paises con que trabajamos.</strong> Listado general.</h1> 
<div class="row"> 
    <div class="col-12 col-lg-12 col-xxl-12 d-flex"> 
        <div class="card flex-fill"> 
            <div class="card-header"> 
                <h4 class="text-info">Visualizando <?php echo $total_paises; // Muestra la cantidad de países activos ?> registros</h4> 
                <?php if (isset($_SESSION['Mensaje_Pais'])) { // Verifica si hay alertas de retroalimentación de la sesión ?> 
                <div class="alert alert-<?php echo $_SESSION['Estilo_Pais']; // Renderiza la clase de estilo de la alerta ?> alert-dismissible mt-2 mb-0" role="alert"> 
                    <div class="alert-message"> 
                        <?php echo $_SESSION['Mensaje_Pais']; // Muestra la respuesta de la operación de borrado lógico ?> 
                    </div> 
                </div> 
                <?php unset($_SESSION['Mensaje_Pais']); // Limpia el mensaje de retroalimentación de la sesión ?> 
                <?php unset($_SESSION['Estilo_Pais']); // Limpia el estilo de retroalimentación de la sesión ?> 
                <?php } // Fin del chequeo de la sesión ?> 
            </div> 
            <table class="table table-hover my-0"> 
                <thead> 
                    <tr> 
                        <th>#</th> 
                        <th>Denominación</th> 
                        <th class="d-none d-md-table-cell">Pais</th> 
                        <th>Acciones</th> 
                    </tr> 
                </thead> 
                <tbody> 
                    <?php // Recorre cada país en la lista
                    $cnt = 1; // Inicializa el contador de filas
                    foreach ($paises_lista as $pais) { // Recorre el array de países obtenidos
                        // Mapea las imágenes de banderas en función del nombre del país
                        $flag_file = 'URU.jpg'; // Establece Uruguay como bandera de país por defecto
                        if ($pais['NOMBRE'] == 'Argentina') { // Verifica si es Argentina
                            $flag_file = 'ARG.jpg'; // Asigna el archivo de bandera correspondiente
                        } elseif ($pais['NOMBRE'] == 'Brasil') { // Verifica si es Brasil
                            $flag_file = 'BRA.jpg'; // Asigna el archivo de bandera correspondiente
                        } elseif ($pais['NOMBRE'] == 'Chile') { // Verifica si es Chile
                            $flag_file = 'CHI.jpg'; // Asigna el archivo de bandera correspondiente
                        } // Fin del bloque de mapeo de banderas
                    ?> 
                    <tr> 
                        <td><?php echo $cnt++; // Muestra el índice de la fila e incrementa ?></td> 
                        <td><?php echo htmlspecialchars($pais['NOMBRE']); // Muestra el nombre del país de forma segura ?></td> 
                        <td> 
                            <img src="img/countries/<?php echo $flag_file; // Muestra el archivo de la bandera del país ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $pais['NOMBRE']; ?>"> 
                        </td> 
                        <td> 
                            <a class="btn btn-primary btn-sm success" href="editar_pais.php?id=<?php echo $pais['ID']; // Enlace parametrizado para editar detalles del país ?>"><span data-feather="edit"></span> Editar</a> 
                            <a class="btn btn-danger btn-sm" href="borrar_pais.php?id=<?php echo $pais['ID']; // Enlace parametrizado para eliminar lógicamente al país ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este país?');"><span data-feather="delete"></span> Borrar</a> 
                        </td> 
                    </tr> 
                    <?php } // Fin del bucle de países ?> 
                </tbody> 
            </table> 
        </div> 
    </div> 
</div> 
<?php // Cierra los contenedores HTML y carga las dependencias
require_once 'inc/footer.inc.php'; // Incluye el archivo de plantilla del pie de página
?>
