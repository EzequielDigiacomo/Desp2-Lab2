<?php // Start PHP script for project entry form
require_once __DIR__ . '/inc/header.inc.php'; // Include header with session guards and database connection

$empresas = Listar_Empresas($vConexion); // Retrieve list of active companies for the client selector
$lideres = Listar_Lideres($vConexion); // Retrieve list of project leaders for the leader dropdown selector

$Msg_Exito = false; // Flag to display save success alert
$Msg_Error = ""; // String to hold custom error alert messages

if (!empty($_POST['btnRegistrar'])) { // Check if the registration form was submitted via POST
    $vDenominacion = trim(strip_tags($_POST['txtDenominacion'])); // Clean and sanitize the project name input
    $vEmpresa = intval($_POST['selEmpresa']); // Retrieve selected company ID, cast as integer
    $vLider = intval($_POST['selLider']); // Retrieve selected leader ID, cast as integer
    $vObservaciones = trim(strip_tags($_POST['txtObservaciones'])); // Clean and sanitize observations text
    $vPrioridad = isset($_POST['chkPrioridad']) ? 1 : 0; // Set priority flag: 1 if checked, 0 otherwise
    $vUsuarioCarga = $_SESSION['Usuario']; // Retrieve username of the creator from active session

    // Validate project name length
    if (strlen($vDenominacion) < 3) { // Check if project name is shorter than 3 characters
        $Msg_Error = "El nombre del proyecto debe tener al menos 3 caracteres."; // Set validation error message
    } elseif ($vEmpresa <= 0) { // Check if no valid client company was chosen
        $Msg_Error = "Debes seleccionar una empresa válida para el proyecto."; // Set validation error message
    } elseif ($vLider <= 0) { // Check if no valid leader was selected
        $Msg_Error = "Debes asignar un líder al proyecto."; // Set validation error message
    } else { // Proceed if all validations pass
        $exito = Insertar_Proyecto($vConexion, $vDenominacion, $vEmpresa, $vLider, $vObservaciones, $vPrioridad, $vUsuarioCarga); // Call insert function
        if ($exito) { // Check if database insert query succeeded
            $Msg_Exito = true; // Set success alert flag to true
        } else { // Handle database write failure
            $Msg_Error = "No se pudo guardar el proyecto en el sistema. Intenta de nuevo."; // Set general save failure error
        } // End insert query check
    } // End validations check
} // End POST check
?>
<div class="mb-3"> 
    <h1 class="h3 mb-3"><strong>Proyectos</strong> Cargar nuevo.</h1> 
</div> 
<div class="row"> 
    <div class="col-12 col-lg-6"> 
        <div class="card"> 
            <div class="card-header pb-0"> 
                <?php if ($Msg_Exito) { // Check if save was successful ?> 
                <h4 class="text-success text-center"> 
                    <i class="align-middle" data-feather="check-square"></i> Registro cargado correctamente. 
                </h4> 
                <?php } // End success message check ?> 
                <?php if (!empty($Msg_Error)) { // Check if there is an error to display ?> 
                <h4 class="text-danger text-center"> 
                    <i class="align-middle me-2" data-feather="alert-circle"></i> <?php echo $Msg_Error; // Output custom error text ?> 
                </h4> 
                <?php } // End error message check ?> 
                <h4 class="text-info text-center mt-2"> 
                    Los campos con <i class="align-middle me-2" data-feather="command"></i> son obligatorios 
                </h4> 
            </div> 
            <div class="card-body"> 
                <form method="POST" action="carga_proyecto.php"> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Denominación <i class="align-middle me-2" data-feather="command"></i></h5> 
                        <input type="text" name="txtDenominacion" class="form-control" placeholder="Ingresa el nombre del Proyecto" required value="<?php echo isset($_POST['txtDenominacion']) && !$Msg_Exito ? htmlspecialchars($_POST['txtDenominacion']) : ''; ?>"> 
                    </div> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Empresa <i class="align-middle me-2" data-feather="command"></i></h5> 
                        <select name="selEmpresa" class="form-select" required> 
                            <option value="0">Para quien trabajaremos...</option> 
                            <?php foreach ($empresas as $empresa) { // Loop through companies to render dropdown options ?> 
                            <option value="<?php echo $empresa['ID']; ?>" <?php echo isset($_POST['selEmpresa']) && $_POST['selEmpresa'] == $empresa['ID'] && !$Msg_Exito ? 'selected' : ''; ?>> 
                                <?php echo htmlspecialchars($empresa['DENOMINACION']); // Output sanitized company name ?> 
                            </option> 
                            <?php } // End companies options loop ?> 
                        </select> 
                    </div> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Líder <i class="align-middle me-2" data-feather="command"></i></h5> 
                        <select name="selLider" class="form-select" required> 
                            <option value="0">Selecciona una opción</option> 
                            <?php foreach ($lideres as $lider) { // Loop through leaders to build select options ?> 
                            <option value="<?php echo $lider['ID']; ?>" <?php echo isset($_POST['selLider']) && $_POST['selLider'] == $lider['ID'] && !$Msg_Exito ? 'selected' : ''; ?>> 
                                <?php echo htmlspecialchars($lider['NOMBRE_COMPLETO']); // Output sanitized leader name ?> 
                            </option> 
                            <?php } // End leaders option loop ?> 
                        </select> 
                    </div> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Observaciones</h5> 
                        <textarea name="txtObservaciones" class="form-control" rows="2" placeholder="Observaciones del tema..."><?php echo isset($_POST['txtObservaciones']) && !$Msg_Exito ? htmlspecialchars($_POST['txtObservaciones']) : ''; ?></textarea> 
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
<?php // Close the page body and layout wrappers
require_once __DIR__ . '/inc/footer.inc.php'; // Include footer scripts and closing tags
?>
