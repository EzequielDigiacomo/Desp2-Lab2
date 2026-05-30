<?php // Inicia el script PHP para la página del listado de proyectos
require_once __DIR__ . '/inc/header.inc.php'; // Incluye el encabezado con guardias de seguridad y conexión a la base de datos
$proyectos_lista = Listar_Proyectos($vConexion); // Obtiene todos los proyectos no eliminados de la base de datos
$total_proyectos = count($proyectos_lista); // Cuenta la cantidad total de proyectos obtenidos
?>
<h1 class="h3 mb-3"><strong>Proyectos</strong> Listado general.</h1> 
<div class="row"> 
    <div class="col-12 col-lg-12 col-xxl-12 d-flex"> 
        <div class="card flex-fill"> 
            <div class="card-header"> 
                <h4 class="text-info">Visualizando <?php echo $total_proyectos; // Muestra la cantidad de proyectos activos ?> registros</h4> 
                <?php if (isset($_SESSION['Mensaje_Proyecto'])) { // Verifica si hay algún mensaje de retroalimentación de redirección para mostrar ?> 
                <div class="alert alert-<?php echo $_SESSION['Estilo_Proyecto']; // Renderiza el tipo de alerta dinámico ?> alert-dismissible mt-2 mb-0" role="alert"> 
                    <div class="alert-message"> 
                        <?php echo $_SESSION['Mensaje_Proyecto']; // Muestra el texto del mensaje ?> 
                    </div> 
                </div> 
                <?php unset($_SESSION['Mensaje_Proyecto']); // Limpia el mensaje después de mostrarlo para evitar repeticiones ?> 
                <?php unset($_SESSION['Estilo_Proyecto']); // Limpia la variable de estilo ?> 
                <?php } // Fin del chequeo de mensajes de sesión ?> 
            </div> 
            <table class="table table-hover my-0"> 
                <thead> 
                    <tr> 
                        <th>#</th> 
                        <th>Denominación</th> 
                        <th class="d-none d-md-table-cell">Fecha Carga</th> 
                        <th class="d-none d-md-table-cell">Empresa</th> 
                        <th>Estado</th> 
                        <th class="d-none d-md-table-cell">Lider</th> 
                        <th>Acciones</th> 
                    </tr> 
                </thead> 
                <tbody> 
                    <?php // Recorre cada proyecto en la lista
                    $cnt = 1; // Inicializa el contador de filas
                    foreach ($proyectos_lista as $proyecto) { // Itera sobre la colección de filas de proyectos
                        // Asigna la clase de color del badge según el ID de estado actual
                        $badge_class = 'bg-secondary'; // Establece la clase de color por defecto
                        if ($proyecto['ESTADO_ID'] == 1) { // Verifica si el estado es Análisis Iniciado (1)
                            $badge_class = 'bg-info'; // Establece badge de color celeste
                        } elseif ($proyecto['ESTADO_ID'] == 2) { // Verifica si el estado es En Desarrollo (2)
                            $badge_class = 'bg-warning'; // Establece badge de color amarillo
                        } elseif ($proyecto['ESTADO_ID'] == 3) { // Verifica si el estado es Terminado (3)
                            $badge_class = 'bg-success'; // Establece badge de color verde
                        } elseif ($proyecto['ESTADO_ID'] == 4) { // Verifica si el estado es Cancelado (4)
                            $badge_class = 'bg-danger'; // Establece badge de color rojo
                        } // Fin del bloque de mapeo de badges de estado
                        
                        // Mapea los nombres de archivo de las banderas de países según el nombre del país
                        $flag_file = 'URU.jpg'; // Establece Uruguay como bandera de país por defecto
                        if ($proyecto['PAIS'] == 'Argentina') { // Verifica si es Argentina
                            $flag_file = 'ARG.jpg'; // Asigna el archivo de bandera correspondiente
                        } elseif ($proyecto['PAIS'] == 'Brasil') { // Verifica si es Brasil
                            $flag_file = 'BRA.jpg'; // Asigna el archivo de bandera correspondiente
                        } elseif ($proyecto['PAIS'] == 'Chile') { // Verifica si es Chile
                            $flag_file = 'CHI.jpg'; // Asigna el archivo de bandera correspondiente
                        } // Fin del bloque de mapeo de banderas
                    ?> 
                    <tr> 
                        <td><?php echo $cnt++; // Muestra el índice de la fila e incrementa ?></td> 
                        <td> 
                            <?php if ($proyecto['PRIORIDAD'] == 1) { // Renderiza el icono de estrella si el proyecto tiene prioridad alta ?> 
                            <i data-feather="star" class="text-warning align-middle me-1"></i> 
                            <?php } // Fin del chequeo de prioridad ?> 
                            <?php echo htmlspecialchars($proyecto['DENOMINACION']); // Muestra la denominación sanitizada del proyecto ?> 
                        </td> 
                        <td class="d-none d-md-table-cell"> 
                            <?php echo date('d/m/Y', strtotime($proyecto['FECHA_CARGA'])); // Formatea y muestra la fecha de carga ?> 
                        </td> 
                        <td class="d-none d-md-table-cell"> 
                            <img src="img/countries/<?php echo $flag_file; // Muestra el nombre del archivo de la bandera ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $proyecto['PAIS']; ?>" title="<?php echo $proyecto['PAIS']; ?>"> 
                            <?php echo htmlspecialchars($proyecto['EMPRESA']); // Muestra el nombre de la empresa de forma segura ?> 
                        </td> 
                        <td> 
                            <span class="badge <?php echo $badge_class; // Muestra la clase de estilo del badge ?>"><?php echo htmlspecialchars(mb_strtoupper($proyecto['ESTADO_NOMBRE'], 'UTF-8')); // Muestra el nombre del estado en mayúsculas ?></span> 
                        </td> 
                        <td class="d-none d-md-table-cell"> 
                            <img src="img/avatars/<?php echo $proyecto['LIDER_IMG']; // Muestra la imagen de avatar del líder ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $proyecto['LIDER_COMPLETO']; ?>"> 
                            <?php echo htmlspecialchars($proyecto['LIDER_COMPLETO']); // Muestra el nombre del líder de forma segura ?> 
                        </td> 
                        <td> 
                            <a class="btn btn-primary btn-sm success" href="editar_proyecto.php?id=<?php echo $proyecto['ID']; // Enlace parametrizado para editar el proyecto ?>"><span data-feather="edit"></span> Editar</a> 
                            <?php if ($_SESSION['Usuario_Nivel'] == 1) { // Renderiza opciones de cancelación solo si el usuario es un Administrador (Nivel 1) ?> 
                                <?php if ($proyecto['ESTADO_ID'] != 4) { // Renderiza la acción de cancelar solo si el estado del proyecto no es Cancelado (4) ?> 
                                <a class="btn btn-warning btn-sm" href="cancelar_proyecto.php?id=<?php echo $proyecto['ID']; // Enlace parametrizado para cancelar el proyecto ?>" onclick="return confirm('¿Estás seguro de que deseas cancelar este proyecto?');"><span data-feather="alert-triangle"></span> Cancelar</a> 
                                <?php } else { // Si ya está cancelado, muestra el botón de cancelación deshabilitado ?> 
                                <button class="btn btn-secondary btn-sm" disabled><span data-feather="alert-triangle"></span> Cancelado</button> 
                                <?php } // Fin del chequeo de acción según el estado ?> 
                            <?php } // Fin del chequeo de Administrador para el botón de cancelar del proyecto ?> 
                        </td> 
                    </tr> 
                    <?php } // Fin del bucle de proyectos ?> 
                </tbody> 
            </table> 
        </div> 
    </div> 
</div> 
<?php // Cierra los contenedores HTML y carga las dependencias
require_once __DIR__ . '/inc/footer.inc.php'; // Incluye el pie de página y etiquetas de cierre
?>
