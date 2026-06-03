<?php // Inicia el script PHP para la página del listado de empresas
require_once 'inc/header.inc.php'; // Incluye el encabezado con guardias de sesión y conexión a la base de datos
$empresas_lista = Listar_Empresas($vConexion); // Obtiene la lista de empresas activas de la base de datos
$total_empresas = count($empresas_lista); // Cuenta la cantidad total de empresas activas
?>
<h1 class="h3 mb-3"><strong>Empresas</strong> Listado general.</h1> 
<div class="row"> 
    <div class="col-12 col-lg-12 col-xxl-12 d-flex"> 
        <div class="card flex-fill"> 
            <div class="card-header"> 
                <h4 class="text-info">Visualizando <?php echo $total_empresas; // Muestra la cantidad de empresas ?> registros</h4> 
                <?php if (isset($_SESSION['Mensaje_Empresa'])) { // Verifica si hay alertas de retroalimentación en la sesión ?> 
                <div class="alert alert-<?php echo $_SESSION['Estilo_Empresa']; // Renderiza la clase de estilo de la alerta ?> alert-dismissible mt-2 mb-0" role="alert"> 
                    <div class="alert-message"> 
                        <?php echo $_SESSION['Mensaje_Empresa']; // Muestra el texto de retroalimentación de éxito o error ?> 
                    </div> 
                </div> 
                <?php unset($_SESSION['Mensaje_Empresa']); // Limpia la clave del mensaje de retroalimentación de la sesión ?> 
                <?php unset($_SESSION['Estilo_Empresa']); // Limpia la clave de estilo de la sesión ?> 
                <?php } // Fin del chequeo de la sesión ?> 
            </div> 
            <table class="table table-hover my-0"> 
                <thead> 
                    <tr> 
                        <th>#</th> 
                        <th>Denominación</th> 
                        <th>Fecha de carga</th> 
                        <th>Cargada por</th> 
                        <?php if ($_SESSION['Usuario_Nivel'] == 1) { // Renderiza el encabezado de la columna de acciones solo para el Administrador (Nivel 1) ?> 
                        <th>Acciones</th> 
                        <?php } // Fin del chequeo de Administrador para el encabezado de acciones ?> 
                    </tr> 
                </thead> 
                <tbody> 
                    <?php // Recorre cada empresa en la lista
                    $cnt = 1; // Inicializa el contador de filas
                    foreach ($empresas_lista as $empresa) { // Recorre el array de empresas obtenidas
                        // Mapea las imágenes de banderas en función del nombre del país
                        $flag_file = 'URU.jpg'; // Establece Uruguay como bandera de país por defecto
                        if ($empresa['PAIS'] == 'Argentina') { // Verifica si es Argentina
                            $flag_file = 'ARG.jpg'; // Asigna el archivo de bandera correspondiente
                        } elseif ($empresa['PAIS'] == 'Brasil') { // Verifica si es Brasil
                            $flag_file = 'BRA.jpg'; // Asigna el archivo de bandera correspondiente
                        } elseif ($empresa['PAIS'] == 'Chile') { // Verifica si es Chile
                            $flag_file = 'CHI.jpg'; // Asigna el archivo de bandera correspondiente
                        } // Fin del bloque de mapeo de banderas
                    ?> 
                    <tr> 
                        <td><?php echo $cnt++; // Muestra el índice de la fila e incrementa ?></td> 
                        <td> 
                            <img src="img/countries/<?php echo $flag_file; // Muestra el nombre del archivo de la bandera ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $empresa['PAIS']; ?>" title="<?php echo $empresa['PAIS']; ?>"> 
                            <?php echo htmlspecialchars($empresa['DENOMINACION']); // Muestra el nombre de la empresa de forma segura ?> 
                        </td> 
                        <td> 
                            <?php echo date('d/m/Y', strtotime($empresa['FECHA_CARGA'])); // Formatea y muestra la fecha de carga ?> 
                        </td> 
                        <td> 
                            <img src="img/avatars/<?php echo $empresa['CREADOR_IMG']; // Muestra la imagen del avatar del creador ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $empresa['USUARIO_CARGA']; ?>"> 
                            <?php echo htmlspecialchars($empresa['CREADOR_COMPLETO']); // Muestra el nombre completo del creador de forma segura ?> 
                        </td> 
                        <?php if ($_SESSION['Usuario_Nivel'] == 1) { // Renderiza la celda de botones de acción solo para el Administrador (Nivel 1) ?> 
                        <td> 
                            <a class="btn btn-primary btn-sm success" href="editar_empresa.php?id=<?php echo $empresa['ID']; // Enlace parametrizado para editar detalles de la empresa ?>"><span data-feather="edit"></span> Editar</a> 
                            <a class="btn btn-danger btn-sm" href="borrar_empresa.php?id=<?php echo $empresa['ID']; // Enlace parametrizado para eliminar la empresa ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta empresa?');"><span data-feather="delete"></span> Borrar</a> 
                        </td> 
                        <?php } // Fin del chequeo de Administrador para la celda de botones de acción ?> 
                    </tr> 
                    <?php } // Fin del bucle de empresas ?> 
                </tbody> 
            </table> 
        </div> 
    </div> 
</div> 
<?php // Cierra los contenedores HTML y carga las dependencias
require_once 'inc/footer.inc.php'; // Incluye el archivo de plantilla del pie de página
?>
