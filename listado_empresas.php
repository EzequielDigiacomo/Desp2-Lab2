<?php 
require_once 'inc/header.inc.php'; 
$empresas_lista = Listar_Empresas($vConexion); 
$total_empresas = count($empresas_lista); 
?>
<h1 class="h3 mb-3"><strong>Empresas</strong> Listado general.</h1> 
<div class="row"> 
    <div class="col-12 col-lg-12 col-xxl-12 d-flex"> 
        <div class="card flex-fill"> 
            <div class="card-header"> 
                <h4 class="text-info">Visualizando <?php echo $total_empresas; ?> registros</h4> 
                <?php // isset verifica si la variable de sesion existe ?>
                <?php if (isset($_SESSION['Mensaje_Empresa'])) { ?> 
                <div class="alert alert-<?php echo $_SESSION['Estilo_Empresa']; ?> alert-dismissible mt-2 mb-0" role="alert"> 
                    <div class="alert-message"> 
                        <?php echo $_SESSION['Mensaje_Empresa']; ?> 
                    </div> 
                </div> 
                <?php // unset elimina la variable de sesion para que no se repita el cartel ?>
                <?php unset($_SESSION['Mensaje_Empresa']); ?> 
                <?php unset($_SESSION['Estilo_Empresa']); ?> 
                <?php } ?> 
            </div> 
            <table class="table table-hover my-0"> 
                <thead> 
                    <tr> 
                        <th>#</th> 
                        <th>Denominación</th> 
                        <th>Fecha de carga</th> 
                        <th>Cargada por</th> 
                        <?php if ($_SESSION['Usuario_Nivel'] == 1) { ?> 
                        <th>Acciones</th> 
                        <?php } ?> 
                    </tr> 
                </thead> 
                <tbody> 
                    <?php 
                    $contador = 1; 
                    foreach ($empresas_lista as $empresa) { 
                        
                        $flag_file = 'URU.jpg'; 
                        if ($empresa['PAIS'] == 'Argentina') { 
                            $flag_file = 'ARG.jpg'; 
                        } elseif ($empresa['PAIS'] == 'Brasil') { 
                            $flag_file = 'BRA.jpg'; 
                        } elseif ($empresa['PAIS'] == 'Chile') { 
                            $flag_file = 'CHI.jpg'; 
                        } 
                    ?> 
                    <tr> 
                        <td><?php echo $contador++; ?></td> 
                        <td> 
                            <img src="img/countries/<?php echo $flag_file; ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $empresa['PAIS']; ?>" title="<?php echo $empresa['PAIS']; ?>"> 
                            <?php echo $empresa['DENOMINACION']; ?> 
                        </td> 
                        <td> 
                            <?php echo date('d/m/Y', strtotime($empresa['FECHA_CARGA'])); ?> 
                        </td> 
                        <td> 
                            <img src="img/avatars/<?php echo $empresa['CREADOR_IMG']; ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $empresa['USUARIO_CARGA']; ?>"> 
                            <?php echo $empresa['CREADOR_COMPLETO']; ?> 
                        </td> 
                        <?php if ($_SESSION['Usuario_Nivel'] == 1) { ?> 
                        <td> 
                            <a class="btn btn-primary btn-sm success" href="editar_empresas.php?id=<?php echo $empresa['ID']; ?>"><span data-feather="edit"></span> Editar</a> 
                            <a class="btn btn-danger btn-sm" href="borrar_empresa.php?id=<?php echo $empresa['ID']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta empresa?');"><span data-feather="delete"></span> Borrar</a> 
                        </td> 
                        <?php } ?> 
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
