<?php
session_start();

if (!isset($_SESSION['productos'])) {
    $_SESSION['productos'] = [];
}

$mensaje = "";
$mensajeTipo = "";

// CREATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id          = trim($_POST["id"]);
    $nombre      = trim($_POST["nombre"]);
    $descripcion = trim($_POST["descripcion"]);
    $precio      = trim($_POST["precio"]);
    $stock       = trim($_POST["stock"]);
    $categoria   = trim($_POST["categoria"]);

    if (empty($id) || empty($nombre) || empty($descripcion) || empty($precio) || empty($stock) || empty($categoria)) {
        $mensaje = "Todos los campos son obligatorios.";
        $mensajeTipo = "error";
    } elseif (!is_numeric($precio) || !is_numeric($stock)) {
        $mensaje = "Precio y Stock deben ser numéricos.";
        $mensajeTipo = "error";
    } elseif ($precio < 0 || $stock < 0) {
        $mensaje = "No se permiten valores negativos.";
        $mensajeTipo = "error";
    } else {
        foreach ($_SESSION['productos'] as $producto) {
            if ($producto['id'] == $id) {
                $mensaje = "El ID ya existe.";
                $mensajeTipo = "error";
                break;
            }
        }
        if ($mensaje == "") {
            $_SESSION['productos'][] = [
                "id"          => $id,
                "nombre"      => $nombre,
                "descripcion" => $descripcion,
                "precio"      => $precio,
                "stock"       => $stock,
                "categoria"   => $categoria
            ];
            $mensaje = "Producto agregado correctamente.";
            $mensajeTipo = "success";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Productos</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --emerald:  #00c896;
            --red:      #f87171;
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

        /* ── layout ──────────────────────────────────── */
        .page {
            position: relative;
            z-index: 1;
            max-width: 1280px;
            margin: 0 auto;
        }

        .split {
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 20px;
            align-items: stretch;
        }

        @media (max-width: 860px) {
            .split { grid-template-columns: 1fr; }
        }

        /* ── top nav ─────────────────────────────────── */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            animation: rise 0.5s cubic-bezier(0.22,1,0.36,1) both;
        }
        .topbar-left { display: flex; align-items: center; gap: 10px; }
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

        /* ── card shell ──────────────────────────────── */
        .card {
            background: var(--dark-2);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow);
            animation: rise 0.6s cubic-bezier(0.22,1,0.36,1) both;
            display: flex;
            flex-direction: column;
        }

        @keyframes rise {
            from { opacity:0; transform: translateY(20px); }
            to   { opacity:1; transform: translateY(0); }
        }

        .card-header {
            padding: 20px 26px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(90deg, rgba(0,200,150,0.04) 0%, transparent 60%);
        }
        .card-header h2 {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
        }
        .card-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: grid; place-items: center;
            font-size: 1rem;
            background: rgba(0,200,150,0.08);
            border: 1px solid rgba(0,200,150,0.15);
        }

        /* ── alert ───────────────────────────────────── */
        .alert {
            margin: 16px 26px 0;
            padding: 11px 16px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-error   { background: rgba(248,113,113,0.10); border: 1px solid rgba(248,113,113,0.25); color: #fca5a5; }
        .alert-success { background: rgba(0,200,150,0.08);  border: 1px solid rgba(0,200,150,0.22);  color: var(--emerald); }

        /* ── form ────────────────────────────────────── */
        .form-body {
            padding: 22px 26px 26px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group label {
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.08em;
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
        }
        .form-group input:focus {
            border-color: rgba(0,200,150,0.35);
            box-shadow: 0 0 0 3px rgba(0,200,150,0.08);
        }
        .form-group input::placeholder { color: rgba(125,143,163,0.4); }

        .form-footer {
            margin-top: 18px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        .btn-submit {
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
        .btn-submit:hover {
            background: rgba(0,200,150,0.18);
            box-shadow: 0 0 16px rgba(0,200,150,0.12);
        }

        /* ── table ───────────────────────────────────── */
        .table-wrap {
            overflow-x: auto;
            overflow-y: auto;
            flex: 1;
            max-height: 520px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        thead tr {
            border-bottom: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
        }
        thead th {
            padding: 13px 18px;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            white-space: nowrap;
        }
        tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.15s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,0.025); }

        tbody td {
            padding: 13px 18px;
            color: var(--text);
            vertical-align: middle;
        }

        /* id pill */
        .td-id {
            font-family: monospace;
            font-size: 0.82rem;
            color: var(--muted);
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            padding: 3px 8px;
            border-radius: 5px;
            display: inline-block;
        }

        /* stock badge */
        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .stock-ok   { background: rgba(0,200,150,0.08);  border: 1px solid rgba(0,200,150,0.18);  color: var(--emerald); }
        .stock-low  { background: rgba(245,183,49,0.08); border: 1px solid rgba(245,183,49,0.22); color: #f5b731; }
        .stock-zero { background: rgba(248,113,113,0.08);border: 1px solid rgba(248,113,113,0.22);color: #f87171; }

        /* price */
        .td-price { color: #fff; font-weight: 600; }
        .td-price::before { content: '$'; color: var(--muted); font-weight: 400; margin-right: 1px; }

        /* category chip */
        .cat-chip {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 5px;
            font-size: 0.78rem;
            font-weight: 500;
            background: var(--dark-4);
            border: 1px solid var(--border);
            color: var(--muted);
        }

        /* actions */
        .action-links { display: flex; align-items: center; gap: 10px; }
        .action-links a {
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            padding: 5px 12px;
            border-radius: 7px;
            border: 1px solid transparent;
            transition: background 0.15s, border-color 0.15s;
        }
        .link-edit {
            color: var(--muted);
            border-color: var(--border);
            background: transparent;
        }
        .link-edit:hover { color: var(--text); background: var(--dark-3); }
        .link-delete {
            color: #f87171;
            border-color: rgba(248,113,113,0.18);
            background: rgba(248,113,113,0.06);
        }
        .link-delete:hover { background: rgba(248,113,113,0.12); }

        /* empty state */
        .empty-state {
            padding: 60px 24px;
            text-align: center;
            color: var(--muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            height: 100%;
        }
        .empty-state .empty-icon {
            font-size: 3rem;
            margin-bottom: 14px;
            opacity: 0.3;
            display: block;
        }
        .empty-state p { font-size: 0.95rem; letter-spacing: 0.01em; }

        /* count pill in header */
        .count-pill {
            margin-left: auto;
            font-size: 0.78rem;
            font-weight: 600;
            background: rgba(0,200,150,0.07);
            border: 1px solid rgba(0,200,150,0.15);
            color: var(--emerald);
            padding: 3px 12px;
            border-radius: 999px;
        }
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

        <!-- top bar -->
        <div class="topbar">
            <div class="topbar-left">
                <a class="back-btn" href="index.php">← Inicio</a>
                <h1 class="page-title">Gestionar <em>Productos</em></h1>
            </div>
        </div>

        <!-- cards side by side -->
        <div class="split">

        <!-- form card -->
        <div class="card" style="animation-delay:0.05s">
            <div class="card-header">
                <div class="card-icon">➕</div>
                <h2>Agregar nuevo producto</h2>
            </div>

            <?php if ($mensaje !== ""): ?>
            <div class="alert alert-<?php echo $mensajeTipo; ?>">
                <?php echo $mensajeTipo === 'error' ? '⚠' : '✓'; ?>
                <?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <?php endif; ?>

            <div class="form-body">
                <form method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="id">ID</label>
                            <input type="text" id="id" name="id" placeholder="ej. P001">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" placeholder="Nombre del producto">
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <input type="text" id="descripcion" name="descripcion" placeholder="Descripción breve">
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio ($)</label>
                            <input type="text" id="precio" name="precio" placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="text" id="stock" name="stock" placeholder="Unidades disponibles">
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <input type="text" id="categoria" name="categoria" placeholder="ej. Electrónica">
                        </div>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn-submit">+ Agregar producto</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- table card -->
        <div class="card" style="animation-delay:0.12s">
            <div class="card-header">
                <div class="card-icon">📦</div>
                <h2>Inventario</h2>
                <span class="count-pill"><?php echo count($_SESSION['productos']); ?> productos</span>
            </div>

            <div class="table-wrap">
                <?php if (empty($_SESSION['productos'])): ?>
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <p>No hay productos por el momento</p>
                </div>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Categoría</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['productos'] as $producto): ?>
                        <tr>
                            <td><span class="td-id"><?php echo htmlspecialchars($producto['id'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                            <td><?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td style="color:var(--muted)"><?php echo htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="td-price"><?php echo htmlspecialchars($producto['precio'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <?php
                                    $s = (int)$producto['stock'];
                                    $cls = $s === 0 ? 'stock-zero' : ($s <= 5 ? 'stock-low' : 'stock-ok');
                                ?>
                                <span class="stock-badge <?php echo $cls; ?>"><?php echo htmlspecialchars($producto['stock'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </td>
                            <td><span class="cat-chip"><?php echo htmlspecialchars($producto['categoria'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                            <td>
                                <div class="action-links">
                                    <a class="link-edit" href="editar.php?id=<?php echo urlencode($producto['id']); ?>">Editar</a>
                                    <a class="link-delete" href="eliminar.php?id=<?php echo urlencode($producto['id']); ?>">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        </div>

    </div>
</body>
</html>