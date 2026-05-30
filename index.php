<?php // Inicia el script PHP para la página de inicio del panel de control
require_once __DIR__ . '/inc/header.inc.php'; // Incluye el diseño de cabecera con guardias de seguridad y conexión

// Obtiene el número total de proyectos activos en la base de datos
$SQL_Total = "SELECT COUNT(*) as Total FROM proyectos WHERE Eliminado = 0"; // Consulta SQL para contar proyectos activos
$rs_Total = mysqli_query($vConexion, $SQL_Total); // Ejecuta la consulta de conteo total
$data_Total = mysqli_fetch_array($rs_Total); // Obtiene la fila del resultado en un array
$total_proyectos = $data_Total['Total']; // Almacena el conteo total en una variable

// Obtiene la cantidad de proyectos agrupados por país
$SQL_Paises = "SELECT P.Denominacion as Pais, COUNT(PR.Id) as Cantidad " . // Selecciona el país y realiza el conteo agregado de proyectos
              "FROM proyectos PR " . // Desde la tabla proyectos
              "JOIN empresas E ON PR.IdEmpresa = E.Id " . // JOIN con empresas para vincular el proyecto con el cliente
              "JOIN paises P ON E.IdPais = P.Id " . // JOIN con países a través de la referencia de la empresa
              "WHERE PR.Eliminado = 0 " . // Excluye proyectos eliminados
              "GROUP BY P.Denominacion " . // Agrupa los resultados por el nombre del país
              "ORDER BY Cantidad DESC"; // Ordena por la cantidad en orden descendente
$rs_Paises = mysqli_query($vConexion, $SQL_Paises); // Ejecuta la consulta de países en la conexión
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
                        <h1 class="mt-1 mb-3"><?php echo $total_proyectos; // Muestra el total de proyectos activos ?></h1> 
                        <?php // Recorre los países para mostrar la distribución de proyectos
                        while ($row_pais = mysqli_fetch_array($rs_Paises)) { // Obtiene la siguiente fila de país
                        ?> 
                        <div class="mb-1"> 
                            <span class="badge bg-success me-2"><?php echo $row_pais['Cantidad']; // Muestra el conteo de proyectos para el país ?></span> 
                            <span class="text-muted"><?php echo $row_pais['Pais']; // Muestra el nombre del país ?></span> 
                        </div> 
                        <?php } // Fin del bucle de visualización del conteo de países ?> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</div> 
<?php // Fin del contenido del cuerpo de la página
require_once __DIR__ . '/inc/footer.inc.php'; // Incluye la plantilla de pie de página y cierra las etiquetas
?>
