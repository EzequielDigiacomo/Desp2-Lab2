<?php 
require_once 'inc/header.inc.php'; 
$proyectos_lista = Listar_Proyectos($vConexion); 
$total_proyectos = count($proyectos_lista); 
?>
<h1 class="h3 mb-3"><strong>Proyectos</strong> Listado general.</h1> 
<div class="row"> 
    <div class="col-12 col-lg-12 col-xxl-12 d-flex"> 
        <div class="card flex-fill"> 
            <div class="card-header"> 
                <h4 class="text-info">Visualizando <?php echo $total_proyectos; ?> registros</h4> 
                <?php // isset verifica si la variable de sesion existe ?>
                <?php if (isset($_SESSION['Mensaje_Proyecto'])) { ?> 
                <div class="alert alert-<?php echo $_SESSION['Estilo_Proyecto']; ?> alert-dismissible mt-2 mb-0" role="alert"> 
                    <div class="alert-message"> 
                        <?php echo $_SESSION['Mensaje_Proyecto']; ?> 
                    </div> 
                </div> 
                <?php // unset elimina la variable de sesion para que no se repita el cartel ?>
                <?php unset($_SESSION['Mensaje_Proyecto']); ?> 
                <?php unset($_SESSION['Estilo_Proyecto']); ?> 
                <?php } ?> 
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
                    <?php 
                    $contador = 1; 
                    foreach ($proyectos_lista as $proyecto) { 
                        
                        if (isset($_GET['id_cancelado_simulado']) && intval($_GET['id_cancelado_simulado']) == $proyecto['ID']) {
                            $proyecto['ESTADO_ID'] = 4;
                            $proyecto['ESTADO_NOMBRE'] = 'Cancelado';
                        }

                        
                        $badge_class = 'bg-secondary'; 
                        if ($proyecto['ESTADO_ID'] == 1) { 
                            $badge_class = 'bg-info'; 
                        } elseif ($proyecto['ESTADO_ID'] == 2) { 
                            $badge_class = 'bg-warning'; 
                        } elseif ($proyecto['ESTADO_ID'] == 3) { 
                            $badge_class = 'bg-success'; 
                        } elseif ($proyecto['ESTADO_ID'] == 4) { 
                            $badge_class = 'bg-danger'; 
                        } 
                        
                        
                        $flag_file = 'URU.jpg'; 
                        if ($proyecto['PAIS'] == 'Argentina') { 
                            $flag_file = 'ARG.jpg'; 
                        } elseif ($proyecto['PAIS'] == 'Brasil') { 
                            $flag_file = 'BRA.jpg'; 
                        } elseif ($proyecto['PAIS'] == 'Chile') { 
                            $flag_file = 'CHI.jpg'; 
                        } 
                    ?> 
                    <tr> 
                        <td><?php echo $contador++; ?></td> 
                        <td> 
                            <?php if ($proyecto['PRIORIDAD'] == 1) { ?> 
                            <i data-feather="star" class="text-warning align-middle me-1"></i> 
                            <?php } ?> 
                            <?php echo $proyecto['DENOMINACION']; ?> 
                        </td> 
                        <td class="d-none d-md-table-cell"> 
                            <?php echo date('d/m/Y', strtotime($proyecto['FECHA_CARGA'])); ?> 
                        </td> 
                        <td class="d-none d-md-table-cell"> 
                            <img src="img/countries/<?php echo $flag_file; ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $proyecto['PAIS']; ?>" title="<?php echo $proyecto['PAIS']; ?>"> 
                            <?php echo $proyecto['EMPRESA']; ?> 
                        </td> 
                        <td> 
                            <span class="badge <?php echo $badge_class; ?>"><?php echo mb_strtoupper($proyecto['ESTADO_NOMBRE'], 'UTF-8'); ?></span> 
                        </td> 
                        <td class="d-none d-md-table-cell"> 
                            <img src="img/avatars/<?php echo $proyecto['LIDER_IMG']; ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $proyecto['LIDER_COMPLETO']; ?>"> 
                            <?php echo $proyecto['LIDER_COMPLETO']; ?> 
                        </td> 
                        <td> 
                            <a class="btn btn-primary btn-sm success" href="editar_proyecto.php?id=<?php echo $proyecto['ID']; ?>"><span data-feather="edit"></span> Editar</a> 
                            <?php if ($_SESSION['Usuario_Nivel'] == 1) { ?> 
                                <?php if ($proyecto['ESTADO_ID'] != 4) { ?> 
                                <a class="btn btn-warning btn-sm" href="cancelar_proyecto.php?id=<?php echo $proyecto['ID']; ?>" onclick="return confirm('¿Estás seguro de que deseas cancelar este proyecto?');"><span data-feather="alert-triangle"></span> Cancelar</a> 
                                <?php } else { ?> 
                                <button class="btn btn-secondary btn-sm" disabled><span data-feather="alert-triangle"></span> Cancelado</button> 
                                <?php } ?> 
                            <?php } ?> 
                        </td> 
                    </tr> 
                    <?php } ?> 
                </tbody> 
            </table> 
        </div> 
    </div> 
</div> 
<?php 
require_once 'inc/footer.inc.php'; 
?>
