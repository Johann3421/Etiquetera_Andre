<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

checkAuthentication();

$db   = new Database();
$conn = $db->connect();
date_default_timezone_set('America/Lima');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recepción de Equipos | Sistema POS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Llama tu CSS externo -->
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <?php include '../public/layouts/sidebar.php'; ?>

    <div class="main-content">
        <?php include '../public/layouts/header.php'; ?>

        <div class="form-container">
            <img src="/images/logo_almerco.png" alt="Logo" class="form-logo">
            <h2 class="form-title">FORMATO DE RECEPCIÓN DE EQUIPOS DE CÓMPUTO O IMPRESORAS</h2>
            <form id="recepcionForm">
                <!-- Apartado para editar código generado automáticamente -->
                <div class="form-section">
                    <h3 class="form-section-title">EDITAR CÓDIGO GENERADO AUTOMÁTICAMENTE</h3>
                    <div class="form-group">
                        <label for="codigoGenerado">Código Generado:</label>
                        <input type="text" id="codigoGenerado" name="codigoGenerado" class="form-control" placeholder="Código generado automáticamente" value="">
                    </div>
                </div>

                <!-- Fecha y hora -->
                <div class="form-section">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="fecha">Fecha:</label>
                            <input type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="hora_recibido">Hora Recibido:</label>
                            <input type="time" id="hora_recibido" name="hora_recibido" value="<?php echo date('H:i') ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Datos del cliente -->
                <div class="form-section">
                    <h3 class="form-section-title">DATOS DEL CLIENTE</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="cliente_nombre">Nombre:</label>
                            <input type="text" id="cliente_nombre" name="cliente_nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="cliente_telefono">Teléfono:</label>
                            <input type="tel" id="cliente_telefono" name="cliente_telefono" required>
                        </div>
                    </div>
                </div>

                <!-- Datos del equipo -->
                <div class="form-section">
                    <h3 class="form-section-title">DATOS DEL EQUIPO</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="equipo_tipo">Equipo:</label>
                            <input type="text" id="equipo_tipo" name="equipo_tipo" placeholder="Ej. Laptop, Impresora" required>
                        </div>
                        <div class="form-group">
                            <label for="equipo_password">Contraseña:</label>
                            <input type="text" id="equipo_password" name="equipo_password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="diagnostico">Diagnóstico Inicial:</label>
                        <input type="text" id="diagnostico" name="diagnostico" required>
                    </div>
                </div>

                <!-- Extras -->
                <div class="form-section">
                    <h3 class="form-section-title">EXTRAS</h3>
                    <div class="form-grid">
                        <div class="checkbox-group">
                            <input type="checkbox" id="extra_cargador" name="extra_cargador">
                            <label for="extra_cargador">Cargador</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="extra_bateria" name="extra_bateria">
                            <label for="extra_bateria">Batería</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="extra_bolsa" name="extra_bolsa">
                            <label for="extra_bolsa">Bolsa</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="extra_estuche" name="extra_estuche">
                            <label for="extra_estuche">Estuche</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="extra_cable" name="extra_cable">
                            <label for="extra_cable">Cable de Poder</label>
                        </div>
                    </div>
                    <button type="button" onclick="agregarCampo()" class="btn btn-secondary">
                        <i class="fas fa-plus"></i> Añadir Campo Extra
                    </button>
                    <div id="camposExtras"></div>
                </div>

                <!-- Observaciones -->
                <div class="form-section">
                    <h3 class="form-section-title">OBSERVACIONES</h3>
                    <div class="form-group">
                        <label for="observaciones">Detalles adicionales:</label>
                        <textarea id="observaciones" name="observaciones"></textarea>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="fecha_entrega">Fecha de Entrega:</label>
                            <input type="date" id="fecha_entrega" name="fecha_entrega" required>
                        </div>
                        <div class="form-group">
                            <label for="hora_entrega">Hora de Entrega:</label>
                            <input type="time" id="hora_entrega" name="hora_entrega" required>
                        </div>
                        <div class="form-group">
                            <label for="costo">Costo Estimado:</label>
                            <input type="number" id="costo" name="costo" min="0" step="0.01" required>
                        </div>
                    </div>
                </div>

                <!-- Cámara -->
                <div class="form-section">
                    <h3 class="form-section-title">CÁMARA</h3>
                    <button type="button" onclick="activarCamara()" class="btn btn-secondary">
                        <i class="fas fa-camera"></i> Activar Cámara
                    </button>
                    <video id="camera" autoplay playsinline width="320" height="240" style="display:none;"></video>
                    <canvas id="snapshot" style="display:none;"></canvas>
                    <button type="button" onclick="capturarFoto()" class="btn btn-secondary">
                        <i class="fas fa-camera"></i> Capturar Foto
                    </button>
                    <input type="hidden" name="foto_url" id="foto_url">
                </div>

                <!-- Acciones -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Recepción
                    </button>
                    <button type="button" id="printForm" class="btn btn-print">
                        <i class="fas fa-print"></i> Imprimir Formulario
                    </button>
                </div>
            </form>
        </div>
        </main>

        <!-- Scripts generales -->
    <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Generar un código ascendente automáticamente
                function generarCodigo() {
                    // Obtener el último código almacenado en el localStorage o iniciar desde 00001A
                    let ultimoCodigo = localStorage.getItem('ultimoCodigo') || '00000A';

                    // Incrementar el código
                    let numero = parseInt(ultimoCodigo.slice(0, 5)); // Extraer los primeros 5 dígitos
                    let letra = ultimoCodigo.slice(5); // Extraer la letra final

                    numero++; // Incrementar el número

                    // Si el número supera 99999, reiniciar y avanzar la letra
                    if (numero > 99999) {
                        numero = 0;
                        letra = String.fromCharCode(letra.charCodeAt(0) + 1); // Avanzar a la siguiente letra
                        if (letra > 'Z') letra = 'A'; // Reiniciar a 'A' si supera 'Z'
                    }

                    // Formatear el nuevo código
                    const nuevoCodigo = numero.toString().padStart(5, '0') + letra;

                    // Guardar el nuevo código en localStorage
                    localStorage.setItem('ultimoCodigo', nuevoCodigo);

                    return nuevoCodigo;
                }

                // Asignar el código generado al campo "Código Generado"
                const codigoGeneradoInput = document.getElementById('codigoGenerado');
                if (codigoGeneradoInput) {
                    codigoGeneradoInput.value = generarCodigo();
                }
            });
            document.addEventListener('DOMContentLoaded', function() {
                // Añadir selector de tipo de moneda dinámicamente
                const costoGroup = document.querySelector('.form-group input[name="costo"]').parentNode;
                const monedaSelect = document.createElement('div');
                monedaSelect.className = 'form-group';
                monedaSelect.innerHTML = `
        <label for="tipo_moneda">Moneda:</label>
        <select id="tipo_moneda" name="tipo_moneda" class="form-control">
            <option value="S/">Soles (S/)</option>
            <option value="$">Dólares ($)</option>
        </select>
            `;
                costoGroup.parentNode.insertBefore(monedaSelect, costoGroup.nextSibling);

                // Validación del formulario
                document.getElementById('recepcionForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    fetch('guardar_recepcion.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert('Guardado exitosamente');
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(err => {
                            alert('Error de conexión');
                        });
                });

            });
            window.agregarCampo = function() {
                let extrasContainer = document.getElementById('camposExtras');
                if (!extrasContainer) {
                    extrasContainer = document.createElement('div');
                    extrasContainer.id = 'camposExtras';
                    document.getElementById('recepcionForm').appendChild(extrasContainer);
                }
                const div = document.createElement('div');
                div.className = 'form-group';
                div.innerHTML = `
        <label>Campo Extra:</label>
        <input type="text" name="extras[]" placeholder="Ingrese valor extra" class="form-control">
        `;
                extrasContainer.appendChild(div);
            }
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.getElementById('camera');
                const canvas = document.getElementById('snapshot');
                const fotoUrlInput = document.getElementById('foto_url');
                let stream = null;

                // Activar la cámara
                window.activarCamara = function() {
                    navigator.mediaDevices.getUserMedia({
                            video: true
                        })
                        .then(mediaStream => {
                            stream = mediaStream;
                            video.srcObject = stream;
                            video.style.display = 'block'; // Mostrar el video
                        })
                        .catch(err => {
                            console.error("No se puede acceder a la cámara", err);
                            alert("Error al activar la cámara. Verifica los permisos.");
                        });
                };

                // Capturar foto y enviarla al servidor
                window.capturarFoto = function() {
                    if (!stream) {
                        alert("Primero activa la cámara.");
                        return;
                    }

                    const ctx = canvas.getContext('2d');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    ctx.drawImage(video, 0, 0);

                    canvas.toBlob(blob => {
                        const formData = new FormData();
                        formData.append('foto', blob, 'captura.png');

                        fetch('subir_foto.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    fotoUrlInput.value = data.path; // Guardar la ruta en el campo oculto
                                    alert('Foto capturada y guardada exitosamente.');
                                } else {
                                    alert('Error al guardar la foto: ' + data.message);
                                }
                            })
                            .catch(err => {
                                alert('Error al subir la foto.');
                                console.error(err);
                            });
                    });
                };
            });
        </script>

        <!-- Script para imprimir el formulario -->
        <script>
document.getElementById('printForm').addEventListener('click', function () {
    const form = document.getElementById('recepcionForm');
    const simboloMoneda = document.getElementById('tipo_moneda').value;

    // Obtener los campos extras generados dinámicamente
    const extrasContainer = document.getElementById('camposExtras');
    let extrasHTML = '';
    if (extrasContainer) {
        const extraFields = extrasContainer.querySelectorAll('input[name="extras[]"]');
        extraFields.forEach((field, index) => {
            extrasHTML += `
                <div class="pos-row">
                    <span class="pos-label">Extra ${index + 1}:</span>
                    <span>${field.value || 'N/A'}</span>
                </div>
            `;
        });
    }

    // Guardar automáticamente antes de imprimir
    const formData = new FormData(form);
    fetch('guardar_recepcion.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Si guardó correctamente, imprimir
            const printContent = `
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: 80mm auto;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
                width: 80mm;
                font-family: 'Roboto', 'Helvetica', 'Arial', sans-serif;
            }
            .pos-print {
                width: 72mm;
                padding: 1.5mm;
                margin: 0 auto;
                font-size: 11.5px;
                line-height: 1.3;
            }
            .pos-header {
                text-align: center;
                margin-bottom: 3px;
                padding-bottom: 3px;
                border-bottom: 1px dashed #000;
            }
            .pos-logo {
                max-width: 50mm;
                margin: 0 auto 2px;
                display: block;
            }
            .pos-title {
                font-weight: bold;
                font-size: 13px;
                margin: 2px 0;
            }
            .pos-section {
                margin-bottom: 4px;
                padding-bottom: 2px;
                page-break-inside: avoid;
            }
            .pos-section-title {
                font-weight: bold;
                font-size: 11.5px;
                margin-bottom: 2px;
                background: #f0f0f0;
                padding: 2px 3px;
            }
            .pos-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 2px;
                page-break-inside: avoid;
            }
            .pos-label {
                font-weight: bold;
                min-width: 25mm;
            }
            .pos-extras {
                display: flex;
                flex-wrap: wrap;
                gap: 2px;
                margin: 3px 0;
            }
            .pos-checked {
                background: none !important;
                color: #000 !important;
                font-weight: bold !important;
                border: none !important;
                padding: 2px 4px !important;
                font-size: 11px !important;
                text-transform: uppercase;
                display: inline-block;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            .pos-observaciones {
                margin-top: 4px;
                font-size: 10px;
                border: 1px dashed #aaa;
                padding: 3px;
                background: #f9f9f9;
                min-height: 20px;
            }
            .pos-footer {
                margin-top: 4px;
                padding-top: 2px;
                border-top: 1px dashed #000;
                text-align: center;
                font-size: 11px;
                font-weight: bold;
                page-break-inside: avoid;
            }
            .no-print {
                display: none !important;
            }
            .pos-info {
                text-align: center;
                font-size: 11px;
                font-weight: bold;
                margin-bottom: 2px;
                text-transform: uppercase;
            }
        }
    </style>

    <div class="pos-print">
        <div class="pos-header">
            <img src="/images/logo_almerco.png" alt="Logo" class="pos-logo">
            <div class="pos-info">RUC: 20601687977</div>
            <div class="pos-info">JR.HUALLAYCO NRO 1135( A 1/2 CDRA DEL COLEGIO LEONCIO PRADO).</div>
            <div class="pos-info">HUANUCO-HUANUCO-HUANUCO.</div>
            <div class="pos-info">CEL 991375813.</div>
            <div class="pos-title">RECEPCIÓN DE EQUIPOS</div>
            <div>${document.getElementById('fecha').value} ${document.getElementById('hora_recibido').value}</div>
        </div>

        <div class="pos-section">
            <div class="pos-row">
                <span class="pos-label">Código:</span>
                <span>${document.getElementById('codigoGenerado').value || 'N/A'}</span>
            </div>
        </div>

        <div class="pos-section">
            <div class="pos-row">
                <span class="pos-label">Cliente:</span>
                <span>${document.getElementById('cliente_nombre').value}</span>
            </div>
            <div class="pos-row">
                <span class="pos-label">Teléfono:</span>
                <span>${document.getElementById('cliente_telefono').value}</span>
            </div>
        </div>

        <div class="pos-section">
            <div class="pos-section-title">EQUIPO</div>
            <div class="pos-row">
                <span class="pos-label">Tipo:</span>
                <span>${document.getElementById('equipo_tipo').value}</span>
            </div>
            <div class="pos-row">
                <span class="pos-label">Contraseña:</span>
                <span>${document.getElementById('equipo_password').value || 'N/A'}</span>
            </div>
        </div>

        <div class="pos-section">
            <div class="pos-section-title">DIAGNÓSTICO</div>
            <div class="pos-row">
                <span>${document.getElementById('diagnostico').value}</span>
            </div>
        </div>

        <div class="pos-section">
            <div class="pos-section-title">EXTRAS</div>
            <div class="pos-extras">
                ${document.getElementById('extra_cargador').checked ? '<span class="pos-checked">Cargador</span>' : ''}
                ${document.getElementById('extra_bateria').checked ? '<span class="pos-checked">Batería</span>' : ''}
                ${document.getElementById('extra_bolsa').checked ? '<span class="pos-checked">Bolsa</span>' : ''}
                ${document.getElementById('extra_estuche').checked ? '<span class="pos-checked">Estuche</span>' : ''}
                ${document.getElementById('extra_cable').checked ? '<span class="pos-checked">Cable</span>' : ''}
            </div>
            ${extrasHTML}
        </div>

        <div class="pos-section">
            <div class="pos-section-title">OBSERVACIONES</div>
            <div class="pos-observaciones">
                ${document.getElementById('observaciones').value || 'Ninguna'}
            </div>
        </div>

        <div class="pos-section">
            <div class="pos-section-title">ENTREGA</div>
            <div class="pos-row">
                <span class="pos-label">Fecha/Hora:</span>
                <span>${document.getElementById('fecha_entrega').value} ${document.getElementById('hora_entrega').value}</span>
            </div>
            <div class="pos-row">
                <span class="pos-label">Costo Estimado:</span>
                <span>${simboloMoneda}${parseFloat(document.getElementById('costo').value || 0).toFixed(2)}</span>
            </div>
        </div>

        <div class="pos-footer">
            <div>${new Date().toLocaleString()}</div>
            <div>Atendido por: <?php echo htmlspecialchars($_SESSION['username']) ?></div>
        </div>
    </div>
    `;

            // Crear ventana de impresión
            const printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(`
        <!DOCTYPE html>
        <html>
            <head>
                <title>Recibo de Recepción</title>
                <style>
                    body {
                        margin: 0;
                        padding: 0;
                    }
                </style>
            </head>
            <body style="margin: 0; padding: 0;">
                ${printContent}
                <script>
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                            setTimeout(function() {
                                window.close();
                            }, 100);
                        }, 50);
                    };
                <\/script>
            </body>
        </html>
        `);
            printWindow.document.close();
        } else {
            alert('Error al guardar antes de imprimir: ' + (data.message || 'Intente nuevamente.'));
        }
    })
    .catch(err => {
        alert('Error de conexión al guardar antes de imprimir.');
    });
});
</script>
</body>

</html>