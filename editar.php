<?php
session_start();

$id = $_GET['id'];
$productoEncontrado = null;
$mensaje = "";
$mensajeTipo = "";

foreach ($_SESSION['productos'] as $key => $producto) {
    if ($producto['id'] == $id) {
        $productoEncontrado = $producto;
        $index = $key;
        break;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre      = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio      = trim($_POST["precio"]);
    $stock       = trim($_POST["stock"]);
    $categoria   = trim($_POST["categoria"]);

    if (empty($nombre) || empty($descripcion) || empty($precio) || empty($stock) || empty($categoria)) {
        $mensaje = "Todos los campos son obligatorios.";
        $mensajeTipo = "error";
    } elseif (!is_numeric($precio) || !is_numeric($stock)) {
        $mensaje = "Precio y Stock deben ser numéricos.";
        $mensajeTipo = "error";
    } elseif ($precio < 0 || $stock < 0) {
        $mensaje = "No se permiten valores negativos.";
        $mensajeTipo = "error";
    } else {
        $_SESSION['productos'][$index] = [
            "id"          => $id,
            "nombre"      => $nombre,
            "descripcion" => $descripcion,
            "precio"      => $precio,
            "stock"       => $stock,
            "categoria"   => $categoria
        ];
        header("Location: productos.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --emerald:  #00c896;
            --dark:     #0d1117;
            --dark-2:   #161b22;
            --dark-3:   #1c2433;
            --dark-4:   #212d3d;
            --text:     #e6edf3;
            --muted:    #7d8fa3;
            --border:   rgba(255,255,255,0.07);
            --shadow:   0 24px 60px rgba(0,0,0,0.6);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            background: var(--dark);
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 44px 44px;
            display: grid;
            place-items: center;
            padding: 32px 20px 60px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            width: 500px; height: 300px;
            top: -120px; left: 50%;
            transform: translateX(-50%);
            background: radial-gradient(ellipse, rgba(0,200,150,0.07), transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── stacked boxes (bottom-left) ─────────────── */
        .boxes-scene {
            position: fixed;
            bottom: 0; left: 0;
            z-index: 0;
            pointer-events: none;
        }
        .box {
            position: absolute;
            border-radius: 5px;
            border: 1px solid rgba(255,255,255,0.07);
            background: rgba(255,255,255,0.025);
        }
        .box::before {
            content: '';
            position: absolute;
            top: 50%; left: 15%; width: 70%; height: 1px;
            background: rgba(255,255,255,0.055);
            transform: translateY(-50%);
        }
        .box::after {
            content: '';
            position: absolute;
            left: 50%; top: 15%; height: 70%; width: 1px;
            background: rgba(255,255,255,0.055);
            transform: translateX(-50%);
        }
        .b1 { width:80px; height:68px; bottom:0;     left:0;    animation: bf 7s  ease-in-out 0.0s infinite; }
        .b2 { width:80px; height:68px; bottom:0;     left:74px; animation: bf 7s  ease-in-out 0.3s infinite; }
        .b3 { width:80px; height:68px; bottom:0;     left:148px;animation: bf 7s  ease-in-out 0.6s infinite; }
        .b4 { width:80px; height:68px; bottom:62px;  left:8px;  animation: bf 8s  ease-in-out 0.2s infinite; }
        .b5 { width:80px; height:68px; bottom:62px;  left:82px; animation: bf 8s  ease-in-out 0.5s infinite; }
        .b6 { width:80px; height:68px; bottom:124px; left:16px; animation: bf 9s  ease-in-out 0.1s infinite; }
        .b7 { width:80px; height:68px; bottom:124px; left:90px; animation: bf 9s  ease-in-out 0.4s infinite; }
        .b8 { width:80px; height:68px; bottom:186px; left:24px; animation: bf 10s ease-in-out 0.3s infinite; }
        .boxes-fade {
            position: absolute;
            bottom: 0; left: -10px; right: -10px; height: 55px;
            background: linear-gradient(to top, var(--dark), transparent);
            z-index: 10;
        }
        @keyframes bf {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-4px); }
        }

        /* ── page wrapper ────────────────────────────── */
        .page {
            position: relative;
            z-index: 1;
            width: min(820px, 100%);
            animation: rise 0.6s cubic-bezier(0.22,1,0.36,1) both;
        }
        @keyframes rise {
            from { opacity:0; transform: translateY(22px); }
            to   { opacity:1; transform: translateY(0); }
        }

        /* ── topbar ──────────────────────────────────── */
        .topbar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: var(--muted);
            font-size: 0.85rem;
            padding: 7px 14px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--dark-2);
            transition: color 0.2s, border-color 0.2s;
        }
        .back-btn:hover { color: var(--text); border-color: rgba(255,255,255,0.15); }
        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
        }
        .page-title em { font-style: normal; color: var(--emerald); }

        /* ── main layout: two columns ────────────────── */
        .layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            align-items: start;
        }
        @media (max-width: 680px) {
            .layout { grid-template-columns: 1fr; }
        }

        /* ── card ────────────────────────────────────── */
        .card {
            background: var(--dark-2);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(90deg, rgba(0,200,150,0.04) 0%, transparent 60%);
        }
        .card-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: grid; place-items: center;
            font-size: 1rem;
            background: rgba(0,200,150,0.08);
            border: 1px solid rgba(0,200,150,0.15);
            flex-shrink: 0;
        }
        .card-header h2 {
            font-family: 'Syne', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #fff;
        }

        /* ── alert ───────────────────────────────────── */
        .alert {
            margin: 14px 24px 0;
            padding: 10px 14px;
            border-radius: 9px;
            font-size: 0.86rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-error { background: rgba(248,113,113,0.10); border: 1px solid rgba(248,113,113,0.25); color: #fca5a5; }

        /* ── form ────────────────────────────────────── */
        .form-body { padding: 20px 24px 24px; }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 14px;
        }
        .form-group:last-of-type { margin-bottom: 0; }

        .form-group label {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .form-group input {
            background: var(--dark-3);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 10px 13px;
            font-size: 0.92rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        .form-group input:focus {
            border-color: rgba(0,200,150,0.35);
            box-shadow: 0 0 0 3px rgba(0,200,150,0.08);
        }
        .form-group input:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }
        .form-group input::placeholder { color: rgba(125,143,163,0.35); }

        .form-footer {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }
        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(0,200,150,0.12);
            border: 1px solid rgba(0,200,150,0.3);
            color: var(--emerald);
            padding: 10px 22px;
            border-radius: 9px;
            font-size: 0.92rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-save:hover {
            background: rgba(0,200,150,0.18);
            box-shadow: 0 0 16px rgba(0,200,150,0.12);
        }

        /* ── info card (right side) ──────────────────── */
        .info-body { padding: 20px 24px 24px; }

        .info-id {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--dark-3);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 10px 16px;
            margin-bottom: 20px;
            width: 100%;
        }
        .info-id-label {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
        }
        .info-id-value {
            font-family: monospace;
            font-size: 1rem;
            color: #fff;
            font-weight: 600;
            margin-left: auto;
            background: rgba(0,200,150,0.08);
            border: 1px solid rgba(0,200,150,0.18);
            padding: 2px 10px;
            border-radius: 5px;
            color: var(--emerald);
        }

        /* current values preview */
        .preview-title {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 12px;
        }

        .preview-grid {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .preview-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 9px 14px;
            background: var(--dark-3);
            border: 1px solid var(--border);
            border-radius: 9px;
            font-size: 0.88rem;
        }
        .preview-row-key {
            color: var(--muted);
            font-size: 0.8rem;
        }
        .preview-row-val {
            color: var(--text);
            font-weight: 500;
            text-align: right;
            max-width: 55%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* stock pill on preview */
        .stock-ok   { color: #00c896; }
        .stock-low  { color: #f5b731; }
        .stock-zero { color: #f87171; }

        /* hint below preview */
        .info-hint {
            margin-top: 16px;
            padding: 10px 14px;
            border-radius: 9px;
            background: rgba(0,200,150,0.05);
            border: 1px solid rgba(0,200,150,0.12);
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.55;
        }
        .info-hint strong { color: var(--emerald); font-weight: 600; }
    </style>
</head>
<body>

    <div class="boxes-scene" aria-hidden="true">
        <div class="box b1"></div><div class="box b2"></div><div class="box b3"></div>
        <div class="box b4"></div><div class="box b5"></div><div class="box b6"></div>
        <div class="box b7"></div><div class="box b8"></div>
        <div class="boxes-fade"></div>
    </div>

    <div class="page">

        <div class="topbar">
            <a class="back-btn" href="productos.php">← Productos</a>
            <h1 class="page-title">Editar <em>Producto</em></h1>
        </div>

        <div class="layout">

            <!-- LEFT: edit form -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">✏️</div>
                    <h2>Modificar datos</h2>
                </div>

                <?php if ($mensaje !== ""): ?>
                <div class="alert alert-error">
                    ⚠ <?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <?php endif; ?>

                <div class="form-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>ID (no editable)</label>
                            <input type="text" value="<?php echo htmlspecialchars($productoEncontrado['id'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre"
                                   value="<?php echo htmlspecialchars($productoEncontrado['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <input type="text" id="descripcion" name="descripcion"
                                   value="<?php echo htmlspecialchars($productoEncontrado['descripcion'], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio ($)</label>
                            <input type="text" id="precio" name="precio"
                                   value="<?php echo htmlspecialchars($productoEncontrado['precio'], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="text" id="stock" name="stock"
                                   value="<?php echo htmlspecialchars($productoEncontrado['stock'], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <input type="text" id="categoria" name="categoria"
                                   value="<?php echo htmlspecialchars($productoEncontrado['categoria'], ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn-save">✓ Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT: current values preview -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">📋</div>
                    <h2>Valores actuales</h2>
                </div>

                <div class="info-body">

                    <div class="info-id">
                        <span class="info-id-label">ID del producto</span>
                        <span class="info-id-value"><?php echo htmlspecialchars($productoEncontrado['id'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>

                    <p class="preview-title">Datos registrados</p>

                    <div class="preview-grid">
                        <div class="preview-row">
                            <span class="preview-row-key">Nombre</span>
                            <span class="preview-row-val"><?php echo htmlspecialchars($productoEncontrado['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        <div class="preview-row">
                            <span class="preview-row-key">Descripción</span>
                            <span class="preview-row-val"><?php echo htmlspecialchars($productoEncontrado['descripcion'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        <div class="preview-row">
                            <span class="preview-row-key">Precio</span>
                            <span class="preview-row-val" style="color:#fff; font-weight:600;">
                                $<?php echo htmlspecialchars($productoEncontrado['precio'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </div>
                        <div class="preview-row">
                            <span class="preview-row-key">Stock</span>
                            <span class="preview-row-val <?php
                                $s = (int)$productoEncontrado['stock'];
                                echo $s === 0 ? 'stock-zero' : ($s <= 5 ? 'stock-low' : 'stock-ok');
                            ?>">
                                <?php echo htmlspecialchars($productoEncontrado['stock'], ENT_QUOTES, 'UTF-8'); ?> unidades
                            </span>
                        </div>
                        <div class="preview-row">
                            <span class="preview-row-key">Categoría</span>
                            <span class="preview-row-val"><?php echo htmlspecialchars($productoEncontrado['categoria'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    </div>

                    <div class="info-hint">
                        <strong>Tip:</strong> Los valores a la derecha muestran los datos <em>antes</em> de guardar. Al hacer clic en "Guardar cambios" serás redirigido al inventario.
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>
</html>