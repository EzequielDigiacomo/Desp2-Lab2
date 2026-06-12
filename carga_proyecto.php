<?php 
require_once 'inc/header.inc.php'; 

$empresas = Listar_Empresas($vConexion); 
$lideres = Listar_Lideres($vConexion); 

$Msg_Exito = false; 
$Msg_Error = ""; 

if (!empty($_POST['btnRegistrar'])) { 
    $vDenominacion = trim($_POST['txtDenominacion']); 
    $vEmpresa = intval($_POST['selEmpresa']); 
    $vLider = intval($_POST['selLider']); 
    $vObservaciones = trim($_POST['txtObservaciones']); 
    // Verifica si se marco la casilla de prioridad
    $vPrioridad = isset($_POST['chkPrioridad']) ? 1 : 0; 
    $vUsuarioCarga = $_SESSION['Usuario']; 

    // Valida que el nombre tenga al menos 3 letras
    if (strlen($vDenominacion) < 3) { 
        $Msg_Error = "El nombre del proyecto debe tener al menos 3 caracteres."; 
    } elseif ($vEmpresa <= 0) { 
        $Msg_Error = "Debes seleccionar una empresa válida para el proyecto."; 
    } elseif ($vLider <= 0) { 
        $Msg_Error = "Debes asignar un líder al proyecto."; 
    } else { 
        $exito = Insertar_Proyecto($vConexion, $vDenominacion, $vEmpresa, $vLider, $vObservaciones, $vPrioridad, $vUsuarioCarga); 
        if ($exito) { 
            $Msg_Exito = true; 
        } else { 
            $Msg_Error = "No se pudo guardar el proyecto en el sistema. Intenta de nuevo."; 
        } 
    } 
} 
?>
<div class="mb-3"> 
    <h1 class="h3 mb-3"><strong>Proyectos</strong> Cargar nuevo.</h1> 
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
                <form method="POST" action="carga_proyecto.php"> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Denominación <i class="align-middle me-2" data-feather="command"></i></h5> 
                        <input type="text" name="txtDenominacion" class="form-control" placeholder="Ingresa el nombre del Proyecto" value="<?php echo isset($_POST['txtDenominacion']) && !$Msg_Exito ? $_POST['txtDenominacion'] : ''; ?>"> 
                    </div> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Empresa <i class="align-middle me-2" data-feather="command"></i></h5> 
                        <select name="selEmpresa" class="form-select"> 
                            <option value="0">Para quien trabajaremos...</option> 
                            <?php foreach ($empresas as $empresa) { ?> 
                            <option value="<?php echo $empresa['ID']; ?>" <?php echo isset($_POST['selEmpresa']) && $_POST['selEmpresa'] == $empresa['ID'] && !$Msg_Exito ? 'selected' : ''; ?>> 
                                <?php echo $empresa['DENOMINACION']; ?> 
                            </option> 
                            <?php } ?> 
                        </select> 
                    </div> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Líder <i class="align-middle me-2" data-feather="command"></i></h5> 
                        <select name="selLider" class="form-select"> 
                            <option value="0">Selecciona una opción</option> 
                            <?php foreach ($lideres as $lider) { ?> 
                            <option value="<?php echo $lider['ID']; ?>" <?php echo isset($_POST['selLider']) && $_POST['selLider'] == $lider['ID'] && !$Msg_Exito ? 'selected' : ''; ?>> 
                                <?php echo $lider['NOMBRE_COMPLETO']; ?> 
                            </option> 
                            <?php } ?> 
                        </select> 
                    </div> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Observaciones</h5> 
                        <textarea name="txtObservaciones" class="form-control" rows="2" placeholder="Observaciones del tema..."><?php echo isset($_POST['txtObservaciones']) && !$Msg_Exito ? $_POST['txtObservaciones'] : ''; ?></textarea> 
                    </div> 
                    <div class="mb-3"> 
                        <label class="form-check m-0"> 
                            <input name="chkPrioridad" class="form-check-input" type="checkbox" value="1" <?php echo isset($_POST['chkPrioridad']) && !$Msg_Exito ? 'checked' : ''; ?>> 
                            <span class="form-check-label"> 
                                Tildar si es solicitado con prioridad 
                            </span> 
                        </label> 
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
