
<?php

function calcularSubtotal($precio, $cantidad) {
    return $precio * $cantidad;
}

function calcularTransporte($cantidad) {
    if ($cantidad <= 2) {
        return 2.00;
    } else {
        return 0.00;
    }
}

function calcularPresupuesto($precio, $cantidad) {
    $subtotal = calcularSubtotal($precio, $cantidad);
    $transporte = calcularTransporte($cantidad);

    return [
        "subtotal" => $subtotal,
        "transporte" => $transporte,
        "total" => $subtotal + $transporte
    ];
}
