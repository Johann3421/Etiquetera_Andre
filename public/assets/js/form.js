document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('posForm');
    const itemsContainer = document.querySelector('.items-container');
    const addItemBtn = document.getElementById('addItem');
    const printBtn = document.getElementById('printBoleta');
    const boletaPreview = document.getElementById('boletaPreview');
    
    let itemCount = 1;

    // Agregar nuevo ítem
    addItemBtn.addEventListener('click', () => {
        const itemRow = document.createElement('div');
        itemRow.className = 'item-row';
        itemRow.innerHTML = `
            <input type="text" name="items[${itemCount}][descripcion]" placeholder="Descripción" required>
            <input type="number" name="items[${itemCount}][cantidad]" placeholder="Cantidad" min="1" required>
            <input type="number" name="items[${itemCount}][precio]" placeholder="Precio" min="0" step="0.01" required>
            <button type="button" class="remove-item">×</button>
        `;
        itemsContainer.appendChild(itemRow);
        itemCount++;
    });

    // Eliminar ítem
    itemsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-item')) {
            e.target.parentElement.remove();
        }
    });

    // Generar boleta
    printBtn.addEventListener('click', () => {
        const formData = new FormData(form);
        const cliente = formData.get('cliente');
        const items = [];
        
        // Calcular total
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const descripcion = row.querySelector('input[name$="[descripcion]"]').value;
            const cantidad = parseFloat(row.querySelector('input[name$="[cantidad]"]').value);
            const precio = parseFloat(row.querySelector('input[name$="[precio]"]').value);
            const subtotal = cantidad * precio;
            
            items.push({ descripcion, cantidad, precio, subtotal });
            total += subtotal;
        });

        // Mostrar boleta
        document.getElementById('boletaCliente').textContent = cliente;
        
        const itemsHtml = items.map(item => `
            <p>${item.descripcion} - ${item.cantidad} x $${item.precio.toFixed(2)} = $${item.subtotal.toFixed(2)}</p>
        `).join('');
        
        document.getElementById('boletaItems').innerHTML = itemsHtml;
        document.getElementById('boletaTotal').textContent = total.toFixed(2);
        boletaPreview.style.display = 'block';

        // Imprimir
        window.print();
    });

    // Enviar formulario (AJAX)
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        try {
            const response = await fetch('/api/save_transaction.php', {
                method: 'POST',
                body: new FormData(form)
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert('Transacción guardada');
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al guardar');
        }
    });
});