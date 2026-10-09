# Retos de examen — Equipo 3

## Pregunta 1 — Parámetros y retorno

**Enunciado:** Cementos Andalucía cobra 2 € de transporte para pedidos de 1–2 unidades y no cobra transporte para pedidos de 3–5 unidades. Se llama a `calcularTransporte($cantidad)` con una cantidad entera válida. ¿Qué completaciones hacen que la función devuelva el coste correspondiente, sin imprimirlo?

**Fragmento:**

```php
function calcularTransporte($cantidad) {
    if (___) {
        ___
    } else {
        return 0.00;
    }
}
```

**Opciones** (primera expresión para la condición; segunda para la rama):

- **A.** `$cantidad >= 2` ; `return 2.00;`
- **B.** `$cantidad <= 2` ; `echo 2.00;`
- **C.** `$cantidad <= 2` ; `return 2.00;`
- **D.** `$cantidad <= 2` ; `return 0.00;`

Elegid una opción, justificadla y predecid qué devolvería con otra entrada válida, por ejemplo, una cantidad de 4 unidades.

## Pregunta 2 — Acceso y recorrido del array

**Enunciado:** El catálogo de Cementos Andalucía es un array asociativo: cada clave identifica un producto y su valor contiene los campos `nombre` y `precio`. Para cada producto, el formulario debe mostrar su nombre y conservar el precio unitario. ¿Qué expresiones completan el recorrido para acceder al campo correcto?

**Fragmento:**

```php
foreach ($catalogo as ___) {
    $seleccionado = "";
    if ($productoElegido === $clave) {
        $seleccionado = "selected";
    }
    echo '<option value="' . htmlspecialchars($clave, ENT_QUOTES, "UTF-8") . '" ' . $seleccionado . '>';
    echo htmlspecialchars($datos[___], ENT_QUOTES, "UTF-8");
    echo " — " . number_format($datos["precio"], 2, ",", ".") . " €/unidad";
    echo "</option>";
}
```

**Opciones** (primera expresión para el recorrido; segunda para el campo mostrado como nombre):

- **A.** `$datos => $clave` ; `"nombre"`
- **B.** `$clave => $datos` ; `"producto"`
- **C.** `$clave => $datos` ; `"precio"`
- **D.** `$clave => $datos` ; `"nombre"`

Elegid una opción, justificadla y predecid qué opción HTML se generaría para otra entrada, por ejemplo, `$productoElegido = "cem325"`.
