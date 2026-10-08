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
    // Recoger los campos del formulario.
    $clienteRecibido = $_POST["cliente"] ?? "";
    $productoRecibido = $_POST["producto"] ?? "";
    $cantidadRecibida = $_POST["cantidad"] ?? "";

    // Comprobar los tipos recibidos antes de procesarlos.
    if (!is_string($clienteRecibido) ||
        !is_string($productoRecibido) ||
        !is_string($cantidadRecibida)) {
        $errores[] = "Los datos recibidos no son válidos.";
    } else {
        $cliente = trim($clienteRecibido);
        $productoElegido = $productoRecibido;
        $cantidadTexto = $cantidadRecibida;

        if (strlen($cliente) < 3) {
            $errores[] =
                "El nombre debe tener al menos 3 caracteres.";
        }

        // Comprobar que el producto existe en el catálogo.
        if (!array_key_exists($productoElegido, $catalogo)) {
            $errores[] = "Selecciona un producto válido.";
        }

        // Comprobar que la cantidad es un entero entre 1 y 5.
        $cantidadValida = filter_var(
            $cantidadTexto,
            FILTER_VALIDATE_INT
        );

        if ($cantidadTexto === "" ||
            $cantidadValida === false ||
            $cantidadValida < 1 ||
            $cantidadValida > 5) {
            $errores[] =
                "La cantidad debe ser un entero entre 1 y 5.";
        }
    }

    // Booleano que indica si la validación ha sido correcta.
    $valido = empty($errores);

    if ($valido) {
        $producto = $catalogo[$productoElegido];
        $cantidad = (int) $cantidadTexto;

        $presupuesto = calcularPresupuesto(
            $producto["precio"],
            $cantidad
        );
    }
}

// Preparar la cabecera de la página.
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

        <?php if (!$enviado): ?>

            <p>Introduce los datos para calcular tu presupuesto.</p>

        <?php elseif (!empty($errores)): ?>

            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li>
                            <?= htmlspecialchars(
                                $error,
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        <?php else: ?>

            <?php
            // print muestra un mensaje directamente.
            print "Pedido calculado correctamente.";

            // sprintf construye una cadena que se muestra después.
            $mensaje = sprintf(
                "Se han solicitado %d unidades.",
                $cantidad
            );
            ?>

            <div class="alert alert-success">
                <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
            </div>

        <?php endif; ?>

        <form action="index.php" method="post">
            <div class="form-group">
                <label for="cliente">Nombre del cliente</label>
                <input
                    type="text"
                    id="cliente"
                    name="cliente"
                    value="<?= htmlspecialchars(
                        $cliente,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="producto">Tipo de cemento</label>
                <select id="producto" name="producto" required>
                    <option value="">-- Selecciona un tipo --</option>

                    <?php foreach ($catalogo as $clave => $datos): ?>
                        <option
                            value="<?= htmlspecialchars(
                                $clave,
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>"
                            <?= $productoElegido === $clave
                                ? "selected"
                                : "" ?>
                        >
                            <?= htmlspecialchars(
                                $datos["nombre"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                            — <?= number_format(
                                $datos["precio"],
                                2,
                                ",",
                                "."
                            ) ?> €/unidad
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="cantidad">Cantidad de unidades (1–5)</label>
                <input
                    type="number"
                    id="cantidad"
                    name="cantidad"
                    min="1"
                    max="5"
                    step="1"
                    value="<?= htmlspecialchars(
                        $cantidadTexto,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    required
                >
            </div>

            <button type="submit">Calcular presupuesto</button>
        </form>

        <?php if ($presupuesto !== null): ?>

            <section class="resumen">
                <h2>Resumen del presupuesto</h2>

                <p>
                    <strong>Cliente:</strong>
                    <?= htmlspecialchars(
                        $cliente,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </p>

                <p>
                    <strong>Producto:</strong>
                    <?= htmlspecialchars(
                        $producto["nombre"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </p>

                <p><strong>Unidades:</strong> <?= $cantidad ?></p>

                <p>
                    <strong>Precio unitario:</strong>
                    <?php
                    printf(
                        "%.2f €",
                        $producto["precio"]
                    );
                    ?>
                </p>

                <p>
                    <strong>Subtotal:</strong>
                    <?= number_format(
                        $presupuesto["subtotal"],
                        2,
                        ",",
                        "."
                    ) ?> €
                </p>

                <p>
                    <strong>Transporte:</strong>
                    <?= number_format(
                        $presupuesto["transporte"],
                        2,
                        ",",
                        "."
                    ) ?> €
                </p>

                <p>
                    <strong>TOTAL:</strong>
                    <?= number_format(
                        $presupuesto["total"],
                        2,
                        ",",
                        "."
                    ) ?> €
                </p>
            </section>

        <?php endif; ?>

        <div class="nota-teorica">
            <strong>Condiciones de entrega:</strong>
            2 € de transporte para 1–2 unidades.
            Transporte gratuito para 3–5 unidades.
        </div>
    </main>
</div>
</body>
</html>