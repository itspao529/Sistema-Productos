<?php
session_start();

$id = $_GET['id'] ?? '';
$productoEncontrado = null;

foreach ($_SESSION['productos'] as $key => $producto) {
    if ($producto['id'] == $id) {
        $productoEncontrado = $producto;
        break;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmar'])) {
    foreach ($_SESSION['productos'] as $key => $producto) {
        if ($producto['id'] == $id) {
            unset($_SESSION['productos'][$key]);
            break;
        }
    }
    header("Location: productos.php");
    exit();
}

// If product not found → redirect
if (!$productoEncontrado) {
    header("Location: productos.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Producto</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --emerald:  #00c896;
            --red:      #f87171;
            --red-dk:   #dc2626;
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
            padding: 32px 20px;
            position: relative;
            overflow: hidden;
        }

        /* red glow top */
        body::before {
            content: '';
            position: fixed;
            width: 500px; height: 280px;
            top: -130px; left: 50%;
            transform: translateX(-50%);
            background: radial-gradient(ellipse, rgba(248,113,113,0.09), transparent 70%);
            pointer-events: none; z-index: 0;
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

        /* ── card ────────────────────────────────────── */
        .card {
            position: relative; z-index: 1;
            width: min(480px, 100%);
            background: var(--dark-2);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            animation: rise .6s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes rise{from{opacity:0;transform:translateY(22px) scale(.98)}to{opacity:1;transform:translateY(0) scale(1)}}

        /* red top accent bar */
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 10%; right: 10%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(248,113,113,0.7), transparent);
            border-radius: 0 0 4px 4px;
        }

        /* ── warning header ──────────────────────────── */
        .warn-header {
            padding: 36px 32px 24px;
            text-align: center;
            border-bottom: 1px solid var(--border);
            background: linear-gradient(180deg, rgba(248,113,113,0.06) 0%, transparent 100%);
        }

        .warn-icon-wrap {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: rgba(248,113,113,0.10);
            border: 1px solid rgba(248,113,113,0.25);
            display: grid; place-items: center;
            margin: 0 auto 18px;
            font-size: 1.8rem;
            animation: pulse-red 2.5s ease-in-out infinite;
        }
        @keyframes pulse-red {
            0%,100% { box-shadow: 0 0 0 0 rgba(248,113,113,0); }
            50%      { box-shadow: 0 0 0 8px rgba(248,113,113,0.08); }
        }

        .warn-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.35rem; font-weight: 800;
            color: #fff; margin-bottom: 8px;
        }
        .warn-title em { font-style: normal; color: var(--red); }

        .warn-sub {
            color: var(--muted); font-size: .9rem; line-height: 1.55;
        }

        /* ── product preview ─────────────────────────── */
        .product-preview {
            margin: 20px 28px;
            background: var(--dark-3);
            border: 1px solid rgba(248,113,113,0.15);
            border-radius: 12px;
            padding: 16px 18px;
            display: flex; flex-direction: column; gap: 10px;
        }

        .preview-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: .88rem;
        }
        .preview-key {
            color: var(--muted);
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .preview-val { color: var(--text); font-weight: 500; }
        .preview-val.id-val {
            font-family: monospace;
            background: rgba(255,255,255,.04);
            border: 1px solid var(--border);
            padding: 2px 8px; border-radius: 5px;
            font-size: .82rem; color: var(--muted);
        }

        .stock-ok  { color: #00c896; }
        .stock-low { color: #f5b731; }
        .stock-zero{ color: #f87171; }

        /* ── actions ─────────────────────────────────── */
        .actions {
            padding: 0 28px 28px;
            display: flex; gap: 10px;
        }

        .btn-cancel {
            flex: 1;
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            text-decoration: none;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--muted);
            padding: 11px 18px; border-radius: 9px;
            font-size: .92rem; font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: color .2s, border-color .2s, background .2s;
        }
        .btn-cancel:hover {
            color: var(--text);
            border-color: rgba(255,255,255,.15);
            background: rgba(255,255,255,.03);
        }

        .btn-delete {
            flex: 1;
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            background: rgba(248,113,113,.12);
            border: 1px solid rgba(248,113,113,.35);
            color: var(--red);
            padding: 11px 18px; border-radius: 9px;
            font-size: .92rem; font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background .2s, box-shadow .2s;
        }
        .btn-delete:hover {
            background: rgba(248,113,113,.20);
            box-shadow: 0 0 20px rgba(248,113,113,.15);
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

    <div class="card">

        <div class="warn-header">
            <div class="warn-icon-wrap">🗑️</div>
            <h1 class="warn-title">¿Eliminar <em>producto</em>?</h1>
            <p class="warn-sub">Esta acción no se puede deshacer.<br>El producto será removido del inventario permanentemente.</p>
        </div>

        <!-- product details -->
        <div class="product-preview">
            <div class="preview-row">
                <span class="preview-key">ID</span>
                <span class="preview-val id-val"><?php echo htmlspecialchars($productoEncontrado['id'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="preview-row">
                <span class="preview-key">Nombre</span>
                <span class="preview-val"><?php echo htmlspecialchars($productoEncontrado['nombre'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="preview-row">
                <span class="preview-key">Precio</span>
                <span class="preview-val" style="color:#fff;font-weight:600;">
                    $<?php echo htmlspecialchars($productoEncontrado['precio'], ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </div>
            <div class="preview-row">
                <span class="preview-key">Stock</span>
                <?php
                    $s = (int)$productoEncontrado['stock'];
                    $cls = $s === 0 ? 'stock-zero' : ($s <= 5 ? 'stock-low' : 'stock-ok');
                ?>
                <span class="preview-val <?php echo $cls; ?>">
                    <?php echo htmlspecialchars($productoEncontrado['stock'], ENT_QUOTES, 'UTF-8'); ?> unidades
                </span>
            </div>
            <div class="preview-row">
                <span class="preview-key">Categoría</span>
                <span class="preview-val"><?php echo htmlspecialchars($productoEncontrado['categoria'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        </div>

        <!-- action buttons -->
        <div class="actions">
            <a class="btn-cancel" href="productos.php">← Cancelar</a>
            <form method="POST" style="flex:1;display:flex;">
                <button type="submit" name="confirmar" value="1" class="btn-delete" style="width:100%;">
                    🗑️ Sí, eliminar
                </button>
            </form>
        </div>

    </div>

</body>
</html>