<?php // Start PHP script for projects listing page
require_once __DIR__ . '/inc/header.inc.php'; // Include header with security guards and database connection
$proyectos_lista = Listar_Proyectos($vConexion); // Retrieve all non-deleted projects from database
$total_proyectos = count($proyectos_lista); // Count the total number of projects retrieved
?>
<h1 class="h3 mb-3"><strong>Proyectos</strong> Listado general.</h1> 
<div class="row"> 
    <div class="col-12 col-lg-12 col-xxl-12 d-flex"> 
        <div class="card flex-fill"> 
            <div class="card-header"> 
                <h4 class="text-info">Visualizando <?php echo $total_proyectos; // Display active projects count ?> registros</h4> 
                <?php if (isset($_SESSION['Mensaje_Proyecto'])) { // Check if there is a redirection feedback message to display ?> 
                <div class="alert alert-<?php echo $_SESSION['Estilo_Proyecto']; // Render dynamic alert type ?> alert-dismissible mt-2 mb-0" role="alert"> 
                    <div class="alert-message"> 
                        <?php echo $_SESSION['Mensaje_Proyecto']; // Output message text ?> 
                    </div> 
                </div> 
                <?php unset($_SESSION['Mensaje_Proyecto']); // Clear message after display to avoid repetition ?> 
                <?php unset($_SESSION['Estilo_Proyecto']); // Clear style variable ?> 
                <?php } // End session message check ?> 
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
                    <?php // Loop through each project in the list
                    $cnt = 1; // Initialize row counter
                    foreach ($proyectos_lista as $proyecto) { // Iterate over project rows collection
                        // Assign background badge color class depending on current status ID
                        $badge_class = 'bg-secondary'; // Set default fallback color class
                        if ($proyecto['ESTADO_ID'] == 1) { // Check if status is Analisis Iniciado (1)
                            $badge_class = 'bg-info'; // Set sky blue color badge
                        } elseif ($proyecto['ESTADO_ID'] == 2) { // Check if status is En Desarrollo (2)
                            $badge_class = 'bg-warning'; // Set yellow color badge
                        } elseif ($proyecto['ESTADO_ID'] == 3) { // Check if status is Terminado (3)
                            $badge_class = 'bg-success'; // Set green color badge
                        } elseif ($proyecto['ESTADO_ID'] == 4) { // Check if status is Cancelado (4)
                            $badge_class = 'bg-danger'; // Set red color badge
                        } // End status badge mapping block
                        
                        // Map country flag filenames based on country string names
                        $flag_file = 'URU.jpg'; // Set Uruguay as default fallback flag
                        if ($proyecto['PAIS'] == 'Argentina') { // Check for Argentina
                            $flag_file = 'ARG.jpg'; // Match flag file name
                        } elseif ($proyecto['PAIS'] == 'Brasil') { // Check for Brasil
                            $flag_file = 'BRA.jpg'; // Match flag file name
                        } elseif ($proyecto['PAIS'] == 'Chile') { // Check for Chile
                            $flag_file = 'CHI.jpg'; // Match flag file name
                        } // End flag mapping block
                    ?> 
                    <tr> 
                        <td><?php echo $cnt++; // Output index and increment ?></td> 
                        <td> 
                            <?php if ($proyecto['PRIORIDAD'] == 1) { // Render star icon if project has high priority ?> 
                            <i data-feather="star" class="text-warning align-middle me-1"></i> 
                            <?php } // End priority check ?> 
                            <?php echo htmlspecialchars($proyecto['DENOMINACION']); // Output sanitized project denomination ?> 
                        </td> 
                        <td class="d-none d-md-table-cell"> 
                            <?php echo date('d/m/Y', strtotime($proyecto['FECHA_CARGA'])); // Format and output charge date ?> 
                        </td> 
                        <td class="d-none d-md-table-cell"> 
                            <img src="img/countries/<?php echo $flag_file; // Output flag file name ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $proyecto['PAIS']; ?>" title="<?php echo $proyecto['PAIS']; ?>"> 
                            <?php echo htmlspecialchars($proyecto['EMPRESA']); // Output company name safely ?> 
                        </td> 
                        <td> 
                            <span class="badge <?php echo $badge_class; // Output status badge styling class ?>"><?php echo htmlspecialchars(mb_strtoupper($proyecto['ESTADO_NOMBRE'], 'UTF-8')); // Output status name in uppercase ?></span> 
                        </td> 
                        <td class="d-none d-md-table-cell"> 
                            <img src="img/avatars/<?php echo $proyecto['LIDER_IMG']; // Output leader avatar image ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $proyecto['LIDER_COMPLETO']; ?>"> 
                            <?php echo htmlspecialchars($proyecto['LIDER_COMPLETO']); // Output composite leader name ?> 
                        </td> 
                        <td> 
                            <a class="btn btn-primary btn-sm success" href="editar_proyecto.php?id=<?php echo $proyecto['ID']; // Parametrized link to edit project ?>"><span data-feather="edit"></span> Editar</a> 
                            <?php if ($_SESSION['Usuario_Nivel'] == 1) { // Render cancel options only if the user is an Administrator (Level 1) ?> 
                                <?php if ($proyecto['ESTADO_ID'] != 4) { // Render cancel action only if project status is not already Cancelado (4) ?> 
                                <a class="btn btn-warning btn-sm" href="cancelar_proyecto.php?id=<?php echo $proyecto['ID']; // Parametrized link to cancel project ?>" onclick="return confirm('¿Estás seguro de que deseas cancelar este proyecto?');"><span data-feather="alert-triangle"></span> Cancelar</a> 
                                <?php } else { // If already cancelled, display disabled cancel button ?> 
                                <button class="btn btn-secondary btn-sm" disabled><span data-feather="alert-triangle"></span> Cancelado</button> 
                                <?php } // End status display action check ?> 
                            <?php } // End Admin check for project cancel button ?> 
                        </td> 
                    </tr> 
                    <?php } // End projects loop ?> 
                </tbody> 
            </table> 
        </div> 
    </div> 
</div> 
<?php // Close HTML structures and include dependencies
require_once __DIR__ . '/inc/footer.inc.php'; // Include footer and closing tags
?>
