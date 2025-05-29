<?php
session_start();
if (! isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
$categorias = [
    'baja'  => ['Gama Baja', ['cpu' => 'Procesador', 'ram' => 'Memoria RAM', 'storage' => 'Almacenamiento', 'mb' => 'Tarjeta Madre', 'gpu' => 'Tarjeta de Video', 'psu' => 'Fuente de Poder', 'case' => 'Gabinete']],
    'media' => ['Gama Media', ['cpu' => 'Procesador', 'ram' => 'Memoria RAM', 'storage' => 'Almacenamiento', 'mb' => 'Tarjeta Madre', 'gpu' => 'Tarjeta de Video', 'psu' => 'Fuente de Poder', 'case' => 'Gabinete']],
    'alta'  => ['Gama Alta', ['cpu' => 'Procesador', 'ram' => 'Memoria RAM', 'storage' => 'Almacenamiento', 'mb' => 'Tarjeta Madre', 'gpu' => 'Tarjeta de Video', 'psu' => 'Fuente de Poder', 'case' => 'Gabinete']],
    'ultra' => ['Gama Ultra', ['cpu' => 'Procesador', 'ram' => 'Memoria RAM', 'storage' => 'Almacenamiento', 'mb' => 'Tarjeta Madre', 'gpu' => 'Tarjeta de Video', 'psu' => 'Fuente de Poder', 'case' => 'Gabinete']],
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Fichas para PC | Sistema POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .fichas-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
            padding: 24px;
            max-width: 900px;
            margin: 0 auto;
        }

        .fichas-title {
            text-align: center;
            color: #3a0ca3;
            margin-bottom: 24px;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
            gap: 10px;
        }

        .tab-btn {
            padding: 10px 18px;
            border: none;
            background: #4361ee;
            color: #fff;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            font-weight: bold;
        }

        .tab-btn.active,
        .tab-btn:hover {
            background: #3a0ca3;
        }

        .ficha-form {
            display: none;
        }

        .ficha-form.active {
            display: block;
        }
    </style>
</head>

<body>
    <?php include '../public/layouts/sidebar.php'; ?>
    <div class="main-content">
        <?php include '../public/layouts/header.php'; ?>

        <div class="fichas-container">
            <h1 class="fichas-title"><i class="fas fa-desktop"></i> Fichas de Verificación de Componentes para PC</h1>
            <div class="tabs">
                <?php foreach ($categorias as $key => $cat): ?>
                    <button class="tab-btn<?php echo $key === 'baja' ? ' active' : '' ?>" data-tab="<?php echo $key ?>"><?php echo $cat[0] ?></button>
                <?php endforeach; ?>
            </div>

            <?php foreach ($categorias as $key => [$nombre, $campos]): ?>
                <form class="ficha-form<?php echo $key === 'baja' ? ' active' : '' ?>" id="ficha-<?php echo $key ?>">
                    <div class="form-section">
                        <div class="form-section-title">Componentes Principales</div>
                        <?php foreach ($campos as $campo => $label): ?>
                            <div class="form-group">
                                <label><?php echo $label ?></label>
                                <input type="text" name="<?php echo $campo ?>_<?php echo $key ?>" placeholder="Ej: <?php echo $label ?>">
                            </div>
                        <?php endforeach; ?>
                        <div class="form-group">
                <label>Cantidad de PCs a ensamblar</label>
                <input type="number" name="cantidad_<?php echo $key ?>" min="1" value="1" required>
            </div>
                    </div>
                    <div class="form-section">
                        <div class="form-section-title">Observaciones</div>
                        <textarea name="obs_<?php echo $key ?>" rows="2" placeholder="Notas adicionales..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-next"><i class="fas fa-arrow-right"></i> Siguiente</button>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        // Tabs para cambiar entre fichas
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.ficha-form').forEach(f => f.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById('ficha-' + btn.dataset.tab).classList.add('active');
            });
        });
        // Guardar ficha (simulado)
        document.querySelectorAll('.ficha-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Ficha guardada correctamente (simulado)');
                form.reset();
            });
        });
    </script>
    <script>
        const opcionesComponentes = {
            baja: {
                cpu: [
                    "Intel Core i3-10100", "Intel Pentium G6400", "Intel Core i3-9100F", "AMD Ryzen 3 3200G", "AMD Athlon 3000G",
                    "Intel Core i3-7100", "AMD Ryzen 3 2200G", "Intel Pentium G5400", "Intel Core i3-6100", "AMD A8-9600"
                ],
                ram: [
                    "4GB DDR3 1333MHz", "4GB DDR4 2133MHz", "8GB DDR3 1600MHz", "8GB DDR4 2400MHz", "4GB DDR4 2666MHz",
                    "8GB DDR4 2666MHz", "4GB DDR3 1600MHz", "8GB DDR4 2133MHz", "4GB DDR4 2400MHz", "8GB DDR3 1333MHz"
                ],
                storage: [
                    "500GB HDD", "1TB HDD", "120GB SSD", "240GB SSD", "320GB HDD",
                    "250GB HDD", "128GB SSD", "500GB SSD", "160GB HDD", "256GB SSD"
                ],
                mb: [
                    "ASUS H110M-K", "Gigabyte GA-H81M", "MSI A320M-A PRO", "ASRock H310CM-HDV", "Biostar A320MH",
                    "Gigabyte GA-A320M-S2H", "ASUS PRIME A320M-K", "MSI H110M PRO-VD", "ASRock H81M-HDS", "Biostar H310MHP"
                ],
                gpu: [
                    "Integrada", "NVIDIA GT 710", "NVIDIA GT 1030", "AMD Radeon R5 230", "NVIDIA GT 730",
                    "AMD Radeon HD 5450", "NVIDIA Quadro NVS 300", "AMD Radeon R7 240", "NVIDIA GT 610", "AMD Radeon HD 6570"
                ],
                psu: [
                    "350W Real", "400W Genérica", "450W Genérica", "300W Real", "500W Genérica",
                    "400W Real", "350W Genérica", "450W Real", "300W Genérica", "500W Real"
                ],
                case: [
                    "Mini Tower", "Micro ATX", "ATX Básico", "Slim", "Desktop Horizontal",
                    "Mini ITX", "Torre Compacta", "Caja Genérica", "Caja Pequeña", "Caja Económica"
                ]
            },
            media: {
                cpu: [
                    "Intel Core i5-10400F", "Intel Core i5-9400F", "AMD Ryzen 5 3600", "AMD Ryzen 5 2600", "Intel Core i5-11400",
                    "AMD Ryzen 5 3500", "Intel Core i5-8400", "AMD Ryzen 5 1600", "Intel Core i5-10600K", "AMD Ryzen 5 4500"
                ],
                ram: [
                    "8GB DDR4 2666MHz", "16GB DDR4 3200MHz", "16GB DDR4 2666MHz", "8GB DDR4 3200MHz", "16GB DDR4 3000MHz",
                    "8GB DDR4 3000MHz", "16GB DDR4 2400MHz", "8GB DDR4 2400MHz", "16GB DDR4 3600MHz", "8GB DDR4 3600MHz"
                ],
                storage: [
                    "480GB SSD", "1TB HDD", "500GB SSD", "2TB HDD", "240GB SSD + 1TB HDD",
                    "1TB SSD", "512GB SSD", "2TB SSD", "1TB HDD + 240GB SSD", "1TB NVMe SSD"
                ],
                mb: [
                    "ASUS B450M-A", "Gigabyte B450M DS3H", "MSI B450M PRO-VDH", "ASRock B450M-HDV", "ASUS B560M-A",
                    "Gigabyte B560M DS3H", "MSI B560M PRO-VDH", "ASRock B560M-HDV", "ASUS PRIME B450M-A", "Gigabyte B450M S2H"
                ],
                gpu: [
                    "NVIDIA GTX 1650", "AMD RX 6500 XT", "NVIDIA GTX 1050 Ti", "AMD RX 570", "NVIDIA GTX 1660",
                    "AMD RX 580", "NVIDIA GTX 1060", "AMD RX 560", "NVIDIA GTX 1630", "AMD RX 550"
                ],
                psu: [
                    "500W 80+ Bronze", "550W 80+ Bronze", "600W Genérica", "500W Real", "600W 80+ White",
                    "550W Real", "500W 80+ White", "600W 80+ Bronze", "650W Genérica", "550W 80+ White"
                ],
                case: [
                    "Mid Tower", "ATX Mediana", "Micro ATX Gamer", "Caja con Ventana", "Caja RGB Básica",
                    "Caja ATX Compacta", "Caja con Ventiladores", "Caja Gamer Económica", "Caja ATX Estándar", "Caja Micro ATX"
                ]
            },
            alta: {
                cpu: [
                    "Intel Core i7-12700K", "Intel Core i7-11700K", "AMD Ryzen 7 5800X", "AMD Ryzen 7 5700X", "Intel Core i7-10700K",
                    "AMD Ryzen 7 3700X", "Intel Core i7-9700K", "AMD Ryzen 7 3800XT", "Intel Core i7-8700K", "AMD Ryzen 7 7700X"
                ],
                ram: [
                    "32GB DDR4 3200MHz", "32GB DDR4 3600MHz", "32GB DDR5 4800MHz", "32GB DDR4 4000MHz", "32GB DDR5 5200MHz",
                    "32GB DDR4 3000MHz", "32GB DDR4 2666MHz", "32GB DDR5 5600MHz", "32GB DDR4 3733MHz", "32GB DDR5 6000MHz"
                ],
                storage: [
                    "1TB NVMe SSD", "2TB NVMe SSD", "1TB SSD + 2TB HDD", "2TB SSD", "1TB SSD + 1TB HDD",
                    "2TB HDD", "1TB SSD", "2TB SSD + 2TB HDD", "1TB NVMe + 2TB HDD", "2TB NVMe + 2TB HDD"
                ],
                mb: [
                    "ASUS Z590-A", "Gigabyte X570 AORUS", "MSI X570 Tomahawk", "ASRock X570 Phantom", "ASUS B550-F",
                    "Gigabyte B550 AORUS", "MSI B550 Tomahawk", "ASRock B550 Steel Legend", "ASUS Z690-P", "Gigabyte Z690 UD"
                ],
                gpu: [
                    "NVIDIA RTX 3070", "AMD RX 6800", "NVIDIA RTX 3060 Ti", "AMD RX 6750 XT", "NVIDIA RTX 3080",
                    "AMD RX 6700 XT", "NVIDIA RTX 2080 Super", "AMD RX 6600 XT", "NVIDIA RTX 2070 Super", "AMD RX 7600"
                ],
                psu: [
                    "750W 80+ Gold", "850W 80+ Gold", "750W 80+ Bronze", "850W 80+ Bronze", "750W 80+ Platinum",
                    "850W 80+ Platinum", "750W Modular", "850W Modular", "800W 80+ Gold", "800W 80+ Bronze"
                ],
                case: [
                    "ATX Tower", "Full Tower", "Caja con RGB", "Caja Premium", "Caja con Vidrio Templado",
                    "Caja ATX Gamer", "Caja con Ventiladores RGB", "Caja ATX Alta Gama", "Caja Full ATX", "Caja ATX con Filtros"
                ]
            },
            ultra: {
                cpu: [
                    "Intel Core i9-13900K", "AMD Ryzen 9 7950X", "Intel Core i9-12900KS", "AMD Ryzen 9 7900X", "Intel Core i9-13900KF",
                    "AMD Ryzen 9 5950X", "Intel Core i9-12900K", "AMD Ryzen 9 5900X", "Intel Core i9-11900K", "AMD Ryzen 9 5900"
                ],
                ram: [
                    "64GB DDR5 6000MHz", "64GB DDR5 5600MHz", "64GB DDR4 4000MHz", "64GB DDR5 6400MHz", "64GB DDR4 3600MHz",
                    "64GB DDR5 5200MHz", "64GB DDR4 3200MHz", "64GB DDR5 4800MHz", "64GB DDR4 3733MHz", "64GB DDR5 7200MHz"
                ],
                storage: [
                    "2TB NVMe SSD", "4TB NVMe SSD", "2TB SSD + 4TB HDD", "4TB SSD", "2TB SSD + 2TB HDD",
                    "4TB HDD", "2TB SSD", "4TB SSD + 4TB HDD", "2TB NVMe + 4TB HDD", "4TB NVMe + 4TB HDD"
                ],
                mb: [
                    "ASUS Z790 Hero", "Gigabyte X670E AORUS", "MSI MEG X670E ACE", "ASRock X670E Taichi", "ASUS ROG X670E",
                    "Gigabyte Z790 AORUS", "MSI Z790 Tomahawk", "ASRock Z790 Steel Legend", "ASUS ROG Z690", "Gigabyte Z690 AORUS"
                ],
                gpu: [
                    "NVIDIA RTX 4090", "AMD RX 7900 XTX", "NVIDIA RTX 4080", "AMD RX 7900 XT", "NVIDIA RTX 4070 Ti",
                    "AMD RX 7800 XT", "NVIDIA RTX 3090 Ti", "AMD RX 6950 XT", "NVIDIA RTX 3090", "AMD RX 6900 XT"
                ],
                psu: [
                    "1000W 80+ Platinum", "1200W 80+ Platinum", "1000W 80+ Gold", "1200W 80+ Gold", "1000W Modular",
                    "1200W Modular", "1000W 80+ Titanium", "1200W 80+ Titanium", "1100W 80+ Platinum", "1100W Modular"
                ],
                case: [
                    "Full Tower Premium", "Super Tower", "Caja con RGB Premium", "Caja con Vidrio Templado Premium", "Caja ATX Ultra",
                    "Caja con Refrigeración Líquida", "Caja Full ATX Premium", "Caja con Filtros Avanzados", "Caja Gamer Ultra", "Caja ATX XL"
                ]
            }
        };

        // Reemplazar los inputs de texto por selects con opciones y agregar botón "+" para RAM y almacenamiento
        document.addEventListener('DOMContentLoaded', function() {
            Object.entries(opcionesComponentes).forEach(([gama, componentes]) => {
                Object.entries(componentes).forEach(([campo, opciones]) => {
                    // Selección de todos los inputs (por si hay más de uno)
                    let inputs = Array.from(document.querySelectorAll(`form#ficha-${gama} input[name^="${campo}_${gama}"]`));
                    if (inputs.length === 0) return;

                    inputs.forEach((input, idx) => {
                        const select = document.createElement('select');
                        select.name = input.name;
                        select.className = input.className;
                        opciones.forEach(op => {
                            const option = document.createElement('option');
                            option.value = op;
                            option.textContent = op;
                            select.appendChild(option);
                        });
                        // Opción vacía
                        const emptyOption = document.createElement('option');
                        emptyOption.value = '';
                        emptyOption.textContent = '-- Seleccione --';
                        emptyOption.selected = true;
                        select.insertBefore(emptyOption, select.firstChild);

                        input.parentNode.replaceChild(select, input);
                    });

                    // Solo para RAM y almacenamiento, agrega botón "+"
                    // ...existing code...
                    if ((campo === 'ram' || campo === 'storage') && inputs.length === 1) {
                        const firstSelect = document.querySelector(`form#ficha-${gama} select[name="${campo}_${gama}"]`);
                        const label = firstSelect.parentNode.querySelector('label');
                        const addBtn = document.createElement('button');
                        addBtn.type = 'button';
                        addBtn.textContent = '+';
                        addBtn.title = 'Agregar otro';
                        // Botón pequeño, rojo, al lado derecho del label
                        addBtn.style.background = '#e74c3c';
                        addBtn.style.color = '#fff';
                        addBtn.style.border = 'none';
                        addBtn.style.borderRadius = '50%';
                        addBtn.style.width = '22px';
                        addBtn.style.height = '22px';
                        addBtn.style.fontSize = '14px';
                        addBtn.style.cursor = 'pointer';
                        addBtn.style.marginLeft = '8px';
                        addBtn.style.verticalAlign = 'middle';
                        addBtn.style.padding = '0';
                        addBtn.style.display = 'inline-block';

                        // Inserta el botón dentro del label, al final, para que quede a la derecha del texto
                        if (label && !label.querySelector('.ram-add-btn')) {
                            addBtn.classList.add('ram-add-btn');
                            label.appendChild(addBtn);
                        }

                        addBtn.addEventListener('click', function() {
                            const form = document.getElementById(`ficha-${gama}`);
                            const selects = form.querySelectorAll(`select[name^="${campo}_${gama}"]`);
                            if (selects.length >= 4) return;

                            const newGroup = document.createElement('div');
                            newGroup.className = 'form-group';
                            const newLabel = document.createElement('label');
                            newLabel.textContent = (campo === 'ram' ? 'Memoria RAM' : 'Almacenamiento') + ` #${selects.length + 1}`;
                            newGroup.appendChild(newLabel);

                            const newSelect = firstSelect.cloneNode(true);
                            newSelect.name = `${campo}_${gama}_${selects.length + 1}`;
                            newSelect.selectedIndex = 0;
                            newGroup.appendChild(newSelect);

                            // Botón para eliminar
                            const delBtn = document.createElement('button');
                            delBtn.type = 'button';
                            delBtn.textContent = '–';
                            delBtn.title = 'Quitar';
                            delBtn.style.background = '#e74c3c';
                            delBtn.style.color = '#fff';
                            delBtn.style.border = 'none';
                            delBtn.style.borderRadius = '50%';
                            delBtn.style.width = '22px';
                            delBtn.style.height = '22px';
                            delBtn.style.fontSize = '14px';
                            delBtn.style.cursor = 'pointer';
                            delBtn.style.marginLeft = '8px';
                            delBtn.style.verticalAlign = 'middle';
                            delBtn.style.padding = '0';
                            delBtn.style.display = 'inline-block';
                            delBtn.onclick = function() {
                                newGroup.remove();
                            };
                            newGroup.appendChild(delBtn);

                            let last = selects[selects.length - 1].parentNode;
                            last.parentNode.insertBefore(newGroup, last.nextSibling);
                        });
                    }
                    // ...existing code...
                });
            });


            // Tabs para cambiar entre fichas
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                    document.querySelectorAll('.ficha-form').forEach(f => f.classList.remove('active'));
                    btn.classList.add('active');
                    document.getElementById('ficha-' + btn.dataset.tab).classList.add('active');
                });
            });
            document.querySelectorAll('.btn-next').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const form = btn.closest('form');
                    // Obtén los valores seleccionados de los selects
                    const piezas = {};
                    form.querySelectorAll('select').forEach(sel => {
                        if (sel.value) piezas[sel.name] = sel.value;
                    });
                    // Muestra el formulario QA en un modal tipo PDF (simulado)
                    mostrarFormularioQA(piezas);
                });
            });

            function mostrarFormularioQA(piezas) {
                // Si ya existe el modal, elimínalo
                let oldModal = document.getElementById('qa-modal');
                if (oldModal) oldModal.remove();

                // Crea el modal
                const modal = document.createElement('div');
                modal.id = 'qa-modal';
                modal.style.position = 'fixed';
                modal.style.top = '0';
                modal.style.left = '0';
                modal.style.width = '100vw';
                modal.style.height = '100vh';
                modal.style.background = 'rgba(0,0,0,0.7)';
                modal.style.display = 'flex';
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
                modal.style.zIndex = '9999';

                // Contenido tipo "PDF"
                const content = document.createElement('div');
                content.style.background = '#f9f9f9';
                content.style.borderRadius = '12px';
                content.style.padding = '40px 32px';
                content.style.width = '95vw';
                content.style.maxWidth = '700px';
                content.style.maxHeight = '92vh';
                content.style.overflowY = 'auto';
                content.style.boxShadow = '0 8px 32px rgba(0,0,0,0.25)';
                content.style.fontFamily = 'Segoe UI, Arial, sans-serif';
                content.style.border = '2px solid #3a0ca3';

                content.innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
            <h2 style="color:#3a0ca3;margin:0;font-size:2rem;display:flex;align-items:center;gap:10px;">
                <i class="fas fa-clipboard-check"></i> Checklist QA de Ensamblaje
            </h2>
            <button type="button" id="qa-cerrar" style="background:#e74c3c;color:#fff;border:none;padding:8px 18px;border-radius:6px;cursor:pointer;font-size:1.1rem;">Cerrar</button>
        </div>
        <form id="qa-form" style="background:#fff;border-radius:8px;padding:24px;box-shadow:0 2px 8px rgba(58,12,163,0.08);">
            <div id="qa-items">
                ${Object.entries(piezas).map(([nombre, valor]) => `
                    <div class="qa-item" style="margin-bottom:22px;padding-bottom:12px;border-bottom:1px solid #ececec;">
                        <div style="font-weight:bold;color:#222;font-size:1.08rem;margin-bottom:6px;">
                            ${nombre.replace(/_.+/, '').toUpperCase()}: <span style="color:#3a0ca3">${valor}</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:18px;">
                            <label style="font-weight:500;"><input type="checkbox" name="ok_${nombre}" required style="margin-right:4px;"> OK</label>
                            <label style="font-weight:500;"><input type="checkbox" name="fail_${nombre}" style="margin-right:4px;"> Falla</label>
                            <input type="text" name="obs_${nombre}" placeholder="Observaciones" style="flex:1;padding:6px 10px;border-radius:5px;border:1px solid #ccc;">
                        </div>
                    </div>
                `).join('')}
            </div>
            <button type="button" id="qa-add-item" style="background:#4361ee;color:#fff;border:none;padding:7px 16px;border-radius:6px;cursor:pointer;margin-bottom:18px;display:block;margin-left:auto;margin-right:auto;">
                <i class="fas fa-plus"></i> Añadir opción de verificación
            </button>
            <div style="text-align:right;margin-top:18px;">
                <button type="submit" style="background:#3a0ca3;color:#fff;border:none;padding:10px 28px;border-radius:6px;cursor:pointer;font-size:1.1rem;">
                    <i class="fas fa-save"></i> Finalizar QA
                </button>
            </div>
        </form>
        `;

                modal.appendChild(content);
                document.body.appendChild(modal);

                // Cerrar modal
                document.getElementById('qa-cerrar').onclick = () => modal.remove();

                // Añadir opción de verificación extra
                document.getElementById('qa-add-item').onclick = function() {
                    const qaItems = document.getElementById('qa-items');
                    const idx = qaItems.querySelectorAll('.qa-item').length + 1;
                    const div = document.createElement('div');
                    div.className = 'qa-item';
                    div.style.marginBottom = '22px';
                    div.style.paddingBottom = '12px';
                    div.style.borderBottom = '1px solid #ececec';
                    div.innerHTML = `
            <div style="font-weight:bold;color:#222;font-size:1.08rem;margin-bottom:6px;">
                Opción extra #${idx}:
                <input type="text" name="extra_nombre_${idx}" placeholder="Nombre de la verificación" style="margin-left:8px;padding:4px 8px;border-radius:5px;border:1px solid #ccc;width:40%;">
            </div>
            <div style="display:flex;align-items:center;gap:18px;">
                <label style="font-weight:500;"><input type="checkbox" name="ok_extra_${idx}" required style="margin-right:4px;"> OK</label>
                <label style="font-weight:500;"><input type="checkbox" name="fail_extra_${idx}" style="margin-right:4px;"> Falla</label>
                <input type="text" name="obs_extra_${idx}" placeholder="Observaciones" style="flex:1;padding:6px 10px;border-radius:5px;border:1px solid #ccc;">
            </div>
        `;
                    qaItems.appendChild(div);
                };

                // ...dentro de mostrarFormularioQA...
                // Finalizar QA y guardar en la base de datos
                document.getElementById('qa-form').onsubmit = async function(e) {
                    e.preventDefault();

                    // Recolectar datos de QA
                    const form = e.target;
                    const formData = new FormData(form);
                    let checklist = [];
                    document.querySelectorAll('#qa-items .qa-item').forEach(item => {
                        let nombre = item.querySelector('input[name^="extra_nombre_"]') ?
                            item.querySelector('input[name^="extra_nombre_"]').value :
                            item.querySelector('div').textContent.split(':')[0].trim();
                        let ok = item.querySelector('input[type="checkbox"][name^="ok_"]').checked;
                        let fail = item.querySelector('input[type="checkbox"][name^="fail_"]').checked;
                        let obs = item.querySelector('input[type="text"][name^="obs_"]').value;
                        checklist.push({
                            nombre,
                            ok,
                            fail,
                            obs
                        });
                    });

                    // Datos de la PC ensamblada (de la ficha activa)
                    const fichaForm = document.querySelector('.ficha-form.active');
                    const cantidadInput = fichaForm.querySelector('input[name^="cantidad_"]');
                    const cantidad = cantidadInput ? parseInt(cantidadInput.value, 10) || 1 : 1;
                    const gama = fichaForm.id.replace('ficha-', '');
                    const usuario = "<?php echo $_SESSION['username']; ?>";
                    const obs = fichaForm.querySelector('textarea[name^="obs_"]').value;
                    const campos = {};
                    fichaForm.querySelectorAll('select').forEach(sel => {
                        campos[sel.name] = sel.value;
                    });

                    // Enviar por AJAX a PHP
                    const response = await fetch('guardar_pc_ensamblada.php', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            usuario,
                            gama,
                            campos,
                            cantidad,
                            observaciones: obs,
                            checklist_qa: checklist
                        })
                    });
                    const text = await response.text();
                    try {
                        const res = JSON.parse(text);
                        if (res.success) {
                            alert('PC ensamblada y QA guardados correctamente.');
                            modal.remove();
                            fichaForm.reset();
                        } else {
                            alert('Error al guardar: ' + (res.error || ''));
                        }
                    } catch (err) {
                        alert('Respuesta inesperada del servidor:\n' + text);
                    }
                };
            }
        });
    </script>
</body>

</html>