
<?php

include __DIR__ . "/datos_prueba.php";
include __DIR__ . "/funciones_ejercicio.php";

// Prueba 1: acceder a un producto del array.
$clave = "cem325";

if (array_key_exists($clave, $catalogo)) {
    echo "Producto: " . $catalogo[$clave]["nombre"] . "<br>";

    $resultado = calcularPresupuesto(
        $catalogo[$clave]["precio"],
        2
    );

    echo "Subtotal: " . $resultado["subtotal"] . " €<br>";
    echo "Transporte: " . $resultado["transporte"] . " €<br>";
    echo "Total: " . $resultado["total"] . " €<br>";
}

// Prueba 2: recorrer el catálogo.
echo "<h3>Catálogo completo</h3>";

foreach ($catalogo as $clave => $producto) {
    echo $clave . " - ";
    echo $producto["nombre"] . " - ";
    echo $producto["precio"] . " €<br>";
}
