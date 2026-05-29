<?php // Start PHP script for company registration page
require_once __DIR__ . '/inc/header.inc.php'; // Include header with session guards and database connection

// Restrict access: only Admin (level 1) can view this company creation page
if ($_SESSION['Usuario_Nivel'] != 1) { // Check if the logged-in user is not an Admin
    header('Location: index.php'); // Redirect unauthorized user back to dashboard home
    exit(); // Stop further script execution
} // End Admin access guard check

$paises = Listar_Paises($vConexion); // Retrieve list of all active non-deleted countries for dropdown selector

$Msg_Exito = false; // Flag to trigger success message display
$Msg_Error = ""; // String to hold dynamic validation error alerts

if (!empty($_POST['btnRegistrar'])) { // Check if form was submitted via POST
    $vDenominacion = trim(strip_tags($_POST['txtDenominacion'])); // Clean and sanitize company name input
    $vPais = intval($_POST['selPais']); // Retrieve selected country ID and cast to integer
    $vObservaciones = trim(strip_tags($_POST['txtObservaciones'])); // Clean and sanitize observations text
    $vUsuarioCarga = $_SESSION['Usuario']; // Retrieve username from active session

    if (strlen($vDenominacion) < 3) { // Check if denomination has less than 3 characters
        $Msg_Error = "El nombre de la empresa debe tener al menos 3 caracteres."; // Set validation error message
    } elseif ($vPais <= 0) { // Check if a valid country has not been selected
        $Msg_Error = "Debes seleccionar un país de operaciones."; // Set validation error message
    } else { // Proceed if all validation conditions are met
        $exito = Insertar_Empresa($vConexion, $vDenominacion, $vPais, $vObservaciones, $vUsuarioCarga); // Call insert utility
        if ($exito) { // Check if database insert query was successful
            $Msg_Exito = true; // Mark success display flag as true
        } else { // Handle database write failure
            $Msg_Error = "No se pudo guardar la empresa. Intenta de nuevo."; // Set save error message
        } // End insert query check
    } // End validations check
} // End POST check
?>
<div class="mb-3"> 
    <h1 class="h3 d-inline align-middle">Cargar Nueva Empresa</h1> 
</div> 
<div class="row"> 
    <div class="col-12 col-lg-6"> 
        <div class="card"> 
            <div class="card-header pb-0"> 
                <?php if ($Msg_Exito) { // Check if company was registered successfully ?> 
                <h4 class="text-success text-center"> 
                    <i class="align-middle" data-feather="check-square"></i> Registro cargado correctamente. 
                </h4> 
                <?php } // End success check ?> 
                <?php if (!empty($Msg_Error)) { // Check if a validation error occurred ?> 
                <h4 class="text-danger text-center"> 
                    <i class="align-middle me-2" data-feather="alert-circle"></i> <?php echo $Msg_Error; // Output custom error message ?> 
                </h4> 
                <?php } // End error check ?> 
                <h4 class="text-info text-center mt-2"> 
                    Los campos con <i class="align-middle me-2" data-feather="command"></i> son obligatorios 
                </h4> 
            </div> 
            <div class="card-body"> 
                <form method="POST" action="carga_empresa.php"> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Denominación <i class="align-middle me-2" data-feather="command"></i></h5> 
                        <input type="text" name="txtDenominacion" class="form-control" placeholder="Ingresa el nombre" required value="<?php echo isset($_POST['txtDenominacion']) && !$Msg_Exito ? htmlspecialchars($_POST['txtDenominacion']) : ''; ?>"> 
                    </div> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Pais <i class="align-middle me-2" data-feather="command"></i></h5> 
                        <select name="selPais" class="form-select" required> 
                            <option value="0">Elige una opción</option> 
                            <?php foreach ($paises as $pais) { // Loop through country rows to build options ?> 
                            <option value="<?php echo $pais['ID']; ?>" <?php echo isset($_POST['selPais']) && $_POST['selPais'] == $pais['ID'] && !$Msg_Exito ? 'selected' : ''; ?>> 
                                <?php echo htmlspecialchars($pais['NOMBRE']); // Output country designation ?> 
                            </option> 
                            <?php } // End countries traversal loop ?> 
                        </select> 
                    </div> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Observaciones</h5> 
                        <textarea name="txtObservaciones" class="form-control" rows="2" placeholder="Comentarios generales..."><?php echo isset($_POST['txtObservaciones']) && !$Msg_Exito ? htmlspecialchars($_POST['txtObservaciones']) : ''; ?></textarea> 
                    </div> 
                    <div class="d-grid mt-3"> 
                        <input type="submit" name="btnRegistrar" class="btn btn-primary" value="Registrar Datos" /> 
                    </div> 
                </form> 
            </div> 
        </div> 
    </div> 
</div> 
<?php // Close HTML tags and include scripts
require_once __DIR__ . '/inc/footer.inc.php'; // Include footer template file
?>
