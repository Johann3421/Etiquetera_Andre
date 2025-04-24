<?php
    require_once '../config/database.php';
    require_once '../includes/auth.php';

    checkAuthentication();

    $db   = new Database();
    $conn = $db->connect();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recepción de Equipos | Sistema POS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
            background-color: #f5f7fa;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(to bottom, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .user-profile {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .user-profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 3px solid rgba(255,255,255,0.2);
        }

        /* Main content */
        main {
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.1);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        /* Formulario */
        .form-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            max-width: 800px;
            margin: 0 auto;
        }

        .form-logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 150px;

        }

        .form-title {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-dark);
            font-size: 1.5rem;
        }

        .form-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .form-section-title {
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
        }

        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .checkbox-group input {
            margin-right: 10px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
        }

        .btn-print {
            background: #6c757d;
            color: white;
        }

        .btn-print:hover {
            background: #5a6268;
        }

        @media (max-width: 768px) {
            body {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="user-profile">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username'])?>&background=random" alt="Perfil">
            <h3><?php echo htmlspecialchars($_SESSION['username'])?></h3>
        </div>

        <nav>
            <!-- Aquí podrías añadir más items de menú -->
        </nav>
    </aside>

    <!-- Main content -->
    <main>
        <div class="header">
            <h1>Sistema de Recepción</h1>
            <button class="logout-btn" onclick="window.location.href='logout.php'">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar sesión
            </button>
        </div>

        <div class="form-container">
            <!-- Logo y título -->
            <img src="../images/logo_almerco.png" alt="Logo" class="form-logo">
            <h2 class="form-title">FORMATO DE RECEPCIÓN DE EQUIPOS DE CÓMPUTO O IMPRESORAS</h2>

            <form id="recepcionForm">
                <!-- Fecha -->
                <div class="form-section">
                    <div class="form-group">
                        <label for="fecha">Fecha:</label>
                        <input type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d')?>" required>
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
                            <label for="equipo_modelo">Modelo:</label>
                            <input type="text" id="equipo_modelo" name="equipo_modelo" required>
                        </div>
                        <div class="form-group">
                            <label for="equipo_marca">Marca:</label>
                            <input type="text" id="equipo_marca" name="equipo_marca" required>
                        </div>
                        <div class="form-group">
                            <label for="equipo_so">Sistema Operativo:</label>
                            <input type="text" id="equipo_so" name="equipo_so">
                        </div>
                        <div class="form-group">
                            <label for="equipo_password">Contraseña:</label>
                            <input type="text" id="equipo_password" name="equipo_password">
                        </div>
                        <div class="form-group">
                            <label for="equipo_patron">Patrón:</label>
                            <input type="text" id="equipo_patron" name="equipo_patron">
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
                            <label for="costo">Costo Estimado:</label>
                            <input type="number" id="costo" name="costo" min="0" step="0.01" required>
                        </div>
                    </div>
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

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para imprimir el formulario en formato POS
    document.getElementById('printForm').addEventListener('click', function() {
        // Crear contenido optimizado para impresión
        const printContent = `
            <style>
                @page {
                    margin: 0;
                    padding: 0;
                }
                @media print {
                    body {
                        margin: 0;
                        padding: 0;
                        width: 80mm;
                    }
                    .pos-print {
                        width: 80mm;
                        padding: 2mm 5mm;
                        font-family: Arial, sans-serif;
                        font-size: 11px;
                        margin: 0;
                    }
                    .pos-header {
                        text-align: center;
                        margin-bottom: 5px;
                        padding-bottom: 3px;
                        border-bottom: 1px dashed #000;
                    }
                    .pos-logo {
                        max-width: 45mm;
                        margin: 0 auto 3px;
                        display: block;
                    }
                    .pos-title {
                        font-weight: bold;
                        font-size: 13px;
                        margin: 2px 0;
                    }
                    .pos-section {
                        margin-bottom: 5px;
                        padding-bottom: 3px;
                        border-bottom: 1px solid #eee;
                    }
                    .pos-section-title {
                        font-weight: bold;
                        font-size: 12px;
                        margin-bottom: 2px;
                    }
                    .pos-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 1px;
                    }
                    .pos-label {
                        font-weight: bold;
                        min-width: 30mm;
                    }
                    .pos-extras {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 3px;
                        margin-top: 2px;
                    }
                    .pos-checked {
                        background: #000;
                        color: #fff;
                        padding: 0 2px;
                        border-radius: 2px;
                        font-size: 10px;
                    }
                    .pos-footer {
                        margin-top: 5px;
                        padding-top: 3px;
                        border-top: 1px dashed #000;
                        text-align: center;
                        font-size: 10px;
                    }
                    .no-print {
                        display: none !important;
                    }
                }
            </style>
            <div class="pos-print">
                <div class="pos-header">
                    <img src="../images/logo_almerco.png" class="pos-logo" alt="Logo">
                    <div class="pos-title">RECEPCIÓN DE EQUIPOS</div>
                    <div>${document.getElementById('fecha').value}</div>
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
                        <span class="pos-label">Marca/Modelo:</span>
                        <span>${document.getElementById('equipo_marca').value} ${document.getElementById('equipo_modelo').value}</span>
                    </div>
                    <div class="pos-row">
                        <span class="pos-label">S.O.:</span>
                        <span>${document.getElementById('equipo_so').value || 'N/A'}</span>
                    </div>
                </div>

                <div class="pos-section">
                    <div class="pos-row">
                        <span class="pos-label">Diagnóstico:</span>
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
                </div>

                <div class="pos-section">
                    <div class="pos-row">
                        <span class="pos-label">Entrega:</span>
                        <span>${document.getElementById('fecha_entrega').value}</span>
                    </div>
                    <div class="pos-row">
                        <span class="pos-label">Costo:</span>
                        <span>$${parseFloat(document.getElementById('costo').value || 0).toFixed(2)}</span>
                    </div>
                </div>

                <div class="pos-footer">
                    <div>${new Date().toLocaleString()}</div>
                    <div>Atendido por: <?= htmlspecialchars($_SESSION['username']) ?></div>
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
    });

    // Validación del formulario
    document.getElementById('recepcionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Aquí iría la lógica para guardar los datos
        alert('Formulario guardado correctamente');
    });
});
</script>
</body>
</html>