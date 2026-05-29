<?php // Start PHP sidebar script
$current_page = basename($_SERVER['PHP_SELF']); // Get the current active file name
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
                    <i class="align-middle me-2" data-feather="list"></i> <span class="align-middle">Listado</span> 
                </a> 
            </li> 
            <li class="sidebar-item <?php echo ($current_page == 'carga_proyecto.php') ? 'active' : ''; ?>"> 
                <a class="sidebar-link" href="carga_proyecto.php"> 
                    <i class="align-middle me-2" data-feather="file"></i><span class="align-middle">Cargar nuevo</span> 
                </a> 
            </li> 
            <?php if ($_SESSION['Usuario_Nivel'] == 1) { // Render Personal header and user listing only for Admin (Level 1) ?> 
            <li class="sidebar-header"> 
                Personal 
            </li> 
            <li class="sidebar-item <?php echo ($current_page == 'listado_usuarios.php') ? 'active' : ''; ?>"> 
                <a class="sidebar-link" href="listado_usuarios.php"> 
                    <i class="align-middle me-2" data-feather="user"></i><span class="align-middle">Listado de usuarios</span> 
                </a> 
            </li> 
            <?php } // End admin check for Personal ?> 
            <li class="sidebar-header"> 
                Empresas 
            </li> 
            <li class="sidebar-item <?php echo ($current_page == 'listado_empresas.php') ? 'active' : ''; ?>"> 
                <a class="sidebar-link" href="listado_empresas.php"> 
                    <i class="align-middle me-2" data-feather="award"></i> <span class="align-middle">Listado</span> 
                </a> 
            </li> 
            <?php if ($_SESSION['Usuario_Nivel'] == 1) { // Render only if logged user is an Administrator (Level 1) ?> 
            <li class="sidebar-item <?php echo ($current_page == 'carga_empresa.php') ? 'active' : ''; ?>"> 
                <a class="sidebar-link" href="carga_empresa.php"> 
                    <i class="align-middle me-2" data-feather="file"></i><span class="align-middle">Cargar nueva</span> 
                </a> 
            </li> 
            <?php } // End admin menu items block ?> 
            <?php if ($_SESSION['Usuario_Nivel'] == 1) { // Render countries list link only for Admin (Level 1) ?> 
            <li class="sidebar-item <?php echo ($current_page == 'listado_paises.php') ? 'active' : ''; ?>"> 
                <a class="sidebar-link" href="listado_paises.php"> 
                    <i class="align-middle me-2" data-feather="map-pin"></i><span class="align-middle">Listado de paises</span> 
                </a> 
            </li> 
            <?php } // End countries admin check ?> 
        </ul> 
    </div> 
</nav> 
