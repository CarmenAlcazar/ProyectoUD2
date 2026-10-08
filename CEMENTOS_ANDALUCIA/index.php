<?php
include __DIR__ . "/datos.php";
include __DIR__ . "/funciones.php";

$enviado = $_SERVER["REQUEST_METHOD"] === "POST";
$errores = [];
$presupuesto = null;

$cliente = "";
$productoElegido = "";
$cantidadTexto = "";

if ($enviado) {
    $clienteRecibido = $_POST["cliente"] ?? "";
    $productoRecibido = $_POST["producto"] ?? "";
    $cantidadRecibida = $_POST["cantidad"] ?? "";

    if (
        !is_string($clienteRecibido) ||
        !is_string($productoRecibido) ||
        !is_string($cantidadRecibida)
    ) {
        $errores[] = "Los datos recibidos no son válidos.";
    } else {
        $cliente = trim($clienteRecibido);
        $productoElegido = $productoRecibido;
        $cantidadTexto = $cantidadRecibida;

        if (strlen($cliente) < 3) {
            $errores[] = "El nombre debe tener al menos 3 caracteres.";
        }

        if (!array_key_exists($productoElegido, $catalogo)) {
            $errores[] = "Selecciona un producto válido.";
        }

        $cantidadValida = filter_var($cantidadTexto, FILTER_VALIDATE_INT);

        if (
            $cantidadTexto === "" ||
            $cantidadValida === false ||
            $cantidadValida < 1 ||
            $cantidadValida > 5
        ) {
            $errores[] = "La cantidad debe ser un entero entre 1 y 5.";
        }
    }

    $valido = empty($errores);

    if ($valido) {
        $producto = $catalogo[$productoElegido];
        $cantidad = (int) $cantidadTexto;

        $presupuesto = calcularPresupuesto($producto["precio"], $cantidad);
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cementos Andalucía - Presupuestos</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">

        <?php include __DIR__ . "/cabecera.php"; ?>

        <main>
            <h2>Nuevo pedido</h2>

            <?php
            if (!$enviado) {
                echo "<p>Introduce los datos para calcular tu presupuesto.</p>";
            } elseif (!empty($errores)) {
                echo '<div class="alert alert-danger"><ul>';
                foreach ($errores as $error) {
                    echo "<li>" . htmlspecialchars($error, ENT_QUOTES, "UTF-8") . "</li>";
                }
                echo "</ul></div>";
            } else {
                $mensaje = sprintf("Pedido calculado correctamente. Se han solicitado %d unidades.", $cantidad);
                echo '<div class="alert alert-success">';
                echo htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8");
                echo "</div>";
            }
            ?>

            <form action="index.php" method="post">
                <div class="form-group">
                    <label for="cliente">Nombre del cliente</label>
                    <input type="text" id="cliente" name="cliente" required
                        value="<?php echo htmlspecialchars($cliente, ENT_QUOTES, "UTF-8"); ?>">
                </div>

                <div class="form-group">
                    <label for="producto">Tipo de cemento</label>
                    <select id="producto" name="producto" required>
                        <option value="">-- Selecciona un tipo --</option>
                        <?php
                        foreach ($catalogo as $clave => $datos) {
                            $seleccionado = "";
                            if ($productoElegido === $clave) {
                                $seleccionado = "selected";
                            }

                            echo '<option value="' . htmlspecialchars($clave, ENT_QUOTES, "UTF-8") . '" ' . $seleccionado . '>';
                            echo htmlspecialchars($datos["nombre"], ENT_QUOTES, "UTF-8");
                            echo " — " . number_format($datos["precio"], 2, ",", ".") . " €/unidad";
                            echo "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cantidad">Cantidad de unidades (1–5)</label>
                    <input type="number" id="cantidad" name="cantidad" min="1" max="5" step="1" required
                        value="<?php echo htmlspecialchars($cantidadTexto, ENT_QUOTES, "UTF-8"); ?>">
                </div>

                <button type="submit">Calcular presupuesto</button>
            </form>

            <?php
            if ($presupuesto !== null) {
                echo '<section class="resumen">';
                echo "<h2>Resumen del presupuesto</h2>";
                echo "<p><strong>Cliente:</strong> " . htmlspecialchars($cliente, ENT_QUOTES, "UTF-8") . "</p>";
                echo "<p><strong>Producto:</strong> " . htmlspecialchars($producto["nombre"], ENT_QUOTES, "UTF-8") . "</p>";
                echo "<p><strong>Unidades:</strong> " . $cantidad . "</p>";
                echo "<p><strong>Precio unitario:</strong> " . number_format($producto["precio"], 2, ",", ".") . " €</p>";
                echo "<p><strong>Subtotal:</strong> " . number_format($presupuesto["subtotal"], 2, ",", ".") . " €</p>";
                echo "<p><strong>Transporte:</strong> " . number_format($presupuesto["transporte"], 2, ",", ".") . " €</p>";
                echo "<p><strong>TOTAL:</strong> " . number_format($presupuesto["total"], 2, ",", ".") . " €</p>";
                echo "</section>";
            }
            ?>

            <div class="nota-teorica">
                <strong>Condiciones de entrega:</strong>
                2 € de transporte para 1–2 unidades.
                Transporte gratuito para 3–5 unidades.
            </div>
        </main>
    </div>
</body>

</html>
