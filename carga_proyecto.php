<?php // Inicia el script PHP para el formulario de ingreso de proyectos
require_once __DIR__ . '/inc/header.inc.php'; // Incluye el encabezado con guardias de sesión y conexión a la base de datos

$empresas = Listar_Empresas($vConexion); // Obtiene la lista de empresas activas para el selector del cliente
$lideres = Listar_Lideres($vConexion); // Obtiene la lista de líderes de proyectos para el desplegable de selección del líder

$Msg_Exito = false; // Bandera para mostrar la alerta de éxito al guardar
$Msg_Error = ""; // Cadena para almacenar mensajes dinámicos de alertas de error

if (!empty($_POST['btnRegistrar'])) { // Verifica si el formulario de registro fue enviado mediante POST
    $vDenominacion = trim(strip_tags($_POST['txtDenominacion'])); // Limpia y sanitiza la entrada del nombre del proyecto
    $vEmpresa = intval($_POST['selEmpresa']); // Obtiene el ID de la empresa seleccionada y lo convierte a entero
    $vLider = intval($_POST['selLider']); // Obtiene el ID del líder seleccionado y lo convierte a entero
    $vObservaciones = trim(strip_tags($_POST['txtObservaciones'])); // Limpia y sanitiza el texto de observaciones
    $vPrioridad = isset($_POST['chkPrioridad']) ? 1 : 0; // Establece la bandera de prioridad: 1 si está marcado, 0 en caso contrario
    $vUsuarioCarga = $_SESSION['Usuario']; // Obtiene el nombre de usuario del creador de la sesión activa

    // Valida la longitud del nombre del proyecto
    if (strlen($vDenominacion) < 3) { // Verifica si el nombre del proyecto tiene menos de 3 caracteres
        $Msg_Error = "El nombre del proyecto debe tener al menos 3 caracteres."; // Define el mensaje de error de validación
    } elseif ($vEmpresa <= 0) { // Verifica si no se seleccionó una empresa cliente válida
        $Msg_Error = "Debes seleccionar una empresa válida para el proyecto."; // Define el mensaje de error de validación
    } elseif ($vLider <= 0) { // Verifica si no se asignó un líder válido
        $Msg_Error = "Debes asignar un líder al proyecto."; // Define el mensaje de error de validación
    } else { // Procede si todas las validaciones son exitosas
        $exito = Insertar_Proyecto($vConexion, $vDenominacion, $vEmpresa, $vLider, $vObservaciones, $vPrioridad, $vUsuarioCarga); // Llama a la función de inserción
        if ($exito) { // Verifica si la consulta de inserción en la base de datos fue exitosa
            $Msg_Exito = true; // Establece la bandera de alerta de éxito en verdadero
        } else { // Manejo de fallo en la escritura de la base de datos
            $Msg_Error = "No se pudo guardar el proyecto en el sistema. Intenta de nuevo."; // Define el mensaje de error general al guardar
        } // Fin de la verificación de la consulta de inserción
    } // Fin del chequeo de validaciones
} // Fin del chequeo de POST
?>
<div class="mb-3"> 
    <h1 class="h3 mb-3"><strong>Proyectos</strong> Cargar nuevo.</h1> 
</div> 
<div class="row"> 
    <div class="col-12 col-lg-6"> 
        <div class="card"> 
            <div class="card-header pb-0"> 
                <?php if ($Msg_Exito) { // Verifica si el guardado fue exitoso ?> 
                <h4 class="text-success text-center"> 
                    <i class="align-middle" data-feather="check-square"></i> Registro cargado correctamente. 
                </h4> 
                <?php } // Fin de la verificación del mensaje de éxito ?> 
                <?php if (!empty($Msg_Error)) { // Verifica si hay un error para mostrar ?> 
                <h4 class="text-danger text-center"> 
                    <i class="align-middle me-2" data-feather="alert-circle"></i> <?php echo $Msg_Error; // Muestra el texto de error personalizado ?> 
                </h4> 
                <?php } // Fin de la verificación del mensaje de error ?> 
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
                            <?php foreach ($empresas as $empresa) { // Recorre las empresas para renderizar las opciones del desplegable ?> 
                            <option value="<?php echo $empresa['ID']; ?>" <?php echo isset($_POST['selEmpresa']) && $_POST['selEmpresa'] == $empresa['ID'] && !$Msg_Exito ? 'selected' : ''; ?>> 
                                <?php echo htmlspecialchars($empresa['DENOMINACION']); // Muestra el nombre sanitizado de la empresa ?> 
                            </option> 
                            <?php } // Fin del bucle de opciones de empresas ?> 
                        </select> 
                    </div> 
                    <div class="mb-3"> 
                        <h5 class="card-title mb-1">Líder <i class="align-middle me-2" data-feather="command"></i></h5> 
                        <select name="selLider" class="form-select" required> 
                            <option value="0">Selecciona una opción</option> 
                            <?php foreach ($lideres as $lider) { // Recorre los líderes para construir las opciones de selección ?> 
                            <option value="<?php echo $lider['ID']; ?>" <?php echo isset($_POST['selLider']) && $_POST['selLider'] == $lider['ID'] && !$Msg_Exito ? 'selected' : ''; ?>> 
                                <?php echo htmlspecialchars($lider['NOMBRE_COMPLETO']); // Muestra el nombre del líder sanitizado ?> 
                            </option> 
                            <?php } // Fin del bucle de opciones de líderes ?> 
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
<?php // Cierra el cuerpo de la página y las etiquetas del contenedor principal
require_once __DIR__ . '/inc/footer.inc.php'; // Incluye los scripts finales de pie de página y etiquetas de cierre
?>
