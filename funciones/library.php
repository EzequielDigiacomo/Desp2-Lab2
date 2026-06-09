<?php 

function DatosLogin_Hash($vUsuario, $vClave, $vConexion)
{ 
    $Usuario = array(); 
    $SQL = "SELECT U.IdUsuario, U.Nombre, U.Apellido, U.IdRol, U.Imagen, U.Clave, U.Activo, R.Denominacion as NombreRol 
            FROM usuarios U 
            JOIN roles R ON U.IdRol = R.Id 
            WHERE U.Usuario = '$vUsuario' AND U.Eliminado = 0";
    $Resultado = mysqli_query($vConexion, $SQL); 
    if ($Resultado && $data = mysqli_fetch_array($Resultado)) { 
        if (password_verify($vClave, $data['Clave'])) { 
            $Usuario['ID'] = $data['IdUsuario']; 
            $Usuario['NOMBRE'] = $data['Nombre']; 
            $Usuario['APELLIDO'] = $data['Apellido']; 
            $Usuario['ID_ROL'] = $data['IdRol']; 
            $Usuario['NOMBRE_ROL'] = $data['NombreRol']; 
            $Usuario['IMG'] = empty($data['Imagen']) ? 'login.png' : $data['Imagen']; 
            $Usuario['ACTIVO'] = $data['Activo']; 
        } 
    } 
    return $Usuario; 
} 

function Listar_Paises($vConexion)
{ 
    $Listado = array(); 
    $SQL = "SELECT * FROM paises WHERE Eliminado = 0 ORDER BY Denominacion"; 
    $Resultado = mysqli_query($vConexion, $SQL); 
    $i = 0; 
    while ($data = mysqli_fetch_array($Resultado)) { 
        $Listado[$i]['ID'] = $data['Id']; 
        $Listado[$i]['NOMBRE'] = $data['Denominacion']; 
        $i++; 
    } 
    return $Listado; 
} 

function Listar_Roles($vConexion)
{ 
    $Listado = array(); 
    $SQL = "SELECT * FROM roles ORDER BY Denominacion"; 
    $Resultado = mysqli_query($vConexion, $SQL); 
    $i = 0; 
    while ($data = mysqli_fetch_array($Resultado)) { 
        $Listado[$i]['ID'] = $data['Id']; 
        $Listado[$i]['NOMBRE'] = $data['Denominacion']; 
        $i++; 
    } 
    return $Listado; 
} 

function Listar_Usuarios($vConexion)
{ 
    $Listado = array(); 
    $SQL = "SELECT U.IdUsuario, U.Nombre, U.Apellido, U.Usuario, U.Imagen, R.Denominacion as Rol 
            FROM usuarios U 
            JOIN roles R ON U.IdRol = R.Id 
            WHERE U.Eliminado = 0 
            ORDER BY U.Apellido, U.Nombre";
    $Resultado = mysqli_query($vConexion, $SQL); 
    $i = 0; 
    while ($data = mysqli_fetch_array($Resultado)) { 
        $Listado[$i]['ID'] = $data['IdUsuario']; 
        $Listado[$i]['NOMBRE'] = $data['Nombre']; 
        $Listado[$i]['APELLIDO'] = $data['Apellido']; 
        $Listado[$i]['USUARIO'] = $data['Usuario']; 
        $Listado[$i]['ROL'] = $data['Rol']; 
        $Listado[$i]['IMG'] = empty($data['Imagen']) ? 'login.png' : $data['Imagen']; 
        $i++; 
    } 
    return $Listado; 
} 

function Listar_Empresas($vConexion)
{ 
    $Listado = array(); 
    $SQL = "SELECT E.Id, E.Denominacion, E.FechaCarga, E.UsuarioCarga, P.Denominacion as Pais, U.Nombre, U.Apellido, U.Imagen 
            FROM empresas E 
            JOIN paises P ON E.IdPais = P.Id 
            JOIN usuarios U ON E.UsuarioCarga = U.Usuario 
            WHERE E.Eliminado = 0 
            ORDER BY E.Denominacion ASC";
    $Resultado = mysqli_query($vConexion, $SQL); 
    $i = 0; 
    while ($data = mysqli_fetch_array($Resultado)) { 
        $Listado[$i]['ID'] = $data['Id']; 
        $Listado[$i]['DENOMINACION'] = $data['Denominacion']; 
        $Listado[$i]['FECHA_CARGA'] = $data['FechaCarga']; 
        $Listado[$i]['USUARIO_CARGA'] = $data['UsuarioCarga']; 
        $Listado[$i]['PAIS'] = $data['Pais']; 
        $Listado[$i]['CREADOR_COMPLETO'] = $data['Nombre'] . ' ' . $data['Apellido']; 
        $Listado[$i]['CREADOR_IMG'] = empty($data['Imagen']) ? 'login.png' : $data['Imagen']; 
        $i++; 
    } 
    return $Listado; 
} 

function Listar_Proyectos($vConexion)
{ 
    $Listado = array(); 
    $SQL = "SELECT PR.Id, PR.Denominacion, PR.FechaCarga, PR.Prioridad, E.Denominacion as Empresa, P.Denominacion as Pais, U.Nombre, U.Apellido, U.Imagen, ES.Id as IdEstado, ES.Denominacion as Estado 
            FROM proyectos PR 
            JOIN empresas E ON PR.IdEmpresa = E.Id 
            JOIN paises P ON E.IdPais = P.Id 
            JOIN usuarios U ON PR.IdLider = U.IdUsuario 
            JOIN estados ES ON PR.IdEstado = ES.Id 
            WHERE PR.Eliminado = 0 
            ORDER BY PR.FechaCarga ASC";
    $Resultado = mysqli_query($vConexion, $SQL); 
    $i = 0; 
    while ($data = mysqli_fetch_array($Resultado)) { 
        $Listado[$i]['ID'] = $data['Id']; 
        $Listado[$i]['DENOMINACION'] = $data['Denominacion']; 
        $Listado[$i]['FECHA_CARGA'] = $data['FechaCarga']; 
        $Listado[$i]['PRIORIDAD'] = $data['Prioridad']; 
        $Listado[$i]['EMPRESA'] = $data['Empresa']; 
        $Listado[$i]['PAIS'] = $data['Pais']; 
        $Listado[$i]['LIDER_COMPLETO'] = $data['Nombre'] . ' ' . $data['Apellido']; 
        $Listado[$i]['LIDER_IMG'] = empty($data['Imagen']) ? 'login.png' : $data['Imagen'];  //si imagen esta vacio entonces pone login.png
        $Listado[$i]['ESTADO_ID'] = $data['IdEstado']; 
        $Listado[$i]['ESTADO_NOMBRE'] = $data['Estado']; 
        $i++; 
    } 
    return $Listado; 
} 

function Listar_Lideres($vConexion)
{ 
    $Listado = array(); 
    $SQL = "SELECT IdUsuario, Nombre, Apellido 
            FROM usuarios 
            WHERE IdRol = 2 AND Eliminado = 0 
            ORDER BY Apellido, Nombre";
    $Resultado = mysqli_query($vConexion, $SQL); 
    $i = 0; 
    while ($data = mysqli_fetch_array($Resultado)) { 
        $Listado[$i]['ID'] = $data['IdUsuario']; 
        $Listado[$i]['NOMBRE_COMPLETO'] = $data['Apellido'] . ', ' . $data['Nombre']; 
        $i++; 
    } 
    return $Listado; 
} 

function Insertar_Empresa($vConexion, $Denominacion, $IdPais, $Observaciones, $UsuarioCarga)
{ 
    $SQL = "INSERT INTO empresas (Denominacion, IdPais, Observaciones, FechaCarga, UsuarioCarga, Eliminado) 
            VALUES ('$Denominacion', '$IdPais', '$Observaciones', NOW(), '$UsuarioCarga', 0)";
    if (mysqli_query($vConexion, $SQL)) { 
        return true; 
    } else { 
        return false; 
    } 
} 

function Insertar_Proyecto($vConexion, $Denominacion, $IdEmpresa, $IdLider, $Observaciones, $Prioridad, $UsuarioCarga)
{ 
    $SQL = "INSERT INTO proyectos (Denominacion, IdEmpresa, IdLider, Observaciones, Prioridad, IdEstado, FechaCarga, UsuarioCarga, Eliminado) 
            VALUES ('$Denominacion', '$IdEmpresa', '$IdLider', '$Observaciones', '$Prioridad', 1, NOW(), '$UsuarioCarga', 0)";
    if (mysqli_query($vConexion, $SQL)) { 
        return true; 
    } else { 
        return false; 
    } 
} 

function Cancelar_Proyecto($vConexion, $IdProyecto)
{ 
    $SQL = "UPDATE proyectos SET IdEstado = 4 WHERE Id = $IdProyecto"; 
    if (mysqli_query($vConexion, $SQL)) { 
        return true; 
    } else { 
        return false; 
    } 
} 

function Eliminar_Proyecto_Fisico($vConexion, $IdProyecto)
{ 
    // Consulta SQL para baja física: DELETE FROM proyectos WHERE Id = $IdProyecto
    $SQL = "DELETE FROM proyectos WHERE Id = $IdProyecto"; 
    if (mysqli_query($vConexion, $SQL)) { 
        return true; 
    } else { 
        return false; 
    } 
}

function Borrar_Usuario_Logico($vConexion, $Id)
{
    // Consulta SQL alternativa para baja física: DELETE FROM usuarios WHERE IdUsuario = $Id
    $SQL = "UPDATE `usuarios` SET Eliminado = 1, Activo = 0 WHERE IdUsuario = $Id";
    if (mysqli_query($vConexion, $SQL)) {
        return true;
    } else {
        return false;
    }
}

function Borrar_Empresa_Logico($vConexion, $Id)
{
    // Consulta SQL alternativa para baja física: DELETE FROM empresas WHERE Id = $Id
    $SQL = "UPDATE `empresas` SET Eliminado = 1 WHERE Id = $Id";
    if (mysqli_query($vConexion, $SQL)) {
        return true;
    } else {
        return false;
    }
}

function Borrar_Pais_Logico($vConexion, $Id)
{
    // Consulta SQL alternativa para baja física: DELETE FROM paises WHERE Id = $Id
    $SQL = "UPDATE `paises` SET Eliminado = 1 WHERE Id = $Id";
    if (mysqli_query($vConexion, $SQL)) {
        return true;
    } else {
        return false;
    }
}

function Existe_Usuario($vUsuario, $vConexion)
{
    $SQL = "SELECT IdUsuario FROM usuarios WHERE Usuario = '$vUsuario' AND Eliminado = 0";
    $Resultado = mysqli_query($vConexion, $SQL);
    if ($Resultado && mysqli_num_rows($Resultado) > 0) {
        return true;
    }
    return false;
}

function Insertar_Usuario_Hash($vConexion, $vNombre, $vApellido, $vUsuario, $vClave, $vIdRol)
{
    
    $vClaveHash = password_hash($vClave, PASSWORD_BCRYPT);

    
    $SQL = "INSERT INTO usuarios (Nombre, Apellido, Usuario, Clave, IdRol, Imagen, Activo, Eliminado) 
            VALUES ('$vNombre', '$vApellido', '$vUsuario', '$vClaveHash', '$vIdRol', 'login.png', 1, 0)";

    if (mysqli_query($vConexion, $SQL)) {
        return true;
    }
    return false;
}

?>