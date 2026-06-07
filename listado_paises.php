<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
} 
if (empty($_SESSION['Usuario'])) { 
    header('Location: login.php'); 
    exit(); 
} 
if ($_SESSION['Usuario_Nivel'] != 1) { 
    header('Location: index.php'); 
    exit(); 
} 
require_once 'inc/header.inc.php'; 
$paises_lista = Listar_Paises($vConexion); 
$total_paises = count($paises_lista); 
?>
<h1 class="h3 mb-3"><strong>Paises con que trabajamos.</strong> Listado general.</h1> 
<div class="row"> 
    <div class="col-12 col-lg-12 col-xxl-12 d-flex"> 
        <div class="card flex-fill"> 
            <div class="card-header"> 
                <h4 class="text-info">Visualizando <?php echo $total_paises; ?> registros</h4> 
                <?php // isset verifica si la variable de sesion existe ?>
                <?php if (isset($_SESSION['Mensaje_Pais'])) { ?> 
                <div class="alert alert-<?php echo $_SESSION['Estilo_Pais']; ?> alert-dismissible mt-2 mb-0" role="alert"> 
                    <div class="alert-message"> 
                        <?php echo $_SESSION['Mensaje_Pais']; ?> 
                    </div> 
                </div> 
                <?php // unset elimina la variable de sesion para que no se repita el cartel ?>
                <?php unset($_SESSION['Mensaje_Pais']); ?> 
                <?php unset($_SESSION['Estilo_Pais']); ?> 
                <?php } ?> 
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
                    <?php 
                    $contador = 1; 
                    foreach ($paises_lista as $pais) { 
                        
                        $flag_file = 'URU.jpg'; 
                        if ($pais['NOMBRE'] == 'Argentina') { 
                            $flag_file = 'ARG.jpg'; 
                        } elseif ($pais['NOMBRE'] == 'Brasil') { 
                            $flag_file = 'BRA.jpg'; 
                        } elseif ($pais['NOMBRE'] == 'Chile') { 
                            $flag_file = 'CHI.jpg'; 
                        } 
                    ?> 
                    <tr> 
                        <td><?php echo $contador++; ?></td> 
                        <td><?php echo $pais['NOMBRE']; ?></td> 
                        <td> 
                            <img src="img/countries/<?php echo $flag_file; ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $pais['NOMBRE']; ?>"> 
                        </td> 
                        <td> 
                            <a class="btn btn-primary btn-sm success" href="editar_pais.php?id=<?php echo $pais['ID']; ?>"><span data-feather="edit"></span> Editar</a> 
                            <a class="btn btn-danger btn-sm" href="borrar_pais.php?id=<?php echo $pais['ID']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este país?');"><span data-feather="delete"></span> Borrar</a> 
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
