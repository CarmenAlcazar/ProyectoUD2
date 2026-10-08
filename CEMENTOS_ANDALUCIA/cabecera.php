<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cementos Andalucía - Gestión de Pedidos</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>🏗️ Cementos Andalucía</h1>
            <p><em>Portal de cálculo de presupuestos y pedidos minoristas de cemento</em></p>
        </header>

        <main>
            <h2>Nuevo pedido</h2>
            <div id="mensajes"></div>

            <form id="form-pedido" novalidate>
                <div class="form-group">
                    <label for="cliente">Nombre del cliente</label>
                    <input type="text" id="cliente" placeholder="Ej: Construcciones Pérez">
                </div>

                <div class="form-group">
                    <label for="tipo">Tipo de cemento (saco de 25 kg)</label>
                    <select id="tipo">
                        <option value="">-- Selecciona un tipo --</option>
                        <option value="5.20">CEM II/B-L 32,5 N — 5,20 €/saco</option>
                        <option value="6.10">CEM II/A-L 42,5 R — 6,10 €/saco</option>
                        <option value="7.30">CEM I 52,5 R — 7,30 €/saco</option>
                        <option value="11.50">Cemento blanco BL 52,5 — 11,50 €/saco</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cantidad">Cantidad de sacos</label>
                    <input type="number" id="cantidad" min="1" max="500" step="1" placeholder="1 - 500">
                </div>

                <div class="form-group">
                    <label for="entrega">Entrega</label>
                    <select id="entrega">
                        <option value="0">Recogida en almacén (gratis)</option>
                        <option value="25">Envío a domicilio (+25 €)</option>
                    </select>
                </div>

                <button type="submit">Calcular pedido</button>
            </form>

            <div id="resumen" class="resumen" hidden></div>

            <div class="nota-teorica">
                <strong>Condiciones:</strong> 5 % de descuento a partir de 50 sacos y 10 % a partir de 100.
                Los precios no incluyen IVA; se aplica un 21 %.
            </div>
        </main>
    </div>

    <script>
        const IVA = 0.21;
        const form = document.getElementById('form-pedido');
        const mensajes = document.getElementById('mensajes');
        const resumen = document.getElementById('resumen');

        const euros = n => n.toLocaleString('es-ES', {
            style: 'currency',
            currency: 'EUR'
        });

        const escapar = texto => texto.replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [c]));

        function descuentoPorCantidad(sacos) {
            if (sacos >= 100) return 0.10;
            if (sacos >= 50) return 0.05;
            return 0;
        }

        form.addEventListener('submit', e => {
            e.preventDefault();
            mensajes.innerHTML = '';
            resumen.hidden = true;

            const cliente = document.getElementById('cliente').value.trim();
            const selectTipo = document.getElementById('tipo');
            const precio = parseFloat(selectTipo.value);
            const nombreTipo = selectTipo.options[selectTipo.selectedIndex].text.split(' — ')[0];
            const cantidad = Number(document.getElementById('cantidad').value);
            const envio = parseFloat(document.getElementById('entrega').value);

            const errores = [];
            if (cliente.length < 3) errores.push('Introduce el nombre del cliente (mínimo 3 caracteres).');
            if (!selectTipo.value) errores.push('Selecciona un tipo de cemento.');
            if (!Number.isInteger(cantidad) || cantidad < 1 || cantidad > 500) {
                errores.push('La cantidad debe ser un número entero entre 1 y 500.');
            }

            if (errores.length) {
                mensajes.innerHTML = '<div class="alert alert-danger"><ul>' +
                    errores.map(m => `<li>${m}</li>`).join('') + '</ul></div>';
                return;
            }

            const bruto = precio * cantidad;
            const pctDto = descuentoPorCantidad(cantidad);
            const descuento = bruto * pctDto;
            const base = bruto - descuento + envio;
            const iva = base * IVA;
            const total = base + iva;

            mensajes.innerHTML = '<div class="alert alert-success">Pedido calculado correctamente.</div>';

            resumen.innerHTML = `
            <h2>Resumen del pedido</h2>
            <p><strong>Cliente:</strong> ${escapar(cliente)}</p>
            <p><strong>Producto:</strong> ${escapar(nombreTipo)} × ${cantidad} sacos</p>
            <p><strong>Subtotal:</strong> ${euros(bruto)}</p>
            <p><strong>Descuento (${pctDto * 100} %):</strong> -${euros(descuento)}</p>
            <p><strong>Envío:</strong> ${euros(envio)}</p>
            <p><strong>Base imponible:</strong> ${euros(base)}</p>
            <p><strong>IVA (21 %):</strong> ${euros(iva)}</p>
            <p><strong>TOTAL:</strong> ${euros(total)}</p>
            <p><small>Peso total: ${(cantidad * 25).toLocaleString('es-ES')} kg</small></p>
        `;
            resumen.hidden = false;
        });

        form.addEventListener('reset', () => {
            mensajes.innerHTML = '';
            resumen.hidden = true;
        });
    </script>
</body>

</html>