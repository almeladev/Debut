<?php

namespace core;

class ErrorHandler
{

    /**
     * Convierte todos los errores en excepciones.
     *
     * @param int    $level   Nivel de error
     * @param string $message Mensaje de error
     * @param string $file    Nombre del archivo donde se generó el error
     * @param int    $line    Número de línea en el archivo
     *
     * @return void
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Gestor de excepciones.
     *
     * @param Exception $exception La excepción
     *
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        // 404 (No encontrado), 500 (Error interno)
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);

        // Archivo de configuración
        require_once '../app/config.php';

        if ($debug_mode) {
            echo "<h1>¡Error!</h1>";
            echo "<p>Clase de excepción: '" . get_class($exception) . "'</p>";
            echo "<p>Mensaje: '" . $exception->getMessage() . "'</p>";
            echo "<p>Informe de ejecución:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Excepción arrojada en el archivo '" . $exception->getFile() . "' en la línea " . $exception->getLine() . "</p>";
        } else {

            // Si no existe el directorio lo crea
            $dir = dirname(__DIR__) . '/logs';
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $message = "Clase de excepción: '" . get_class($exception) . "'";
            $message .= " con mensaje '" . $exception->getMessage() . "'";
            $message .= "\nInforme de ejecución:\n" . $exception->getTraceAsString();
            $message .= "\nExcepción arrojada en el archivo '" . $exception->getFile() . "' en la línea " . $exception->getLine();

            error_log($message);
            View::template("errors/$code.html"); // Directorio de los archivos de registro
        }
    }
}
