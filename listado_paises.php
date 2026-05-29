<?php // Start PHP script for countries listing page
if (session_status() === PHP_SESSION_NONE) { // Verify if session has not been started yet
    session_start(); // Initialize session safely to check roles
} // End session check
if (empty($_SESSION['Usuario'])) { // Verify if user is not logged in
    header('Location: login.php'); // Redirect unauthenticated user to login screen
    exit(); // Stop further script execution
} // End check
if ($_SESSION['Usuario_Nivel'] != 1) { // Verify if the logged-in user is not an Administrator (Level 1)
    header('Location: index.php'); // Redirect unauthorized user back to dashboard home
    exit(); // Terminate further script execution
} // End Admin check
require_once __DIR__ . '/inc/header.inc.php'; // Include header with session guards and database connection
$paises_lista = Listar_Paises($vConexion); // Retrieve list of active countries from database
$total_paises = count($paises_lista); // Count the total number of active countries
?>
<h1 class="h3 mb-3"><strong>Paises con que trabajamos.</strong> Listado general.</h1> 
<div class="row"> 
    <div class="col-12 col-lg-12 col-xxl-12 d-flex"> 
        <div class="card flex-fill"> 
            <div class="card-header"> 
                <h4 class="text-info">Visualizando <?php echo $total_paises; // Output active countries count ?> registros</h4> 
                <?php if (isset($_SESSION['Mensaje_Pais'])) { // Check for session feedback alerts ?> 
                <div class="alert alert-<?php echo $_SESSION['Estilo_Pais']; // Render alert styling class ?> alert-dismissible mt-2 mb-0" role="alert"> 
                    <div class="alert-message"> 
                        <?php echo $_SESSION['Mensaje_Pais']; // Output soft delete operation response ?> 
                    </div> 
                </div> 
                <?php unset($_SESSION['Mensaje_Pais']); // Clear feedback message from session ?> 
                <?php unset($_SESSION['Estilo_Pais']); // Clear feedback style ?> 
                <?php } // End session check ?> 
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
                    <?php // Loop through each country in the list
                    $cnt = 1; // Initialize row counter
                    foreach ($paises_lista as $pais) { // Traverse the retrieved countries array
                        // Map flag images based on country string name
                        $flag_file = 'URU.jpg'; // Set Uruguay as default fallback country flag
                        if ($pais['NOMBRE'] == 'Argentina') { // Check for Argentina
                            $flag_file = 'ARG.jpg'; // Match flag file name
                        } elseif ($pais['NOMBRE'] == 'Brasil') { // Check for Brasil
                            $flag_file = 'BRA.jpg'; // Match flag file name
                        } elseif ($pais['NOMBRE'] == 'Chile') { // Check for Chile
                            $flag_file = 'CHI.jpg'; // Match flag file name
                        } // End flag mapping block
                    ?> 
                    <tr> 
                        <td><?php echo $cnt++; // Output row index and increment ?></td> 
                        <td><?php echo htmlspecialchars($pais['NOMBRE']); // Output country name safely ?></td> 
                        <td> 
                            <img src="img/countries/<?php echo $flag_file; // Output country flag file name ?>" width="36" height="36" class="rounded-circle me-2" alt="<?php echo $pais['NOMBRE']; ?>"> 
                        </td> 
                        <td> 
                            <a class="btn btn-primary btn-sm success" href="editar_pais.php?id=<?php echo $pais['ID']; // Parametrized link to edit country details ?>"><span data-feather="edit"></span> Editar</a> 
                            <a class="btn btn-danger btn-sm" href="borrar_pais.php?id=<?php echo $pais['ID']; // Parametrized link to logically delete country ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este país?');"><span data-feather="delete"></span> Borrar</a> 
                        </td> 
                    </tr> 
                    <?php } // End country loop ?> 
                </tbody> 
            </table> 
        </div> 
    </div> 
</div> 
<?php // Close HTML structures and load scripts
require_once __DIR__ . '/inc/footer.inc.php'; // Include footer template file
?>
