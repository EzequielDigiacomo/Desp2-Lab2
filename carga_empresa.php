<?php 
require_once 'inc/header.inc.php'; 

if ($_SESSION['Usuario_Nivel'] != 1) { 
    header('Location: index.php'); 
    exit(); 
} 

$paises = Listar_Paises($vConexion); 

$Msg_Exito = false; 
$Msg_Error = ""; 

if (!empty($_POST['btnRegistrar'])) { 
    $vDenominacion = trim($_POST['txtDenominacion']); 
    $vPais = intval($_POST['selPais']); 
    $vObservaciones = trim($_POST['txtObservaciones']); 
    $vUsuarioCarga = $_SESSION['Usuario']; 

    // Valida que el nombre tenga al menos 3 letras
    if (strlen($vDenominacion) < 3) { 
        $Msg_Error = "El nombre de la empresa debe tener al menos 3 caracteres."; 
    } elseif ($vPais <= 0) { 
        $Msg_Error = "Debes seleccionar un país de operaciones."; 
    } else { 
        $exito = Insertar_Empresa($vConexion, $vDenominacion, $vPais, $vObservaciones, $vUsuarioCarga); 
        if ($exito) { 
            $Msg_Exito = true; 
        } else { 
            $Msg_Error = "No se pudo guardar la empresa. Intenta de nuevo."; 
        } 
    } 
} 
?>
<div class="mb-3"> 
    <h1 class="h3 d-inline align-middle">Cargar Nueva Empresa</h1> 
</div> 
<div class="row"> 
    <div class="col-12 col-lg-6"> 
        <div class="card"> 
            <div class="card-header pb-0"> 
                <?php if ($Msg_Exito) { ?> 
                <h4 class="text-success text-center"> 
                    <i class="align-middle" data-feather="check-square"></i> Registro cargado correctamente. 
                </h4> 
                <?php } ?> 
                <?php if ($Msg_Error) { ?> 
                <h4 class="text-danger text-center"> 
                    <i class="align-middle me-2" data-feather="alert-circle"></i> <?php echo $Msg_Error; ?> 
                </h4> 
                <?php } ?> 
                <h4 class="text-info text-center mt-2"> 
                    Los campos con <i class="align-middle me-2" data-feather="command"></i> son obligatorios 
                </h4> 
            </div> 
            <div class="card-body"> 
                <form method="POST" action="carga_empresa.php"> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Denominación <i class="align-middle me-2" data-feather="command"></i></h5> 
                        <input type="text" name="txtDenominacion" class="form-control" placeholder="Ingresa el nombre" value="<?php echo isset($_POST['txtDenominacion']) && !$Msg_Exito ? $_POST['txtDenominacion'] : ''; ?>"> 
                    </div> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Pais <i class="align-middle me-2" data-feather="command"></i></h5> 
                        <select name="selPais" class="form-select"> 
                            <option value="0">Elige una opción</option> 
                            <?php foreach ($paises as $pais) { ?> 
                            <option value="<?php echo $pais['ID']; ?>" <?php echo isset($_POST['selPais']) && $_POST['selPais'] == $pais['ID'] && !$Msg_Exito ? 'selected' : ''; ?>> 
                                <?php echo $pais['NOMBRE']; ?> 
                            </option> 
                            <?php } ?> 
                        </select> 
                    </div> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Observaciones</h5> 
                        <textarea name="txtObservaciones" class="form-control" rows="2" placeholder="Comentarios generales..."><?php echo isset($_POST['txtObservaciones']) && !$Msg_Exito ? $_POST['txtObservaciones'] : ''; ?></textarea> 
                    </div> 
                    <div class="d-grid mt-3"> 
                        <input type="submit" name="btnRegistrar" class="btn btn-primary" value="Registrar Datos" /> 
                    </div> 
                </form> 
            </div> 
        </div> 
    </div> 
</div> 
<?php 
require_once 'inc/footer.inc.php'; 
?>
