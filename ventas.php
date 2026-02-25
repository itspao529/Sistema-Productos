<?php
session_start();

$mensaje = "";
$mensajeTipo = "";
$vendidoId = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id       = $_POST["id"];
    $cantidad = $_POST["cantidad"];
    $vendidoId = $id;

    if (!is_numeric($cantidad) || $cantidad <= 0) {
        $mensaje = "Cantidad inválida.";
        $mensajeTipo = "error";
    } else {
        foreach ($_SESSION['productos'] as $key => $producto) {
            if ($producto['id'] == $id) {
                if ($producto['stock'] >= $cantidad) {
                    $_SESSION['productos'][$key]['stock'] -= $cantidad;
                    $mensaje = "Venta registrada correctamente.";
                    $mensajeTipo = "success";
                    $vendidoId = "";
                } else {
                    $mensaje = "Stock insuficiente para completar la venta.";
                    $mensajeTipo = "error";
                }
                break;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Venta</title>
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

        /* ── boxes ───────────────────────────────────── */
        .boxes-scene {
            position: fixed; bottom: 0; left: 0;
            z-index: 0; pointer-events: none;
        }
        .box {
            position: absolute; border-radius: 5px;
            border: 1px solid rgba(255,255,255,0.07);
            background: rgba(255,255,255,0.025);
        }
        .box::before {
            content: ''; position: absolute;
            top: 50%; left: 15%; width: 70%; height: 1px;
            background: rgba(255,255,255,0.055); transform: translateY(-50%);
        }
        .box::after {
            content: ''; position: absolute;
            left: 50%; top: 15%; height: 70%; width: 1px;
            background: rgba(255,255,255,0.055); transform: translateX(-50%);
        }
        .b1{width:80px;height:68px;bottom:0;left:0;animation:bf 7s ease-in-out 0s infinite}
        .b2{width:80px;height:68px;bottom:0;left:74px;animation:bf 7s ease-in-out .3s infinite}
        .b3{width:80px;height:68px;bottom:0;left:148px;animation:bf 7s ease-in-out .6s infinite}
        .b4{width:80px;height:68px;bottom:62px;left:8px;animation:bf 8s ease-in-out .2s infinite}
        .b5{width:80px;height:68px;bottom:62px;left:82px;animation:bf 8s ease-in-out .5s infinite}
        .b6{width:80px;height:68px;bottom:124px;left:16px;animation:bf 9s ease-in-out .1s infinite}
        .b7{width:80px;height:68px;bottom:124px;left:90px;animation:bf 9s ease-in-out .4s infinite}
        .b8{width:80px;height:68px;bottom:186px;left:24px;animation:bf 10s ease-in-out .3s infinite}
        .boxes-fade{
            position:absolute;bottom:0;left:-10px;right:-10px;height:55px;
            background:linear-gradient(to top,var(--dark),transparent);z-index:10;
        }
        @keyframes bf{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}

        /* ── page ────────────────────────────────────── */
        .page {
            position: relative; z-index: 1;
            max-width: 1100px; margin: 0 auto;
            animation: rise .6s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes rise{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}

        /* ── topbar ──────────────────────────────────── */
        .topbar {
            display: flex; align-items: center;
            gap: 12px; margin-bottom: 24px;
        }
        .back-btn {
            display: inline-flex; align-items: center; gap: 6px;
            text-decoration: none; color: var(--muted); font-size: .85rem;
            padding: 7px 14px; border-radius: 8px;
            border: 1px solid var(--border); background: var(--dark-2);
            transition: color .2s, border-color .2s;
        }
        .back-btn:hover { color: var(--text); border-color: rgba(255,255,255,.15); }
        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem; font-weight: 800; color: #fff;
        }
        .page-title em { font-style: normal; color: var(--emerald); }

        /* ── layout ──────────────────────────────────── */
        .layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px; align-items: start;
        }
        @media(max-width:860px){ .layout{ grid-template-columns: 1fr; } }

        /* ── card ────────────────────────────────────── */
        .card {
            background: var(--dark-2);
            border: 1px solid var(--border);
            border-radius: 18px; overflow: hidden;
            box-shadow: var(--shadow);
            display: flex; flex-direction: column;
        }
        .card-header {
            padding: 18px 24px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
            background: linear-gradient(90deg, rgba(0,200,150,0.04) 0%, transparent 60%);
        }
        .card-icon {
            width: 32px; height: 32px; border-radius: 8px;
            display: grid; place-items: center; font-size: 1rem;
            background: rgba(0,200,150,0.08); border: 1px solid rgba(0,200,150,0.15);
            flex-shrink: 0;
        }
        .card-header h2 {
            font-family: 'Syne', sans-serif;
            font-size: .95rem; font-weight: 700; color: #fff;
        }
        .count-pill {
            margin-left: auto; font-size: .78rem; font-weight: 600;
            background: rgba(0,200,150,0.07); border: 1px solid rgba(0,200,150,0.15);
            color: var(--emerald); padding: 3px 12px; border-radius: 999px;
        }

        /* ── alert ───────────────────────────────────── */
        .alert {
            margin: 14px 24px 0; padding: 10px 14px; border-radius: 9px;
            font-size: .86rem; font-weight: 500;
            display: flex; align-items: center; gap: 8px;
        }
        .alert-error  { background:rgba(248,113,113,.10); border:1px solid rgba(248,113,113,.25); color:#fca5a5; }
        .alert-success{ background:rgba(0,200,150,.08);  border:1px solid rgba(0,200,150,.22);  color:var(--emerald); }

        /* ── product table ───────────────────────────── */
        .table-wrap {
            overflow-x: auto; overflow-y: auto;
            max-height: 480px; flex: 1;
        }
        table { width: 100%; border-collapse: collapse; font-size: .9rem; }
        thead tr {
            border-bottom: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
            position: sticky; top: 0; z-index: 2;
        }
        thead th {
            padding: 12px 16px; text-align: left;
            font-size: .72rem; font-weight: 600;
            letter-spacing: .1em; text-transform: uppercase;
            color: var(--muted); white-space: nowrap;
            background: var(--dark-2);
        }
        /* select col */
        thead th:first-child, tbody td:first-child {
            width: 48px; text-align: center;
        }

        tbody tr {
            border-bottom: 1px solid rgba(255,255,255,.04);
            transition: background .15s;
            cursor: pointer;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,.025); }
        tbody tr.selected {
            background: rgba(0,200,150,.07);
            border-color: rgba(0,200,150,.15);
        }
        tbody td { padding: 12px 16px; vertical-align: middle; }

        /* radio button styled */
        .row-radio {
            width: 18px; height: 18px;
            accent-color: var(--emerald);
            cursor: pointer;
        }

        .td-id {
            font-family: monospace; font-size: .82rem; color: var(--muted);
            background: rgba(255,255,255,.04); border: 1px solid var(--border);
            padding: 3px 8px; border-radius: 5px; display: inline-block;
        }
        .td-price { color: #fff; font-weight: 600; }
        .td-price::before { content: '$'; color: var(--muted); font-weight: 400; }

        .stock-badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 999px;
            font-size: .8rem; font-weight: 600;
        }
        .stock-ok  { background:rgba(0,200,150,.08); border:1px solid rgba(0,200,150,.18); color:var(--emerald); }
        .stock-low { background:rgba(245,183,49,.08);border:1px solid rgba(245,183,49,.22);color:#f5b731; }
        .stock-zero{ background:rgba(248,113,113,.08);border:1px solid rgba(248,113,113,.22);color:#f87171; }

        .cat-chip {
            display: inline-block; padding: 3px 10px; border-radius: 5px;
            font-size: .78rem; font-weight: 500;
            background: var(--dark-4); border: 1px solid var(--border); color: var(--muted);
        }

        /* empty */
        .empty-state {
            padding: 60px 24px; text-align: center; color: var(--muted);
            display: flex; flex-direction: column; align-items: center; gap: 12px;
        }
        .empty-state .empty-icon { font-size: 2.8rem; opacity: .3; }
        .empty-state p { font-size: .92rem; }

        /* ── sell panel (right) ───────────────────────── */
        .sell-body { padding: 20px 24px 24px; }

        .selected-preview {
            background: var(--dark-3); border: 1px solid var(--border);
            border-radius: 12px; padding: 16px;
            margin-bottom: 18px; min-height: 80px;
            display: flex; flex-direction: column; gap: 8px;
        }
        .selected-preview.empty-sel {
            align-items: center; justify-content: center;
            color: var(--muted); font-size: .85rem; text-align: center;
        }
        .sel-name {
            font-family: 'Syne', sans-serif;
            font-size: 1rem; font-weight: 700; color: #fff;
        }
        .sel-meta {
            display: flex; gap: 8px; flex-wrap: wrap; align-items: center;
        }
        .sel-price { color: var(--emerald); font-weight: 600; font-size: .92rem; }
        .sel-stock-label { font-size: .78rem; color: var(--muted); }

        /* hidden select used by the form */
        #hidden-id { display: none; }

        .form-group {
            display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px;
        }
        .form-group label {
            font-size: .72rem; font-weight: 600;
            letter-spacing: .1em; text-transform: uppercase; color: var(--muted);
        }
        .form-group input {
            background: var(--dark-3); border: 1px solid var(--border);
            border-radius: 9px; padding: 10px 13px;
            font-size: .92rem; font-family: 'DM Sans', sans-serif;
            color: var(--text); outline: none;
            transition: border-color .2s, box-shadow .2s; width: 100%;
        }
        .form-group input:focus {
            border-color: rgba(0,200,150,.35);
            box-shadow: 0 0 0 3px rgba(0,200,150,.08);
        }
        .form-group input::placeholder { color: rgba(125,143,163,.35); }

        .btn-sell {
            width: 100%;
            display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            background: rgba(0,200,150,.12); border: 1px solid rgba(0,200,150,.3);
            color: var(--emerald); padding: 11px 22px; border-radius: 9px;
            font-size: .95rem; font-weight: 600; font-family: 'DM Sans', sans-serif;
            cursor: pointer; transition: background .2s, box-shadow .2s;
        }
        .btn-sell:hover {
            background: rgba(0,200,150,.18);
            box-shadow: 0 0 16px rgba(0,200,150,.12);
        }
        .btn-sell:disabled {
            opacity: .4; cursor: not-allowed;
            box-shadow: none; background: rgba(0,200,150,.06);
        }

        .sell-hint {
            margin-top: 14px; padding: 10px 14px; border-radius: 9px;
            background: rgba(0,200,150,.04); border: 1px solid rgba(0,200,150,.10);
            font-size: .8rem; color: var(--muted); line-height: 1.55;
        }
        .sell-hint strong { color: var(--emerald); }
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
            <a class="back-btn" href="index.php">← Inicio</a>
            <h1 class="page-title">Registrar <em>Venta</em></h1>
        </div>

        <?php if ($mensaje !== ""): ?>
        <div class="alert alert-<?php echo $mensajeTipo; ?>" style="margin-bottom:18px; margin-left:0; margin-right:0;">
            <?php echo $mensajeTipo === 'error' ? '⚠' : '✓'; ?>
            <?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <?php endif; ?>

        <div class="layout">

            <!-- LEFT: product table -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">📦</div>
                    <h2>Productos disponibles</h2>
                    <span class="count-pill"><?php echo count($_SESSION['productos']); ?> productos</span>
                </div>

                <div class="table-wrap">
                    <?php if (empty($_SESSION['productos'])): ?>
                    <div class="empty-state">
                        <span class="empty-icon">📦</span>
                        <p>No hay productos por el momento</p>
                    </div>
                    <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Categoría</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['productos'] as $producto): ?>
                            <?php
                                $s   = (int)$producto['stock'];
                                $cls = $s === 0 ? 'stock-zero' : ($s <= 5 ? 'stock-low' : 'stock-ok');
                            ?>
                            <tr class="product-row <?php echo $producto['id'] === $vendidoId ? 'selected' : ''; ?>"
                                data-id="<?php echo htmlspecialchars($producto['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-nombre="<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-precio="<?php echo htmlspecialchars($producto['precio'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-stock="<?php echo htmlspecialchars($producto['stock'], ENT_QUOTES, 'UTF-8'); ?>"
                                data-disabled="<?php echo $s === 0 ? '1' : '0'; ?>">
                                <td>
                                    <input class="row-radio" type="radio" name="row_select"
                                           value="<?php echo htmlspecialchars($producto['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                           <?php echo $producto['id'] === $vendidoId ? 'checked' : ''; ?>
                                           <?php echo $s === 0 ? 'disabled' : ''; ?>>
                                </td>
                                <td><span class="td-id"><?php echo htmlspecialchars($producto['id'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                <td style="font-weight:500"><?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td style="color:var(--muted)"><?php echo htmlspecialchars($producto['descripcion'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="td-price"><?php echo htmlspecialchars($producto['precio'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><span class="stock-badge <?php echo $cls; ?>"><?php echo htmlspecialchars($producto['stock'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                                <td><span class="cat-chip"><?php echo htmlspecialchars($producto['categoria'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RIGHT: sell panel -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">💰</div>
                    <h2>Confirmar venta</h2>
                </div>

                <div class="sell-body">
                    <form method="POST" id="sell-form">
                        <input type="hidden" name="id" id="hidden-id" value="">

                        <!-- selected product preview -->
                        <div class="selected-preview empty-sel" id="sel-preview">
                            <span style="font-size:1.4rem;opacity:.3">☝️</span>
                            <span>Selecciona un producto<br>de la tabla</span>
                        </div>

                        <div class="form-group">
                            <label for="cantidad">Cantidad a vender</label>
                            <input type="number" name="cantidad" id="cantidad"
                                   placeholder="0" min="1" value="">
                        </div>

                        <button type="submit" class="btn-sell" id="btn-sell" disabled>
                            💰 Registrar venta
                        </button>
                    </form>

                    <div class="sell-hint">
                        <strong>Cómo usar:</strong> Selecciona una fila de la tabla, ingresa la cantidad deseada y confirma la venta. El stock se actualizará automáticamente.
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const rows      = document.querySelectorAll('.product-row');
        const hiddenId  = document.getElementById('hidden-id');
        const preview   = document.getElementById('sel-preview');
        const btnSell   = document.getElementById('btn-sell');
        const cantInput = document.getElementById('cantidad');

        function selectRow(row) {
            if (row.dataset.disabled === '1') return;

            // deselect all
            rows.forEach(r => {
                r.classList.remove('selected');
                r.querySelector('.row-radio').checked = false;
            });

            row.classList.add('selected');
            row.querySelector('.row-radio').checked = true;

            const id     = row.dataset.id;
            const nombre = row.dataset.nombre;
            const precio = row.dataset.precio;
            const stock  = parseInt(row.dataset.stock);

            hiddenId.value = id;
            cantInput.max  = stock;
            btnSell.disabled = false;

            const stockCls = stock === 0 ? 'stock-zero' : (stock <= 5 ? 'stock-low' : 'stock-ok');

            preview.classList.remove('empty-sel');
            preview.innerHTML = `
                <span class="sel-name">${nombre}</span>
                <div class="sel-meta">
                    <span class="sel-price">$${precio}</span>
                    <span class="sel-stock-label">·</span>
                    <span class="stock-badge ${stockCls}">${stock} en stock</span>
                </div>
                <span style="font-size:.78rem;color:var(--muted);font-family:monospace">ID: ${id}</span>
            `;

            cantInput.focus();
        }

        rows.forEach(row => {
            row.addEventListener('click', () => selectRow(row));
        });

        // also handle radio click directly
        document.querySelectorAll('.row-radio').forEach(radio => {
            radio.addEventListener('click', e => {
                e.stopPropagation();
                const row = radio.closest('.product-row');
                selectRow(row);
            });
        });
    </script>

</body>
</html>