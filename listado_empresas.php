<?php // Start PHP script for companies listing page
require_once __DIR__ . '/inc/header.inc.php'; // Include header with session guards and database connection
$empresas_lista = Listar_Empresas($vConexion); // Retrieve list of active companies from database
$total_empresas = count($empresas_lista); // Count the total number of active companies
?>
<h1 class="h3 mb-3"><strong>Empresas</strong> Listado general.</h1> 
<div class="row"> 
    <div class="col-12 col-lg-12 col-xxl-12 d-flex"> 
        <div class="card flex-fill"> 
            <div class="card-header"> 
                <h4 class="text-info">Visualizando <?php echo $total_empresas; // Output count of companies ?> registros</h4> 
                <?php if (isset($_SESSION['Mensaje_Empresa'])) { // Check for session feedback alerts ?> 
                <div class="alert alert-<?php echo $_SESSION['Estilo_Empresa']; // Render alert styling class ?> alert-dismissible mt-2 mb-0" role="alert"> 
                    <div class="alert-message"> 
                        <?php echo $_SESSION['Mensaje_Empresa']; // Output success/error feedback text ?> 
                    </div> 
                </div> 
                <?php unset($_SESSION['Mensaje_Empresa']); // Clean feedback message key from session ?> 
                <?php unset($_SESSION['Estilo_Empresa']); // Clean feedback style key from session ?> 
                <?php } // End session check ?> 
            </div> 
            <table class="table table-hover my-0"> 
                <thead> 
                    <tr> 
                        <th>#</th> 
                        <th>Denominación</th> 
                        <th>Fecha de carga</th> 
                        <th>Cargada por</th> 
                        <?php if ($_SESSION['Usuario_Nivel'] == 1) { // Render actions column header only for Administrator (Level 1) ?> 
                        <th>Acciones</th> 
                        <?php } // End Admin check for actions header ?> 
                    </tr> 
                </thead> 
                <tbody> 
                    <?php // Loop through each company in the list
                    $cnt = 1; // Initialize row counter
                    foreach ($empresas_lista as $empresa) { // Traverse the retrieved companies array
                        // Map flag images based on country string name
                        $flag_file = 'URU.jpg'; // Set Uruguay as default fallback country flag
                        if ($empresa['PAIS'] == 'Argentina') { // Check for Argentina
                            $flag_file = 'ARG.jpg'; // Match flag file name
                        } elseif ($empresa['PAIS'] == 'Brasil') { // Check for Brasil
                            $flag_file = 'BRA.jpg'; // Match flag file name
                        } elseif ($empresa['PAIS'] == 'Chile') { // Check for Chile
                            $flag_file = 'CHI.jpg'; // Match flag file name
                        } // End flag mapping block
                    ?> 
                    <tr> 
                        <td><?php echo $cnt++; // Output row index and increment ?></td> 
                        <td> 
                            <img src="img/countries/<?php echo $flag_file; // Output flag file name ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $empresa['PAIS']; ?>" title="<?php echo $empresa['PAIS']; ?>"> 
                            <?php echo htmlspecialchars($empresa['DENOMINACION']); // Output company name safely ?> 
                        </td> 
                        <td> 
                            <?php echo date('d/m/Y', strtotime($empresa['FECHA_CARGA'])); // Format and output upload date ?> 
                        </td> 
                        <td> 
                            <img src="img/avatars/<?php echo $empresa['CREADOR_IMG']; // Output creator avatar image ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $empresa['USUARIO_CARGA']; ?>"> 
                            <?php echo htmlspecialchars($empresa['CREADOR_COMPLETO']); // Output composite loaded by name safely ?> 
                        </td> 
                        <?php if ($_SESSION['Usuario_Nivel'] == 1) { // Render action buttons cell only for Administrator (Level 1) ?> 
                        <td> 
                            <a class="btn btn-primary btn-sm success" href="editar_empresa.php?id=<?php echo $empresa['ID']; // Parametrized link to edit company details ?>"><span data-feather="edit"></span> Editar</a> 
                            <a class="btn btn-danger btn-sm" href="borrar_empresa.php?id=<?php echo $empresa['ID']; // Parametrized link to delete company ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta empresa?');"><span data-feather="delete"></span> Borrar</a> 
                        </td> 
                        <?php } // End Admin check for action buttons cell ?> 
                    </tr> 
                    <?php } // End company loop ?> 
                </tbody> 
            </table> 
        </div> 
    </div> 
</div> 
<?php // Close HTML wrappers and load dependencies
require_once __DIR__ . '/inc/footer.inc.php'; // Include footer template file
?>
