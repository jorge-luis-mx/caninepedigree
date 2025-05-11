<?php

if (!function_exists('array_map_recursive')) {
    function array_map_recursive(callable $callback, array $array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$key] = is_array($value)
                ? array_map_recursive($callback, $value)
                : $callback($value);
        }
        return $result;
    }
}

if (!function_exists('generarCodigoRegistro')) {
    function generarCodigoRegistro() {
        $letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numeros = "0123456789";
    
        $codigo = 'REG-';
    
        // Letras (3 caracteres)
        for ($i = 0; $i < 3; $i++) {
            $codigo .= $letras[random_int(0, strlen($letras) - 1)];
        }
    
        // Números (6 dígitos)
        for ($i = 0; $i < 6; $i++) {
            $codigo .= $numeros[random_int(0, strlen($numeros) - 1)];
        }
    
        return $codigo;
    }
}

