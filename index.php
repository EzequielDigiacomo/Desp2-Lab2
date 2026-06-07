<?php 
require_once 'inc/header.inc.php'; 

$SQL_Total = "SELECT COUNT(*) as Total FROM proyectos WHERE Eliminado = 0"; 
$ResultadoTotal = mysqli_query($vConexion, $SQL_Total); 
$data_Total = mysqli_fetch_array($ResultadoTotal); 
$total_proyectos = $data_Total['Total']; 

$SQL_Paises = "SELECT P.Denominacion as Pais, COUNT(PR.Id) as Cantidad " . 
              "FROM proyectos PR " . 
              "JOIN empresas E ON PR.IdEmpresa = E.Id " . 
              "JOIN paises P ON E.IdPais = P.Id " . 
              "WHERE PR.Eliminado = 0 " . 
              "GROUP BY P.Denominacion " . 
              "ORDER BY Cantidad DESC"; 
$ResultadoPaises = mysqli_query($vConexion, $SQL_Paises); 
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
                        <h1 class="mt-1 mb-3"><?php echo $total_proyectos; ?></h1> 
                        <?php 
                        while ($row_pais = mysqli_fetch_array($ResultadoPaises)) { 
                        ?> 
                        <div class="mb-1"> 
                            <span class="badge bg-success me-2"><?php echo $row_pais['Cantidad']; ?></span> 
                            <span class="text-muted"><?php echo $row_pais['Pais']; ?></span> 
                        </div> 
                        <?php } ?> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</div> 
<?php 
require_once 'inc/footer.inc.php'; 
?>
