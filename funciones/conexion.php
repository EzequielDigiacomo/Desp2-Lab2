<?php // Inicia el script de utilidad para la conexión a la base de datos
// Define la función ConexionBD para conectar con la base de datos MySQL
function ConexionBD($Host = 'localhost', $User = 'root', $Password = '', $BaseDeDatos = 'consultora') { // Declaración de la función con parámetros por defecto
    $linkConexion = mysqli_connect($Host, $User, $Password, $BaseDeDatos); // Intenta establecer la conexión con los parámetros dados
    if ($linkConexion != false) { // Verifica si la conexión fue exitosa
        return $linkConexion; // Retorna el enlace de conexión si fue exitoso
    } else { // Manejo de fallos en la conexión
        die('No se pudo establecer la conexión con la base de datos.'); // Finaliza la ejecución con un mensaje de error
    } // Fin de la verificación de conexión
} // Fin de la definición de la función ConexionBD
?>
