<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

checkAuthentication();

$db = new Database();
$conn = $db->connect();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sistema POS</title>
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        body {
            display: grid;
            grid-template-columns: var(--sidebar-width) 1fr;
            min-height: 100vh;
            background: #f5f7fa;
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
        
        /* Botón de cerrar sesión */
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
            transition: var(--transition);
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.2);
        }
        
        /* Formulario POS */
        .pos-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 25px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .boleta-preview {
            background: white;
            border: 1px solid #e9ecef;
            padding: 20px;
            margin-top: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            display: none;
        }
        
        .item-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }
        
        @media (max-width: 768px) {
            body {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="user-profile">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username']) ?>&background=random" alt="Perfil">
            <h3><?= htmlspecialchars($_SESSION['username']) ?></h3>
        </div>
        
        <nav>
            <!-- Aquí podrías añadir más items de menú -->
        </nav>
    </aside>

    <!-- Main content -->
    <main>
        <div class="header">
            <h1>Sistema POS</h1>
            <button class="logout-btn" onclick="window.location.href='logout.php'">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar sesión
            </button>
        </div>
        
        <div class="pos-container">
            <form id="posForm">
                <div class="form-group">
                    <label for="cliente">Cliente:</label>
                    <input type="text" id="cliente" name="cliente" placeholder="Nombre del cliente" required>
                </div>
                
                <h3>Ítems de venta</h3>
                <div class="items-container">
                    <div class="item-row">
                        <input type="text" name="items[0][descripcion]" placeholder="Descripción" required>
                        <input type="number" name="items[0][cantidad]" placeholder="Cantidad" min="1" required>
                        <input type="number" name="items[0][precio]" placeholder="Precio" min="0" step="0.01" required>
                        <button type="button" class="remove-item btn" style="background: var(--danger-color); width: 40px;">×</button>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" id="addItem" class="btn" style="background: var(--secondary-color); margin-right: 10px;">
                        <i class="fas fa-plus"></i> Agregar ítem
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <button type="button" id="printBoleta" class="btn" style="background: #6c757d; float: right;">
                        <i class="fas fa-print"></i> Imprimir Boleta
                    </button>
                </div>
            </form>

            <div id="boletaPreview" class="boleta-preview">
                <h2 style="text-align: center; margin-bottom: 20px;">Boleta de Venta</h2>
                <p><strong>Cliente:</strong> <span id="boletaCliente"></span></p>
                <p><strong>Fecha:</strong> <span id="boletaFecha"><?= date('d/m/Y H:i') ?></span></p>
                <hr>
                <div id="boletaItems"></div>
                <hr>
                <p style="text-align: right; font-weight: bold; font-size: 1.2rem;">
                    Total: $<span id="boletaTotal">0.00</span>
                </p>
            </div>
        </div>
    </main>

    <script src="assets/js/auth.js"></script>
    <!-- Font Awesome para iconos -->
</body>
</html>