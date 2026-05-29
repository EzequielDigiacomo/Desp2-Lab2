<?php // Start PHP shared functions library
// Function to authenticate a user using password hash verification
function DatosLogin_Hash($vUsuario, $vClave, $vConexion) { // Function declaration with parameters
    $Usuario = array(); // Initialize empty user details array
    $vUsuario = mysqli_real_escape_string($vConexion, $vUsuario); // Sanitize user input for safety
    $SQL = "SELECT U.IdUsuario, U.Nombre, U.Apellido, U.IdRol, U.Imagen, U.Clave, U.Activo, R.Denominacion as NombreRol " . // Select user fields and join with roles
           "FROM usuarios U JOIN roles R ON U.IdRol = R.Id " . // Join statement linking users to roles
           "WHERE U.Usuario = '$vUsuario' AND U.Eliminado = 0"; // Filter statement using sanitized username
    $rs = mysqli_query($vConexion, $SQL); // Execute query on connection
    if ($rs && $data = mysqli_fetch_array($rs)) { // If query succeeds and returns row
        if (password_verify($vClave, $data['Clave'])) { // Verify bcrypt password match
            $Usuario['ID'] = $data['IdUsuario']; // Save user ID in array
            $Usuario['NOMBRE'] = $data['Nombre']; // Save user first name
            $Usuario['APELLIDO'] = $data['Apellido']; // Save user last name
            $Usuario['NIVEL_ID'] = $data['IdRol']; // Save user role ID as level ID
            $Usuario['NIVEL_NOMBRE'] = $data['NombreRol']; // Save user role name
            $Usuario['IMG'] = empty($data['Imagen']) ? 'login.png' : $data['Imagen']; // Set image or fallback icon
            $Usuario['ACTIVO'] = $data['Activo']; // Save active status flag
        } // End password verification
    } // End row retrieval check
    return $Usuario; // Return authenticated user array
} // End DatosLogin_Hash function

// Function to retrieve all countries for list and dropdowns
function Listar_Paises($vConexion) { // Function declaration
    $Listado = array(); // Initialize empty countries list
    $SQL = "SELECT * FROM paises WHERE Eliminado = 0 ORDER BY Denominacion"; // Query to select non-deleted countries alphabetically
    $rs = mysqli_query($vConexion, $SQL); // Execute query on connection
    $i = 0; // Initialize loop counter
    while ($data = mysqli_fetch_array($rs)) { // Iterate over query results
        $Listado[$i]['ID'] = $data['Id']; // Save country ID
        $Listado[$i]['NOMBRE'] = $data['Denominacion']; // Save country name
        $i++; // Increment loop counter
    } // End loop
    return $Listado; // Return list of countries
} // End Listar_Paises function

// Function to retrieve all roles
function Listar_Roles($vConexion) { // Function declaration
    $Listado = array(); // Initialize empty roles list
    $SQL = "SELECT * FROM roles ORDER BY Denominacion"; // Query to select all roles alphabetically
    $rs = mysqli_query($vConexion, $SQL); // Execute query
    $i = 0; // Initialize counter
    while ($data = mysqli_fetch_array($rs)) { // Fetch rows sequentially
        $Listado[$i]['ID'] = $data['Id']; // Save role ID
        $Listado[$i]['NOMBRE'] = $data['Denominacion']; // Save role name
        $i++; // Increment counter
    } // End loop
    return $Listado; // Return roles list
} // End Listar_Roles function

// Function to list all non-deleted users
function Listar_Usuarios($vConexion) { // Function declaration
    $Listado = array(); // Initialize empty users list
    $SQL = "SELECT U.IdUsuario, U.Nombre, U.Apellido, U.Usuario, U.Imagen, R.Denominacion as Rol " . // Query fields
           "FROM usuarios U JOIN roles R ON U.IdRol = R.Id " . // Join with roles table
           "WHERE U.Eliminado = 0 " . // Exclude logically deleted users
           "ORDER BY U.Apellido, U.Nombre"; // Sort by last name then first name
    $rs = mysqli_query($vConexion, $SQL); // Execute query
    $i = 0; // Initialize counter
    while ($data = mysqli_fetch_array($rs)) { // Loop over fetched users
        $Listado[$i]['ID'] = $data['IdUsuario']; // Save user ID
        $Listado[$i]['NOMBRE'] = $data['Nombre']; // Save first name
        $Listado[$i]['APELLIDO'] = $data['Apellido']; // Save last name
        $Listado[$i]['USUARIO'] = $data['Usuario']; // Save username
        $Listado[$i]['ROL'] = $data['Rol']; // Save role designation
        $Listado[$i]['IMG'] = empty($data['Imagen']) ? 'login.png' : $data['Imagen']; // Save image path
        $i++; // Increment counter
    } // End loop
    return $Listado; // Return users list
} // End Listar_Usuarios function

// Function to list all non-deleted companies
function Listar_Empresas($vConexion) { // Function declaration
    $Listado = array(); // Initialize empty list
    $SQL = "SELECT E.Id, E.Denominacion, E.FechaCarga, E.UsuarioCarga, P.Denominacion as Pais, " . // SELECT fields
           "U.Nombre as U_Nombre, U.Apellido as U_Apellido, U.Imagen as U_Imagen " . // SELECT creator fields
           "FROM empresas E " . // FROM companies table
           "JOIN paises P ON E.IdPais = P.Id " . // JOIN countries table
           "LEFT JOIN usuarios U ON E.UsuarioCarga = U.Usuario " . // JOIN users table on username
           "WHERE E.Eliminado = 0 " . // Exclude deleted companies
           "ORDER BY E.Denominacion ASC"; // Order alphabetically by denomination
    $rs = mysqli_query($vConexion, $SQL); // Execute database query
    $i = 0; // Initialize iterator
    while ($data = mysqli_fetch_array($rs)) { // Loop over results
        $Listado[$i]['ID'] = $data['Id']; // Save company ID
        $Listado[$i]['DENOMINACION'] = $data['Denominacion']; // Save company name
        $Listado[$i]['FECHA_CARGA'] = $data['FechaCarga']; // Save creation datetime
        $Listado[$i]['USUARIO_CARGA'] = $data['UsuarioCarga']; // Save creator username
        $Listado[$i]['PAIS'] = $data['Pais']; // Save country name
        $Listado[$i]['CREADOR_COMPLETO'] = !empty($data['U_Nombre']) ? $data['U_Apellido'] . ' ' . $data['U_Nombre'] : $data['UsuarioCarga']; // Composite loaded by name
        $Listado[$i]['CREADOR_IMG'] = !empty($data['U_Imagen']) ? $data['U_Imagen'] : 'login.png'; // Creator avatar
        $i++; // Increment iterator
    } // End loop
    return $Listado; // Return companies list
} // End Listar_Empresas function

// Function to list all non-deleted projects
function Listar_Proyectos($vConexion) { // Function declaration
    $Listado = array(); // Initialize empty projects array
    $SQL = "SELECT PR.Id, PR.Denominacion, PR.FechaCarga, PR.Prioridad, " . // SELECT project metadata
           "E.Denominacion as Empresa, P.Denominacion as Pais, " . // SELECT associated company and country
           "U.Nombre as L_Nombre, U.Apellido as L_Apellido, U.Imagen as L_Imagen, " . // SELECT project leader fields
           "ES.Id as IdEstado, ES.Denominacion as Estado " . // SELECT state details
           "FROM proyectos PR " . // FROM projects table
           "JOIN empresas E ON PR.IdEmpresa = E.Id " . // JOIN companies
           "JOIN paises P ON E.IdPais = P.Id " . // JOIN countries through companies
           "JOIN usuarios U ON PR.IdLider = U.IdUsuario " . // JOIN leader user
           "JOIN estados ES ON PR.IdEstado = ES.Id " . // JOIN project states
           "WHERE PR.Eliminado = 0 " . // Exclude logically deleted projects
           "ORDER BY PR.FechaCarga ASC"; // Sort oldest projects first as required
    $rs = mysqli_query($vConexion, $SQL); // Execute query
    $i = 0; // Initialize counter
    while ($data = mysqli_fetch_array($rs)) { // Loop over projects
        $Listado[$i]['ID'] = $data['Id']; // Save project ID
        $Listado[$i]['DENOMINACION'] = $data['Denominacion']; // Save project title
        $Listado[$i]['FECHA_CARGA'] = $data['FechaCarga']; // Save creation date
        $Listado[$i]['PRIORIDAD'] = $data['Prioridad']; // Save priority boolean
        $Listado[$i]['EMPRESA'] = $data['Empresa']; // Save client company name
        $Listado[$i]['PAIS'] = $data['Pais']; // Save client country name
        $Listado[$i]['LIDER_COMPLETO'] = $data['L_Nombre'] . ' ' . $data['L_Apellido']; // Save composite leader name
        $Listado[$i]['LIDER_IMG'] = empty($data['L_Imagen']) ? 'login.png' : $data['L_Imagen']; // Save leader avatar
        $Listado[$i]['ESTADO_ID'] = $data['IdEstado']; // Save state ID
        $Listado[$i]['ESTADO_NOMBRE'] = $data['Estado']; // Save state status text
        $i++; // Increment counter
    } // End loop
    return $Listado; // Return projects list
} // End Listar_Proyectos function

// Function to retrieve all project leaders (Admins and Lideres)
function Listar_Lideres($vConexion) { // Function declaration
    $Listado = array(); // Initialize leaders array
    $SQL = "SELECT IdUsuario, Nombre, Apellido FROM usuarios " . // Query fields
           "WHERE IdRol IN (1, 2) AND Eliminado = 0 " . // Filter by Admin (1) or Lider (2) roles
           "ORDER BY Apellido, Nombre"; // Order alphabetically by last name then first name
    $rs = mysqli_query($vConexion, $SQL); // Execute query
    $i = 0; // Initialize loop counter
    while ($data = mysqli_fetch_array($rs)) { // Loop over fetched leaders
        $Listado[$i]['ID'] = $data['IdUsuario']; // Save leader ID
        $Listado[$i]['NOMBRE_COMPLETO'] = $data['Apellido'] . ', ' . $data['Nombre']; // Format composite name for select dropdowns
        $i++; // Increment loop counter
    } // End loop
    return $Listado; // Return leaders array
} // End Listar_Lideres function

// Function to insert a new company record with validation sanitization
function Insertar_Empresa($vConexion, $Denominacion, $IdPais, $Observaciones, $UsuarioCarga) { // Function declaration
    $Denominacion = mysqli_real_escape_string($vConexion, trim(strip_tags($Denominacion))); // Clean denomination
    $IdPais = intval($IdPais); // Force conversion to integer
    $Observaciones = mysqli_real_escape_string($vConexion, trim(strip_tags($Observaciones))); // Clean observations text
    $UsuarioCarga = mysqli_real_escape_string($vConexion, trim(strip_tags($UsuarioCarga))); // Clean charging username
    $SQL = "INSERT INTO empresas (Denominacion, IdPais, Observaciones, FechaCarga, UsuarioCarga, Eliminado) " . // Query declaration
           "VALUES ('$Denominacion', $IdPais, '$Observaciones', NOW(), '$UsuarioCarga', 0)"; // Values definition
    if (mysqli_query($vConexion, $SQL)) { // Execute insert query
        return true; // Return true on success
    } else { // Handle query failure
        return false; // Return false on error
    } // End query check
} // End Insertar_Empresa function

// Function to insert a new project record
function Insertar_Proyecto($vConexion, $Denominacion, $IdEmpresa, $IdLider, $Observaciones, $Prioridad, $UsuarioCarga) { // Function declaration
    $Denominacion = mysqli_real_escape_string($vConexion, trim(strip_tags($Denominacion))); // Clean project name
    $IdEmpresa = intval($IdEmpresa); // Clean company ID
    $IdLider = intval($IdLider); // Clean leader ID
    $Observaciones = mysqli_real_escape_string($vConexion, trim(strip_tags($Observaciones))); // Clean observations
    $Prioridad = intval($Prioridad) > 0 ? 1 : 0; // Enforce priority boolean value (0 or 1)
    $UsuarioCarga = mysqli_real_escape_string($vConexion, trim(strip_tags($UsuarioCarga))); // Clean creator username
    $SQL = "INSERT INTO proyectos (Denominacion, IdEmpresa, IdLider, Observaciones, Prioridad, IdEstado, FechaCarga, UsuarioCarga, Eliminado) " . // INSERT structure
           "VALUES ('$Denominacion', $IdEmpresa, $IdLider, '$Observaciones', $Prioridad, 1, NOW(), '$UsuarioCarga', 0)"; // Default state is Analisis Iniciado (1)
    if (mysqli_query($vConexion, $SQL)) { // Execute insert query
        return true; // Return true on success
    } else { // Handle query failure
        return false; // Return false on error
    } // End query check
} // End Insertar_Proyecto function

// Function to cancel an existing project (updates state ID to 4)
function Cancelar_Proyecto($vConexion, $IdProyecto) { // Function declaration
    $IdProyecto = intval($IdProyecto); // Enforce integer ID validation
    $SQL = "UPDATE proyectos SET IdEstado = 4 WHERE Id = $IdProyecto"; // SQL command to change state to Cancelado (4)
    if (mysqli_query($vConexion, $SQL)) { // Execute update query
        return true; // Return true on success
    } else { // Handle database error
        return false; // Return false on error
    } // End execution check
} // End Cancelar_Proyecto function

// Generic logical deletion function
function BorrarLogico($vConexion, $Tabla, $Id) { // Function declaration with connection, table name, and ID parameters
    $Tabla = mysqli_real_escape_string($vConexion, trim(strip_tags($Tabla))); // Clean table name to protect query structure
    $Id = intval($Id); // Enforce numeric primary key structure
    $SQL = "UPDATE `$Tabla` SET Eliminado = 1 WHERE Id = $Id"; // Logical delete query (sets Eliminado flag to 1)
    if ($Tabla == 'usuarios') { // Specific handle for users table (PK is IdUsuario)
        $SQL = "UPDATE `usuarios` SET Eliminado = 1, Activo = 0 WHERE IdUsuario = $Id"; // Deactivate and logically delete user
    } // End table check
    if (mysqli_query($vConexion, $SQL)) { // Execute update query
        return true; // Return true on success
    } else { // Handle database failure
        return false; // Return false on query error
    } // End query verification
} // End BorrarLogico function

/*
========================================================================================
FUNCTION: BorrarFisico (COMMENTED OUT AS REQUESTED)
========================================================================================
This function is intentionally commented out to align with software engineering best
practices regarding data retention, logical deletion audit trails, and strict guidelines.

ADVANTAGES OF PHYSICAL DELETION (DELETE FROM table WHERE id = X):
- Reclaims Storage Space: Permanently frees database space and filesystem allocation.
- Query Performance: Smaller tables are faster to scan and index, maintaining speed.
- Simpler Queries: No need to append 'WHERE Eliminado = 0' clauses to listing queries.

DISADVANTAGES OF PHYSICAL DELETION:
- Destructive Action: Deleted data is completely unrecoverable without restoring backups.
- Foreign Key Violation: Deleting parent rows breaks links or triggers cascade deletes.
- Audit Trails Lost: Regulatory history and logging of user/action records are wiped out.

ADVANTAGES OF LOGICAL DELETION (UPDATE table SET Eliminado = 1 WHERE id = X):
- Non-destructive: Soft-deleted records can easily be restored or queried for auditing.
- Referential Integrity: Keeps FK constraints happy by preserving table row existence.
- Safety Net: Safeguards against accidental user clicks or administrative mistakes.

DISADVANTAGES OF LOGICAL DELETION:
- Larger Database Size: Data remains stored permanently, increasing backup sizes over time.
- Query Overhead: Listing scripts must explicitly filter out rows with `Eliminado = 0`.
========================================================================================

function BorrarFisico($vConexion, $Tabla, $Id) { // Start of commented out code
    $Tabla = mysqli_real_escape_string($vConexion, trim(strip_tags($Tabla))); // Clean table name
    $Id = intval($Id); // Force primary key to numeric integer
    $PK = 'Id'; // Default primary key field name
    if ($Tabla == 'usuarios') { // Check for users table exception
        $PK = 'IdUsuario'; // Correct PK field name for users
    } // End PK field assignment
    $SQL = "DELETE FROM `$Tabla` WHERE `$PK` = $Id"; // Destructive physical delete query
    if (mysqli_query($vConexion, $SQL)) { // Execute SQL query on connection
        return true; // Return success boolean status
    } else { // Handle execute failure
        return false; // Return fail status
    } // End connection check
} // End function
*/
?>
