# Soluciones — Retos de examen, Equipo 3

> Material para quien corrige o presenta las soluciones. Mantener este archivo separado y no mostrarlo al equipo que responde antes de que entregue sus respuestas.

## Pregunta 1 — Parámetros y retorno

**Respuesta correcta: C.**

**Código completo:**

```php
function calcularTransporte($cantidad) {
    if ($cantidad <= 2) {
        return 2.00;
    } else {
        return 0.00;
    }
}
```

**Salidas esperadas:**

- Para `$cantidad = 2`, la llamada `calcularTransporte(2)` devuelve `2.00`.
- Para `$cantidad = 4`, la llamada `calcularTransporte(4)` devuelve `0.00`.

**Por qué fallan los distractores:**

- **A:** invertir la comparación hace que una cantidad de 4 entre en la rama de 2 €, aunque de 3 a 5 unidades el transporte debe ser gratis. La cantidad 2 por sí sola no descubre este error; por eso se pide predecir otra entrada.
- **B:** `echo` imprime `2.00`, pero no devuelve ese importe. La función termina devolviendo `null` en esa rama, por lo que quien la llama no recibe el coste de transporte.
- **D:** la condición sirve, pero devuelve `0.00` para 1–2 unidades, cuando debería devolver `2.00`.

## Pregunta 2 — Acceso y recorrido del array

**Respuesta correcta: D.**

**Código completo:**

```php
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
```

**Salida esperada para** `$productoElegido = "cem325"` (usando los datos actuales del catálogo):

```html
<option value="cem325" selected>CEM II/B-L 32,5 N — 5,20 €/unidad</option>
```

**Por qué fallan los distractores:**

- **A:** intercambia las variables: `$datos` recibiría la clave de texto y `$clave` el array del producto. Así, la comparación de selección no coincide y `htmlspecialchars($clave, ...)` recibe un array en vez de texto.
- **B:** el catálogo no tiene un campo llamado `producto`; el nombre está guardado en `nombre`. El acceso no muestra el nombre requerido y puede provocar un aviso por índice inexistente.
- **C:** `precio` sí existe, pero esa opción mostraría el precio en el lugar donde el enunciado pide el nombre del producto. El bloque ya imprime el precio en la línea siguiente.
