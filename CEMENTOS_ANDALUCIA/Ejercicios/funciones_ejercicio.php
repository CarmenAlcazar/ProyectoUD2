//EJERCICIO PARA CLASE

<?php

// Calcula el precio de los productos sin transporte.
???? calcularSubtotal($precio, $cantidad) {      // 1
    return $precio ???? $cantidad;               // 2
}

// Calcula el transporte según la cantidad.
function calcularTransporte($cantidad) {
    if ($cantidad ???? 2) {                      // 3
        return 2.00;
    } ???? {                                     // 4
        return 0.00;
    }
}

// Calcula y devuelve todos los importes.
function calcularPresupuesto($precio, $cantidad) {
    $subtotal = ???? ($precio, $cantidad);        // 5
    $transporte = calcularTransporte(????);      // 6
    $total = $subtotal ???? $transporte;         // 7

    ???? [                                       // 8
        "subtotal" => $subtotal,
        "transporte" => $transporte,
        "total" => ????                          // 9
    ];
}