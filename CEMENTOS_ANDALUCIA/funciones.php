<?php

// Calcula el precio de los productos sin transporte.
function calcularSubtotal($precio, $cantidad) {
    return $precio * $cantidad;
}

// Calcula el transporte según la cantidad.
function calcularTransporte($cantidad) {
    if ($cantidad <= 2) {
        return 2.00;
    } else {
        return 0.00;
    }
}

// Calcula y devuelve todos los importes.
function calcularPresupuesto($precio, $cantidad) {
    $subtotal = calcularSubtotal($precio, $cantidad);
    $transporte = calcularTransporte($cantidad);
    $total = $subtotal + $transporte;

    return [
        "subtotal" => $subtotal,
        "transporte" => $transporte,
        "total" => $total
    ];
}