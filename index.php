<?php // Start PHP script for dashboard homepage
require_once __DIR__ . '/inc/header.inc.php'; // Include header layout with security guards and connection

// Fetch total number of projects in database
$SQL_Total = "SELECT COUNT(*) as Total FROM proyectos WHERE Eliminado = 0"; // SQL query to count active projects
$rs_Total = mysqli_query($vConexion, $SQL_Total); // Execute total count query
$data_Total = mysqli_fetch_array($rs_Total); // Fetch result row into array
$total_proyectos = $data_Total['Total']; // Store total count in variable

// Fetch project count grouped by country
$SQL_Paises = "SELECT P.Denominacion as Pais, COUNT(PR.Id) as Cantidad " . // SELECT country and aggregate project count
              "FROM proyectos PR " . // FROM projects table
              "JOIN empresas E ON PR.IdEmpresa = E.Id " . // JOIN companies to link project to client
              "JOIN paises P ON E.IdPais = P.Id " . // JOIN countries through company reference
              "WHERE PR.Eliminado = 0 " . // Exclude deleted projects
              "GROUP BY P.Denominacion " . // Group results by country name
              "ORDER BY Cantidad DESC"; // Sort by count in descending order
$rs_Paises = mysqli_query($vConexion, $SQL_Paises); // Execute country query on connection
?>
<h1 class="h3 mb-3">Has ingresado al panel de administración.</h1> 
<div class="row"> 
    <div class="col-12 col-md-6 col-lg-4"> 
        <div class="card"> 
            <div class="card-header pb-0"> 
                <h5 class="card-title mb-0">Elige tu opción desde el menú.</h5> 
            </div> 
            <div class="card-body"> 
                <div class="card shadow-sm border mb-0"> 
                    <div class="card-body"> 
                        <div class="row"> 
                            <div class="col mt-0"> 
                                <h5 class="card-title">Proyectos Activos</h5> 
                            </div> 
                            <div class="col-auto"> 
                                <div class="stat text-primary"> 
                                    <i data-feather="briefcase" class="align-middle"></i> 
                                </div> 
                            </div> 
                        </div> 
                        <h1 class="mt-1 mb-3"><?php echo $total_proyectos; // Output total active projects ?></h1> 
                        <?php // Loop through countries to display project distribution
                        while ($row_pais = mysqli_fetch_array($rs_Paises)) { // Retrieve next country row
                        ?> 
                        <div class="mb-1"> 
                            <span class="badge bg-success me-2"><?php echo $row_pais['Cantidad']; // Output project count for country ?></span> 
                            <span class="text-muted"><?php echo $row_pais['Pais']; // Output country name ?></span> 
                        </div> 
                        <?php } // End countries count display loop ?> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</div> 
<?php // End page body content
require_once __DIR__ . '/inc/footer.inc.php'; // Include footer template and close tags
?>
