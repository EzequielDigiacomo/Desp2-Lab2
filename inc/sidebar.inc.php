<?php // Inicia el script PHP del panel lateral de navegación
$current_page = basename($_SERVER['PHP_SELF']); // Obtiene el nombre del archivo activo actual // CAMBIAR ESTA VARIABLE
?>
<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="index.php">
            <span class="align-middle">AdminKit</span>
        </a>
        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Proyectos
            </li>
            <li class="sidebar-item <?php echo ($current_page == 'listado_proyectos.php') ? 'active' : ''; ?>">
                <a class="sidebar-link" href="listado_proyectos.php">
                    <i class="align-middle me-2" data-feather="list"></i> <span class="align-middle">Listado de
                        proyectos</span>
                </a>
            </li>
            <li class="sidebar-item <?php echo ($current_page == 'carga_proyecto.php') ? 'active' : ''; ?>">
                <a class="sidebar-link" href="carga_proyecto.php">
                    <i class="align-middle me-2" data-feather="file"></i><span class="align-middle">Cargar nuevo
                        proyecto</span>
                </a>
            </li>
            <?php if ($_SESSION['Usuario_Nivel'] == 1) { // Renderiza la sección Personal y listado de usuarios solo para el Administrador (Nivel 1) ?>
                <li class="sidebar-header">
                    Personal
                </li>
                <li class="sidebar-item <?php echo ($current_page == 'listado_usuarios.php') ? 'active' : ''; ?>">
                    <a class="sidebar-link" href="listado_usuarios.php">
                        <i class="align-middle me-2" data-feather="user"></i><span class="align-middle">Listado de
                            usuarios</span>
                    </a>
                </li>
            <?php } // Fin de la verificación de Administrador para la sección Personal ?>
            <li class="sidebar-header">
                Empresas
            </li>
            <li class="sidebar-item <?php echo ($current_page == 'listado_empresas.php') ? 'active' : ''; ?>">
                <a class="sidebar-link" href="listado_empresas.php">
                    <i class="align-middle me-2" data-feather="award"></i> <span class="align-middle">Listado de
                        empresa</span>
                </a>
            </li>
            <?php if ($_SESSION['Usuario_Nivel'] == 1) { // Renderiza solo si el usuario autenticado es un Administrador (Nivel 1) ?>
                <li class="sidebar-item <?php echo ($current_page == 'carga_empresa.php') ? 'active' : ''; ?>">
                    <a class="sidebar-link" href="carga_empresa.php">
                        <i class="align-middle me-2" data-feather="file"></i><span class="align-middle">Cargar nueva
                            empresa</span>
                    </a>
                </li>
                <li class="sidebar-header">
                    Paises
                </li>
            <?php } // Fin del bloque de elementos del menú de Administrador ?>
            <?php if ($_SESSION['Usuario_Nivel'] == 1) { // Renderiza el enlace del listado de países solo para el Administrador (Nivel 1) ?>
                <li class="sidebar-item <?php echo ($current_page == 'listado_paises.php') ? 'active' : ''; ?>">
                    <a class="sidebar-link" href="listado_paises.php">
                        <i class="align-middle me-2" data-feather="map-pin"></i><span class="align-middle">Listado de
                            paises</span>
                    </a>
                </li>
            <?php } // Fin de la verificación del Administrador para países ?>
        </ul>
    </div>
</nav>