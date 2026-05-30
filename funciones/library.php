<?php // Inicia la biblioteca de funciones compartidas de PHP
// Función para autenticar a un usuario mediante la verificación del hash de la contraseña
function DatosLogin_Hash($vUsuario, $vClave, $vConexion) { // Declaración de la función con parámetros
    $Usuario = array(); // Inicializa un array vacío para los detalles del usuario
    $vUsuario = mysqli_real_escape_string($vConexion, $vUsuario); // Sanitiza la entrada del usuario para mayor seguridad
    $SQL = "SELECT U.IdUsuario, U.Nombre, U.Apellido, U.IdRol, U.Imagen, U.Clave, U.Activo, R.Denominacion as NombreRol " . // Selecciona los campos del usuario y realiza un JOIN con roles
           "FROM usuarios U JOIN roles R ON U.IdRol = R.Id " . // Sentencia JOIN que vincula usuarios con roles
           "WHERE U.Usuario = '$vUsuario' AND U.Eliminado = 0"; // Cláusula WHERE usando el nombre de usuario sanitizado
    $rs = mysqli_query($vConexion, $SQL); // Ejecuta la consulta en la conexión
    if ($rs && $data = mysqli_fetch_array($rs)) { // Si la consulta tiene éxito y retorna una fila
        if (password_verify($vClave, $data['Clave'])) { // Verifica si la contraseña coincide usando bcrypt
            $Usuario['ID'] = $data['IdUsuario']; // Guarda el ID de usuario en el array
            $Usuario['NOMBRE'] = $data['Nombre']; // Guarda el nombre del usuario
            $Usuario['APELLIDO'] = $data['Apellido']; // Guarda el apellido del usuario
            $Usuario['NIVEL_ID'] = $data['IdRol']; // Guarda el ID de rol como ID de nivel
            $Usuario['NIVEL_NOMBRE'] = $data['NombreRol']; // Guarda el nombre del rol del usuario
            $Usuario['IMG'] = empty($data['Imagen']) ? 'login.png' : $data['Imagen']; // Establece la imagen o un icono por defecto
            $Usuario['ACTIVO'] = $data['Activo']; // Guarda el estado de activo del usuario
        } // Fin de la verificación de contraseña
    } // Fin del chequeo de obtención de fila
    return $Usuario; // Retorna el array del usuario autenticado
} // Fin de la función DatosLogin_Hash

// Función para obtener todos los países para listados y desplegables
function Listar_Paises($vConexion) { // Declaración de la función
    $Listado = array(); // Inicializa una lista de países vacía
    $SQL = "SELECT * FROM paises WHERE Eliminado = 0 ORDER BY Denominacion"; // Consulta para seleccionar países no eliminados alfabéticamente
    $rs = mysqli_query($vConexion, $SQL); // Ejecuta la consulta en la conexión
    $i = 0; // Inicializa el contador del bucle
    while ($data = mysqli_fetch_array($rs)) { // Itera sobre los resultados de la consulta
        $Listado[$i]['ID'] = $data['Id']; // Guarda el ID del país
        $Listado[$i]['NOMBRE'] = $data['Denominacion']; // Guarda el nombre del país
        $i++; // Incrementa el contador del bucle
    } // Fin del bucle
    return $Listado; // Retorna la lista de países
} // Fin de la función Listar_Paises

// Función para obtener todos los roles
function Listar_Roles($vConexion) { // Declaración de la función
    $Listado = array(); // Inicializa una lista de roles vacía
    $SQL = "SELECT * FROM roles ORDER BY Denominacion"; // Consulta para seleccionar todos los roles alfabéticamente
    $rs = mysqli_query($vConexion, $SQL); // Ejecuta la consulta
    $i = 0; // Inicializa el contador
    while ($data = mysqli_fetch_array($rs)) { // Obtiene las filas secuencialmente
        $Listado[$i]['ID'] = $data['Id']; // Guarda el ID de rol
        $Listado[$i]['NOMBRE'] = $data['Denominacion']; // Guarda el nombre de rol
        $i++; // Incrementa el contador
    } // Fin del bucle
    return $Listado; // Retorna la lista de roles
} // Fin de la función Listar_Roles

// Función para listar todos los usuarios no eliminados
function Listar_Usuarios($vConexion) { // Declaración de la función
    $Listado = array(); // Inicializa una lista de usuarios vacía
    $SQL = "SELECT U.IdUsuario, U.Nombre, U.Apellido, U.Usuario, U.Imagen, R.Denominacion as Rol " . // Campos de la consulta
           "FROM usuarios U JOIN roles R ON U.IdRol = R.Id " . // JOIN con la tabla de roles
           "WHERE U.Eliminado = 0 " . // Excluye usuarios eliminados lógicamente
           "ORDER BY U.Apellido, U.Nombre"; // Ordena por apellido y luego por nombre
    $rs = mysqli_query($vConexion, $SQL); // Ejecuta la consulta
    $i = 0; // Inicializa el contador
    while ($data = mysqli_fetch_array($rs)) { // Bucle sobre los usuarios obtenidos
        $Listado[$i]['ID'] = $data['IdUsuario']; // Guarda el ID del usuario
        $Listado[$i]['NOMBRE'] = $data['Nombre']; // Guarda el nombre
        $Listado[$i]['APELLIDO'] = $data['Apellido']; // Guarda el apellido
        $Listado[$i]['USUARIO'] = $data['Usuario']; // Guarda el nombre de usuario
        $Listado[$i]['ROL'] = $data['Rol']; // Guarda la denominación del rol
        $Listado[$i]['IMG'] = empty($data['Imagen']) ? 'login.png' : $data['Imagen']; // Guarda la ruta de la imagen
        $i++; // Incrementa el contador
    } // Fin del bucle
    return $Listado; // Retorna la lista de usuarios
} // Fin de la función Listar_Usuarios

// Función para listar todas las empresas no eliminadas
function Listar_Empresas($vConexion) { // Declaración de la función
    $Listado = array(); // Inicializa una lista vacía
    $SQL = "SELECT E.Id, E.Denominacion, E.FechaCarga, E.UsuarioCarga, P.Denominacion as Pais, " . // Campos de SELECT
           "U.Nombre as U_Nombre, U.Apellido as U_Apellido, U.Imagen as U_Imagen " . // Campos del creador
           "FROM empresas E " . // Desde la tabla empresas
           "JOIN paises P ON E.IdPais = P.Id " . // JOIN con la tabla de países
           "LEFT JOIN usuarios U ON E.UsuarioCarga = U.Usuario " . // JOIN con la tabla de usuarios en el nombre de usuario
           "WHERE E.Eliminado = 0 " . // Excluye empresas eliminadas
           "ORDER BY E.Denominacion ASC"; // Ordena alfabéticamente por denominación
    $rs = mysqli_query($vConexion, $SQL); // Ejecuta la consulta en la base de datos
    $i = 0; // Inicializa el iterador
    while ($data = mysqli_fetch_array($rs)) { // Bucle sobre los resultados
        $Listado[$i]['ID'] = $data['Id']; // Guarda el ID de la empresa
        $Listado[$i]['DENOMINACION'] = $data['Denominacion']; // Guarda el nombre de la empresa
        $Listado[$i]['FECHA_CARGA'] = $data['FechaCarga']; // Guarda la fecha y hora de carga
        $Listado[$i]['USUARIO_CARGA'] = $data['UsuarioCarga']; // Guarda el nombre de usuario del creador
        $Listado[$i]['PAIS'] = $data['Pais']; // Guarda el nombre del país
        $Listado[$i]['CREADOR_COMPLETO'] = !empty($data['U_Nombre']) ? $data['U_Apellido'] . ' ' . $data['U_Nombre'] : $data['UsuarioCarga']; // Nombre completo compuesto del cargador
        $Listado[$i]['CREADOR_IMG'] = !empty($data['U_Imagen']) ? $data['U_Imagen'] : 'login.png'; // Avatar del creador
        $i++; // Incrementa el iterador
    } // Fin del bucle
    return $Listado; // Retorna la lista de empresas
} // Fin de la función Listar_Empresas

// Función para listar todos los proyectos no eliminados
function Listar_Proyectos($vConexion) { // Declaración de la función
    $Listado = array(); // Inicializa el array vacío de proyectos
    $SQL = "SELECT PR.Id, PR.Denominacion, PR.FechaCarga, PR.Prioridad, " . // Selecciona metadatos del proyecto
           "E.Denominacion as Empresa, P.Denominacion as Pais, " . // Selecciona la empresa y país asociados
           "U.Nombre as L_Nombre, U.Apellido as L_Apellido, U.Imagen as L_Imagen, " . // Selecciona campos del líder del proyecto
           "ES.Id as IdEstado, ES.Denominacion as Estado " . // Selecciona detalles del estado
           "FROM proyectos PR " . // Desde la tabla proyectos
           "JOIN empresas E ON PR.IdEmpresa = E.Id " . // JOIN con empresas
           "JOIN paises P ON E.IdPais = P.Id " . // JOIN con países a través de empresas
           "JOIN usuarios U ON PR.IdLider = U.IdUsuario " . // JOIN con usuario líder
           "JOIN estados ES ON PR.IdEstado = ES.Id " . // JOIN con estados de proyectos
           "WHERE PR.Eliminado = 0 " . // Excluye proyectos eliminados lógicamente
           "ORDER BY PR.FechaCarga ASC"; // Ordena mostrando los proyectos más antiguos primero
    $rs = mysqli_query($vConexion, $SQL); // Ejecuta la consulta
    $i = 0; // Inicializa el contador
    while ($data = mysqli_fetch_array($rs)) { // Bucle sobre los proyectos
        $Listado[$i]['ID'] = $data['Id']; // Guarda el ID del proyecto
        $Listado[$i]['DENOMINACION'] = $data['Denominacion']; // Guarda el título del proyecto
        $Listado[$i]['FECHA_CARGA'] = $data['FechaCarga']; // Guarda la fecha de carga
        $Listado[$i]['PRIORIDAD'] = $data['Prioridad']; // Guarda el valor booleano de prioridad
        $Listado[$i]['EMPRESA'] = $data['Empresa']; // Guarda el nombre de la empresa cliente
        $Listado[$i]['PAIS'] = $data['Pais']; // Guarda el nombre del país del cliente
        $Listado[$i]['LIDER_COMPLETO'] = $data['L_Nombre'] . ' ' . $data['L_Apellido']; // Guarda el nombre compuesto del líder
        $Listado[$i]['LIDER_IMG'] = empty($data['L_Imagen']) ? 'login.png' : $data['L_Imagen']; // Guarda el avatar del líder
        $Listado[$i]['ESTADO_ID'] = $data['IdEstado']; // Guarda el ID del estado
        $Listado[$i]['ESTADO_NOMBRE'] = $data['Estado']; // Guarda el texto del estado
        $i++; // Incrementa el contador
    } // Fin del bucle
    return $Listado; // Retorna la lista de proyectos
} // Fin de la función Listar_Proyectos

// Función para obtener todos los líderes de proyecto (Administradores y Líderes)
function Listar_Lideres($vConexion) { // Declaración de la función
    $Listado = array(); // Inicializa el array de líderes
    $SQL = "SELECT IdUsuario, Nombre, Apellido FROM usuarios " . // Campos de consulta
           "WHERE IdRol IN (1, 2) AND Eliminado = 0 " . // Filtra por roles Admin (1) o Líder (2)
           "ORDER BY Apellido, Nombre"; // Ordena alfabéticamente por apellido y luego por nombre
    $rs = mysqli_query($vConexion, $SQL); // Ejecuta la consulta
    $i = 0; // Inicializa el contador del bucle
    while ($data = mysqli_fetch_array($rs)) { // Bucle sobre los líderes obtenidos
        $Listado[$i]['ID'] = $data['IdUsuario']; // Guarda el ID del líder
        $Listado[$i]['NOMBRE_COMPLETO'] = $data['Apellido'] . ', ' . $data['Nombre']; // Formatea el nombre compuesto para los desplegables de selección
        $i++; // Incrementa el contador del bucle
    } // Fin del bucle
    return $Listado; // Retorna el array de líderes
} // Fin de la función Listar_Lideres

// Función para insertar un nuevo registro de empresa con sanitización y validación
function Insertar_Empresa($vConexion, $Denominacion, $IdPais, $Observaciones, $UsuarioCarga) { // Declaración de la función
    $Denominacion = mysqli_real_escape_string($vConexion, trim(strip_tags($Denominacion))); // Limpia la denominación
    $IdPais = intval($IdPais); // Fuerza la conversión a entero
    $Observaciones = mysqli_real_escape_string($vConexion, trim(strip_tags($Observaciones))); // Limpia el texto de observaciones
    $UsuarioCarga = mysqli_real_escape_string($vConexion, trim(strip_tags($UsuarioCarga))); // Limpia el nombre de usuario que realiza la carga
    $SQL = "INSERT INTO empresas (Denominacion, IdPais, Observaciones, FechaCarga, UsuarioCarga, Eliminado) " . // Declaración de consulta
           "VALUES ('$Denominacion', $IdPais, '$Observaciones', NOW(), '$UsuarioCarga', 0)"; // Definición de valores
    if (mysqli_query($vConexion, $SQL)) { // Ejecuta la consulta de inserción
        return true; // Retorna true en caso de éxito
    } else { // Manejo de fallos en la consulta
        return false; // Retorna false en caso de error
    } // Fin de la verificación de consulta
} // Fin de la función Insertar_Empresa

// Función para insertar un nuevo registro de proyecto
function Insertar_Proyecto($vConexion, $Denominacion, $IdEmpresa, $IdLider, $Observaciones, $Prioridad, $UsuarioCarga) { // Declaración de la función
    $Denominacion = mysqli_real_escape_string($vConexion, trim(strip_tags($Denominacion))); // Limpia el nombre del proyecto
    $IdEmpresa = intval($IdEmpresa); // Limpia el ID de la empresa
    $IdLider = intval($IdLider); // Limpia el ID del líder
    $Observaciones = mysqli_real_escape_string($vConexion, trim(strip_tags($Observaciones))); // Limpia las observaciones
    $Prioridad = intval($Prioridad) > 0 ? 1 : 0; // Fuerza el valor booleano de prioridad (0 o 1)
    $UsuarioCarga = mysqli_real_escape_string($vConexion, trim(strip_tags($UsuarioCarga))); // Limpia el nombre de usuario del creador
    $SQL = "INSERT INTO proyectos (Denominacion, IdEmpresa, IdLider, Observaciones, Prioridad, IdEstado, FechaCarga, UsuarioCarga, Eliminado) " . // Estructura de INSERT
           "VALUES ('$Denominacion', $IdEmpresa, $IdLider, '$Observaciones', $Prioridad, 1, NOW(), '$UsuarioCarga', 0)"; // El estado por defecto es Análisis Iniciado (1)
    if (mysqli_query($vConexion, $SQL)) { // Ejecuta la consulta de inserción
        return true; // Retorna true en caso de éxito
    } else { // Manejo de fallos en la consulta
        return false; // Retorna false en caso de error
    } // Fin de la verificación de consulta
} // Fin de la función Insertar_Proyecto

// Función para cancelar un proyecto existente (actualiza el ID de estado a 4)
function Cancelar_Proyecto($vConexion, $IdProyecto) { // Declaración de la función
    $IdProyecto = intval($IdProyecto); // Fuerza la validación del ID como entero
    $SQL = "UPDATE proyectos SET IdEstado = 4 WHERE Id = $IdProyecto"; // Comando SQL para cambiar el estado a Cancelado (4)
    if (mysqli_query($vConexion, $SQL)) { // Ejecuta la consulta de actualización
        return true; // Retorna true en caso de éxito
    } else { // Manejo de errores de base de datos
        return false; // Retorna false en caso de error
    } // Fin de la verificación de ejecución
} // Fin de la función Cancelar_Proyecto

// Función genérica de borrado lógico
function BorrarLogico($vConexion, $Tabla, $Id) { // Declaración de la función con parámetros de conexión, nombre de tabla e ID
    $Tabla = mysqli_real_escape_string($vConexion, trim(strip_tags($Tabla))); // Limpia el nombre de la tabla para proteger la estructura de la consulta
    $Id = intval($Id); // Fuerza la estructura numérica de la clave primaria
    $SQL = "UPDATE `$Tabla` SET Eliminado = 1 WHERE Id = $Id"; // Consulta de borrado lógico (establece la bandera Eliminado a 1)
    if ($Tabla == 'usuarios') { // Manejo específico para la tabla de usuarios (la clave primaria es IdUsuario)
        $SQL = "UPDATE `usuarios` SET Eliminado = 1, Activo = 0 WHERE IdUsuario = $Id"; // Desactiva y elimina lógicamente al usuario
    } // Fin del chequeo de tabla
    if (mysqli_query($vConexion, $SQL)) { // Ejecuta la consulta de actualización
        return true; // Retorna true en caso de éxito
    } else { // Manejo de fallos en la base de datos
        return false; // Retorna false en caso de error en la consulta
    } // Fin de la verificación de la consulta
} // Fin de la función BorrarLogico

/*
========================================================================================
FUNCIÓN: BorrarFisico (COMENTADA SEGÚN LO SOLICITADO)
========================================================================================
Esta función está comentada intencionalmente para alinearse con las mejores prácticas de
ingeniería de software sobre retención de datos, pistas de auditoría de borrado lógico
y directrices estrictas.

VENTAJAS DEL BORRADO FÍSICO (DELETE FROM tabla WHERE id = X):
- Reclama espacio de almacenamiento: Libera permanentemente el espacio de la base de datos y la asignación del sistema de archivos.
- Rendimiento de consultas: Las tablas más pequeñas son más rápidas de escanear e indexar, manteniendo la velocidad.
- Consultas más simples: No es necesario añadir cláusulas 'WHERE Eliminado = 0' a las consultas de listado.

DESVENTAJAS DEL BORRADO FÍSICO:
- Acción destructiva: Los datos eliminados son completamente irrecuperables sin restaurar copias de seguridad.
- Violación de clave foránea: Eliminar filas padre rompe enlaces o desencadena eliminaciones en cascada.
- Pérdida de pistas de auditoría: Se borra el historial normativo y el registro de acciones/usuarios.

VENTAJAS DEL BORRADO LÓGICO (UPDATE tabla SET Eliminado = 1 WHERE id = X):
- No destructivo: Los registros borrados lógicamente pueden restaurarse o consultarse fácilmente para auditorías.
- Integridad referencial: Mantiene contentas las restricciones de clave foránea al preservar la existencia de la fila.
- Red de seguridad: Protege contra clics accidentales de usuarios o errores administrativos.

DESVENTAJAS DEL BORRADO LÓGICO:
- Mayor tamaño de base de datos: Los datos permanecen almacenados de forma permanente, aumentando el tamaño de las copias de seguridad con el tiempo.
- Sobrecarga en consultas: Los scripts de listado deben filtrar explícitamente las filas con `Eliminado = 0`.
========================================================================================

function BorrarFisico($vConexion, $Tabla, $Id) { // Inicio del código comentado
    $Tabla = mysqli_real_escape_string($vConexion, trim(strip_tags($Tabla))); // Limpia el nombre de la tabla
    $Id = intval($Id); // Fuerza la clave primaria a un entero numérico
    $PK = 'Id'; // Nombre por defecto del campo de clave primaria
    if ($Tabla == 'usuarios') { // Comprueba la excepción para la tabla de usuarios
        $PK = 'IdUsuario'; // Nombre correcto del campo PK para usuarios
    } // Fin de la asignación del campo PK
    $SQL = "DELETE FROM `$Tabla` WHERE `$PK` = $Id"; // Consulta destructiva de borrado físico
    if (mysqli_query($vConexion, $SQL)) { // Ejecuta la consulta SQL en la conexión
        return true; // Retorna estado booleano de éxito
    } else { // Manejo de fallos en la ejecución
        return false; // Retorna estado de fallo
    } // Fin de la verificación de conexión
} // Fin de la función
*/
?>
