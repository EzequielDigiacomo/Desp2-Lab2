<?php // Inicia el script PHP para la página de registro de empresas
require_once 'inc/header.inc.php'; // Incluye el encabezado con guardias de sesión y conexión a la base de datos

// Restringe el acceso: solo el Administrador (Nivel 1) puede ver esta página de creación de empresas
if ($_SESSION['Usuario_Nivel'] != 1) { // Verifica si el usuario autenticado no es un Administrador
    header('Location: index.php'); // Redirecciona al usuario no autorizado al panel de control
    exit(); // Detiene la ejecución del script
} // Fin de la verificación de la guardia de acceso del Administrador

$paises = Listar_Paises($vConexion); // Obtiene la lista de todos los países activos no eliminados para el selector desplegable

$Msg_Exito = false; // Bandera para activar la visualización del mensaje de éxito
$Msg_Error = ""; // Cadena para almacenar mensajes dinámicos de errores de validación

if (!empty($_POST['btnRegistrar'])) { // Verifica si el formulario fue enviado mediante POST
    $vDenominacion = trim(strip_tags($_POST['txtDenominacion'])); // Limpia y sanitiza la entrada del nombre de la empresa
    $vPais = intval($_POST['selPais']); // Obtiene el ID del país seleccionado y lo convierte a entero
    $vObservaciones = trim(strip_tags($_POST['txtObservaciones'])); // Limpia y sanitiza el texto de observaciones
    $vUsuarioCarga = $_SESSION['Usuario']; // Obtiene el nombre de usuario de la sesión activa

    if (strlen($vDenominacion) < 3) { // Verifica si la denominación tiene menos de 3 caracteres
        $Msg_Error = "El nombre de la empresa debe tener al menos 3 caracteres."; // Define el mensaje de error de validación
    } elseif ($vPais <= 0) { // Verifica si no se ha seleccionado un país válido
        $Msg_Error = "Debes seleccionar un país de operaciones."; // Define el mensaje de error de validación
    } else { // Procede si se cumplen todas las condiciones de validación
        $exito = Insertar_Empresa($vConexion, $vDenominacion, $vPais, $vObservaciones, $vUsuarioCarga); // Llama a la utilidad de inserción
        if ($exito) { // Verifica si la consulta de inserción en la base de datos fue exitosa
            $Msg_Exito = true; // Establece la bandera de visualización de éxito en verdadero
        } else { // Manejo de fallo en la escritura de la base de datos
            $Msg_Error = "No se pudo guardar la empresa. Intenta de nuevo."; // Define el mensaje de error al guardar
        } // Fin de la verificación de la consulta de inserción
    } // Fin del chequeo de validaciones
} // Fin del chequeo de POST
?>
<div class="mb-3"> 
    <h1 class="h3 d-inline align-middle">Cargar Nueva Empresa</h1> 
</div> 
<div class="row"> 
    <div class="col-12 col-lg-6"> 
        <div class="card"> 
            <div class="card-header pb-0"> 
                <?php if ($Msg_Exito) { // Verifica si la empresa fue registrada con éxito ?> 
                <h4 class="text-success text-center"> 
                    <i class="align-middle" data-feather="check-square"></i> Registro cargado correctamente. 
                </h4> 
                <?php } // Fin del chequeo de éxito ?> 
                <?php if (!empty($Msg_Error)) { // Verifica si ocurrió un error de validación ?> 
                <h4 class="text-danger text-center"> 
                    <i class="align-middle me-2" data-feather="alert-circle"></i> <?php echo $Msg_Error; // Muestra el mensaje de error personalizado ?> 
                </h4> 
                <?php } // Fin del chequeo de error ?> 
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
                            <?php foreach ($paises as $pais) { // Recorre las filas de los países para construir las opciones ?> 
                            <option value="<?php echo $pais['ID']; ?>" <?php echo isset($_POST['selPais']) && $_POST['selPais'] == $pais['ID'] && !$Msg_Exito ? 'selected' : ''; ?>> 
                                <?php echo htmlspecialchars($pais['NOMBRE']); // Muestra la denominación del país ?> 
                            </option> 
                            <?php } // Fin del bucle de países ?> 
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
<?php // Cierra las etiquetas HTML e incluye los scripts finales
require_once 'inc/footer.inc.php'; // Incluye el archivo de plantilla del pie de página
?>
