<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
require_once '../config/database.php';
$stmt = $pdo->query("SELECT * FROM pcs_armadas ORDER BY fecha DESC");
$pcs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PCs Armadas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
    function verPiezas(piezas) {
        let html = '<ul style="padding-left:18px;">';
        for (const [k, v] of Object.entries(piezas)) {
            html += `<li><b>${k.toUpperCase()}:</b> ${v}</li>`;
        }
        html += '</ul>';
        // Modal simple
        let modal = document.createElement('div');
        modal.style.position = 'fixed';
        modal.style.top = 0;
        modal.style.left = 0;
        modal.style.width = '100vw';
        modal.style.height = '100vh';
        modal.style.background = 'rgba(0,0,0,0.5)';
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        modal.style.zIndex = 9999;
        let content = document.createElement('div');
        content.style.background = '#fff';
        content.style.padding = '32px 24px';
        content.style.borderRadius = '10px';
        content.style.minWidth = '320px';
        content.innerHTML = '<h3 style="color:#3a0ca3;">Piezas de la PC</h3>' + html +
            '<div style="text-align:right;margin-top:18px;"><button onclick="this.closest(\'.modal-piezas\').remove()" style="background:#e74c3c;color:#fff;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;">Cerrar</button></div>';
        let wrap = document.createElement('div');
        wrap.className = 'modal-piezas';
        wrap.appendChild(content);
        modal.appendChild(wrap);
        document.body.appendChild(modal);
        modal.onclick = function(e) { if (e.target === modal) modal.remove(); };
    }
    </script>
</head>
<body>
<?php include '../public/layouts/sidebar.php'; ?>
<div class="main-content">
    <?php include '../public/layouts/header.php'; ?>
    <h1 style="color:#3a0ca3;">PCs Armadas</h1>
    <table class="pcs-table">
        <thead>
            <tr>
        <th>Fecha</th>
        <th>Usuario</th>
        <th>Gama</th>
        <th>Cantidad</th> <!-- Nueva columna -->
        <th>PC ensamblada</th>
        <th>Piezas</th>
        <th>Checklist QA</th>
    </tr> 
        </thead>
        <?php foreach($pcs as $pc): ?>
    <tr>
        <td data-label="Fecha"><?= htmlspecialchars($pc['fecha']) ?></td>
        <td data-label="Usuario"><?= htmlspecialchars($pc['usuario']) ?></td>
        <td data-label="Gama"><?= htmlspecialchars($pc['gama']) ?></td>
        <td data-label="Cantidad"><?= htmlspecialchars($pc['cantidad']) ?></td>
        <td data-label="PC ensamblada">
            <span style="color:green;font-weight:bold;">
                <i class="fas fa-check-circle"></i> Ensamblada
            </span>
        </td>
        <td data-label="Piezas">
            <button class="btn-piezas" onclick='verPiezas(<?= json_encode([
                'CPU' => $pc['cpu'],
                'RAM' => $pc['ram'],
                'Almacenamiento' => $pc['storage'],
                'Tarjeta Madre' => $pc['mb'],
                'GPU' => $pc['gpu'],
                'PSU' => $pc['psu'],
                'Gabinete' => $pc['case']
            ], JSON_UNESCAPED_UNICODE | JSON_HEX_APOS) ?>)'>
                Ver piezas
            </button>
        </td>
        <td data-label="Checklist QA">
            <?php
            $qa = json_decode($pc['checklist_qa'], true);
            $pc_id = $pc['id'];
            ?>
            <div style="display:flex; flex-direction:column; gap:7px;">
                <button class="btn-piezas" onclick="verChecklistQA(<?= $pc['id'] ?>)">
                    <i class="fas fa-list-check"></i> Ver checklist
                </button>
                <button class="btn-piezas" style="background:#43aa8b;" onclick="abrirAgregarChecklist(<?= $pc_id ?>)">
                    <i class="fas fa-plus"></i> Añadir checklist
                </button>
                <button class="btn-piezas" style="background:#f9c846;color:#222;" onclick="window.open('imprimir_qa.php?pc_id=<?= $pc_id ?>','_blank')">
                    <i class="fas fa-print"></i> Imprimir QA
                </button>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
    </table>
</div>
<script>
function verChecklistQA(pc_id) {
    fetch('ver_checklists.php?pc_id=' + pc_id)
        .then(r => r.json())
        .then(data => {
            let html = '<h3 style="color:#3a0ca3;">Checklists QA</h3>';
            if (!Array.isArray(data) || data.length === 0) {
                html += '<p>No hay checklist registrado.</p>';
            } else {
                data.forEach((check, idx) => {
                    html += `<div style="border-bottom:1px solid #ececec;padding-bottom:10px;margin-bottom:10px;">
                        <b>Checklist #${idx + 1}</b> <span style="color:#888;">por ${check.usuario} el ${check.fecha}</span>
                        <ul style="padding-left:18px;">`;
                    check.resultado.forEach(item => {
                        html += `<li>
                            <b>${item.nombre}</b>
                            <span style="color:${item.ok ? '#43aa8b' : '#e74c3c'};font-weight:bold;"> [${item.ok ? 'OK' : 'Falla'}]</span>
                            ${item.obs ? `<span style="color:#888;">(${item.obs})</span>` : ''}
                        </li>`;
                    });
                    html += `</ul></div>`;
                });
            }
            mostrarModal(html);
        });
}

function abrirAgregarChecklist(pc_id) {
    let html = `<h3 style="color:#3a0ca3;">Nuevo Checklist QA</h3>
        <form id="form-nuevo-qa">
            <div style="margin-bottom:10px;">
                <input type="file" id="excel-input" accept=".xlsx,.xls" style="display:none;">
                <button type="button" onclick="document.getElementById('excel-input').click()" style="background:#f9c846;color:#222;border:none;padding:7px 16px;border-radius:6px;cursor:pointer;">
                    <i class="fas fa-file-excel"></i> Subir Excel
                </button>
                <button type="button" id="btn-usar-checklist" style="background:#4361ee;color:#fff;border:none;padding:7px 16px;border-radius:6px;cursor:pointer;margin-left:8px;">
                    <i class="fas fa-copy"></i> Usar checklist existente
                </button>
                <span id="excel-status" style="margin-left:10px;color:#888;"></span>
            </div>
            <div id="checklist-existentes" style="display:none;margin-bottom:10px;">
                <label style="font-size:0.95em;">Selecciona un checklist:</label>
                <select id="select-checklist" style="margin-left:8px;"></select>
                <button type="button" id="btn-cargar-checklist" style="background:#43aa8b;color:#fff;border:none;padding:4px 12px;border-radius:6px;cursor:pointer;margin-left:8px;">Cargar</button>
            </div>
            <div id="qa-items-nuevo"></div>
            <button type="button" onclick="agregarItemQA()" style="margin-bottom:10px;background:#4361ee;color:#fff;border:none;padding:7px 16px;border-radius:6px;cursor:pointer;">
                <i class="fas fa-plus"></i> Añadir ítem
            </button>
            <div style="text-align:right;margin-top:18px;">
                <button type="submit" style="background:#3a0ca3;color:#fff;border:none;padding:10px 28px;border-radius:6px;cursor:pointer;font-size:1.1rem;">
                    <i class="fas fa-save"></i> Guardar Checklist
                </button>
            </div>
        </form>`;
    mostrarModal(html);
    agregarItemQA();

    // Excel file handler (igual que antes)
    document.getElementById('excel-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        document.getElementById('excel-status').textContent = 'Cargando...';
        const reader = new FileReader();
        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {type: 'array'});
            const sheet = workbook.Sheets[workbook.SheetNames[0]];
            const rows = XLSX.utils.sheet_to_json(sheet, {header:1});
            let count = 0;
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const nombre = row[0] ? String(row[0]).trim() : '';
                let obs = row[1] !== undefined ? String(row[1]) : '';
                obs = obs.replace(/[\r\n]+/g, ' ').replace(/"/g, "'");
                if (nombre) {
                    let div = document.createElement('div');
                    div.className = 'qa-item';
                    div.style.marginBottom = '12px';
                    div.innerHTML = `
                        <input type="text" name="nombre" placeholder="Nombre de la verificación" style="width:40%;margin-right:8px;" value="${nombre}">
                        <label><input type="checkbox" name="ok"> OK</label>
                        <label style="margin-left:8px;"><input type="checkbox" name="fail"> Falla</label>
                        <input type="text" name="obs" placeholder="Observaciones" style="width:35%;margin-left:8px;" value="${obs}">
                        <button type="button" onclick="this.parentNode.remove()" style="background:#e74c3c;color:#fff;border:none;border-radius:50%;width:22px;height:22px;font-size:14px;cursor:pointer;margin-left:8px;">–</button>
                    `;
                    document.getElementById('qa-items-nuevo').appendChild(div);
                    count++;
                }
            }
            document.getElementById('excel-status').textContent = count ? `Cargados ${count} ítems.` : 'No se encontraron ítems.';
        };
        reader.readAsArrayBuffer(file);
    });

    // Botón para mostrar select de checklist existentes
    document.getElementById('btn-usar-checklist').onclick = function() {
        let cont = document.getElementById('checklist-existentes');
        cont.style.display = cont.style.display === 'none' ? 'block' : 'none';
        // Solo cargar si está vacío
        if (!document.getElementById('select-checklist').options.length) {
            fetch('ver_checklists.php?pc_id=0')
                .then(r => r.json())
                .then(data => {
                    let select = document.getElementById('select-checklist');
                    select.innerHTML = '';
                    if (!Array.isArray(data) || data.length === 0) {
                        select.innerHTML = '<option value="">No hay checklists guardados</option>';
                    } else {
                        data.forEach((check, idx) => {
    let label = `#${idx+1} (${check.usuario} - ${check.fecha})`;
    let opt = document.createElement('option');
    opt.value = idx;
    opt.textContent = label;
    opt.dataset.resultado = JSON.stringify(check.resultado);
    select.appendChild(opt);
});
                    }
                });
        }
    };

    // Botón para cargar checklist seleccionado
    document.getElementById('btn-cargar-checklist').onclick = function() {
    let select = document.getElementById('select-checklist');
    let idx = select.selectedIndex;
    if (idx < 0) return;
    let resultado = select.options[idx].dataset.resultado;
    if (!resultado) return;
    let items = JSON.parse(resultado);
    document.getElementById('qa-items-nuevo').innerHTML = '';
    items.forEach(item => {
        let div = document.createElement('div');
        div.className = 'qa-item';
        div.style.marginBottom = '12px';
        div.innerHTML = `
            <div style="display:flex;align-items:center;gap:8px;">
                <textarea name="nombre" placeholder="Operación" style="width:28%;min-width:120px;max-width:32%;min-height:28px;resize:vertical;margin-right:6px;border-radius:5px;border:1px solid #bfc4ce;padding:5px 8px;">${item.nombre || ''}</textarea>
                <textarea name="obs" placeholder="Observaciones" style="width:32%;min-width:120px;max-width:38%;min-height:28px;resize:vertical;margin-right:6px;border-radius:5px;border:1px solid #bfc4ce;padding:5px 8px;">${item.obs || ''}</textarea>
                <label style="background:#eafbe7;padding:3px 10px;border-radius:5px;margin-right:4px;display:flex;align-items:center;gap:3px;">
                    <input type="checkbox" name="ok" style="margin:0 3px 0 0;" ${item.ok ? 'checked' : ''}> OK
                </label>
                <label style="background:#ffeaea;padding:3px 10px;border-radius:5px;display:flex;align-items:center;gap:3px;">
                    <input type="checkbox" name="fail" style="margin:0 3px 0 0;" ${item.fail ? 'checked' : ''}> Falla
                </label>
                <button type="button" onclick="this.parentNode.parentNode.remove()" style="background:#e74c3c;color:#fff;border:none;border-radius:50%;width:22px;height:22px;font-size:14px;cursor:pointer;margin-left:8px;">–</button>
            </div>
        `;
        document.getElementById('qa-items-nuevo').appendChild(div);
    });
};

    document.getElementById('form-nuevo-qa').onsubmit = function(e) {
    e.preventDefault();
    let items = [];
    document.querySelectorAll('#qa-items-nuevo .qa-item').forEach(div => {
        let nombre = div.querySelector('textarea[name="nombre"]').value.trim();
        let ok = div.querySelector('input[name="ok"]').checked;
        let fail = div.querySelector('input[name="fail"]').checked;
        let obs = div.querySelector('textarea[name="obs"]').value.trim();
        if (nombre) items.push({nombre, ok, fail, obs});
    });
        fetch('agregar_checklist.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                pc_id: pc_id,
                resultado: items
            })
        }).then(r => r.json()).then(res => {
            if (res.success) {
                alert('Checklist guardado correctamente');
                document.querySelector('.modal-checklist').remove();
            } else {
                alert('Error: ' + (res.error || ''));
            }
        });
    };
}

function agregarItemQA() {
    let div = document.createElement('div');
    div.className = 'qa-item';
    div.style.marginBottom = '12px';
    div.innerHTML = `
        <div style="display:flex;align-items:center;gap:8px;">
            <textarea name="nombre" placeholder="Operación" style="width:28%;min-width:120px;max-width:32%;min-height:28px;resize:vertical;margin-right:6px;border-radius:5px;border:1px solid #bfc4ce;padding:5px 8px;"></textarea>
            <textarea name="obs" placeholder="Observaciones" style="width:32%;min-width:120px;max-width:38%;min-height:28px;resize:vertical;margin-right:6px;border-radius:5px;border:1px solid #bfc4ce;padding:5px 8px;"></textarea>
            <label style="background:#eafbe7;padding:3px 10px;border-radius:5px;margin-right:4px;display:flex;align-items:center;gap:3px;">
                <input type="checkbox" name="ok" style="margin:0 3px 0 0;"> OK
            </label>
            <label style="background:#ffeaea;padding:3px 10px;border-radius:5px;display:flex;align-items:center;gap:3px;">
                <input type="checkbox" name="fail" style="margin:0 3px 0 0;"> Falla
            </label>
            <button type="button" onclick="this.parentNode.parentNode.remove()" style="background:#e74c3c;color:#fff;border:none;border-radius:50%;width:22px;height:22px;font-size:14px;cursor:pointer;margin-left:8px;">–</button>
        </div>
    `;
    document.getElementById('qa-items-nuevo').appendChild(div);
}

function imprimirChecklist(pc_id) {
    // Aquí puedes abrir una nueva ventana o generar PDF (pendiente de implementar)
    alert('Funcionalidad para imprimir checklist de la PC ID: ' + pc_id + ' (pendiente de implementar)');
}

function mostrarModal(html) {
    let modal = document.createElement('div');
    modal.style.position = 'fixed';
    modal.style.top = 0;
    modal.style.left = 0;
    modal.style.width = '100vw';
    modal.style.height = '100vh';
    modal.style.background = 'rgba(0,0,0,0.5)';
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.zIndex = 9999;
    let content = document.createElement('div');
    content.style.background = '#fff';
    content.style.padding = '32px 24px';
    content.style.borderRadius = '10px';
    content.style.minWidth = '320px';
    content.style.maxWidth = '95vw';
    content.style.maxHeight = '80vh'; // <-- Limita el alto
    content.style.overflowY = 'auto'; // <-- Habilita scroll vertical
    content.innerHTML = html +
        '<div style="text-align:right;margin-top:18px;"><button onclick="this.closest(\'.modal-checklist\').remove()" style="background:#e74c3c;color:#fff;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;">Cerrar</button></div>';
    let wrap = document.createElement('div');
    wrap.className = 'modal-checklist';
    wrap.appendChild(content);
    modal.appendChild(wrap);
    document.body.appendChild(modal);
    modal.onclick = function(e) { if (e.target === modal) modal.remove(); };
}
</script>
</body>
</html>