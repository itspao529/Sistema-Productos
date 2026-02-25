<?php
session_start();

if (!isset($_SESSION['productos']) || !is_array($_SESSION['productos'])) {
    $_SESSION['productos'] = [];
}

$totalProductos = count($_SESSION['productos']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Productos</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --emerald:   #00c896;
            --dark:      #0d1117;
            --dark-2:    #161b22;
            --dark-3:    #1c2433;
            --text:      #e6edf3;
            --muted:     #7d8fa3;
            --border:    rgba(255,255,255,0.07);
            --shadow:    0 24px 60px rgba(0,0,0,0.6);
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
            padding: 24px 16px;
            position: relative;
            overflow: hidden;
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
            top: 50%; left: 15%;
            width: 70%; height: 1px;
            background: rgba(255,255,255,0.055);
            transform: translateY(-50%);
        }
        .box::after {
            content: '';
            position: absolute;
            left: 50%; top: 15%;
            height: 70%; width: 1px;
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
            bottom: 0; left: -10px; right: -10px;
            height: 55px;
            background: linear-gradient(to top, var(--dark), transparent);
            z-index: 10;
        }

        @keyframes bf {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-4px); }
        }

        .container {
            position: relative;
            z-index: 1;
            width: min(860px, 100%);
            border-radius: 20px;
            border: 1px solid var(--border);
            background: var(--dark-2);
            box-shadow: var(--shadow);
            overflow: hidden;
            animation: rise 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes rise {
            from { opacity:0; transform: translateY(24px); }
            to   { opacity:1; transform: translateY(0); }
        }

        .header {
            position: relative;
            padding: 40px 36px 36px;
            border-bottom: 1px solid var(--border);
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(0,200,150,0.05) 0%, transparent 55%);
            pointer-events: none;
        }

        .header-inner { position: relative; z-index: 1; }

        .header-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--emerald);
            margin-bottom: 14px;
            opacity: 0.8;
        }
        .header-eyebrow span {
            display: inline-block;
            width: 20px; height: 1.5px;
            background: var(--emerald);
            border-radius: 2px;
            opacity: 0.6;
        }

        .header h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(1.7rem, 3vw, 2.4rem);
            font-weight: 800;
            line-height: 1.18;
            color: #fff;
            letter-spacing: -0.02em;
        }
        .header h1 em {
            font-style: normal;
            color: var(--emerald);
        }

        .header-sub {
            margin-top: 10px;
            color: var(--muted);
            font-size: 0.95rem;
            max-width: 400px;
            line-height: 1.6;
        }

        .content { padding: 28px 36px 38px; }

        .stat-row { margin-bottom: 28px; }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0,200,150,0.07);
            border: 1px solid rgba(0,200,150,0.15);
            color: var(--emerald);
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 0.88rem;
            font-weight: 600;
        }
        .badge-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--emerald);
            opacity: 0.75;
            animation: pulse 2.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%,100% { opacity: 0.75; transform: scale(1); }
            50%      { opacity: 0.35; transform: scale(0.7); }
        }

        .actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 14px;
        }

        .action {
            position: relative;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px 22px;
            text-decoration: none;
            color: inherit;
            background: var(--dark-3);
            overflow: hidden;
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            animation: rise 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .action:nth-child(1) { animation-delay: 0.08s; }
        .action:nth-child(2) { animation-delay: 0.16s; }

        .action:hover {
            transform: translateY(-3px);
            border-color: rgba(0,200,150,0.2);
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
        }

        .action::after {
            content: '';
            position: absolute;
            top: 0; left: 25%; right: 25%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0,200,150,0.4), transparent);
            opacity: 0;
            transition: opacity 0.25s ease;
        }
        .action:hover::after { opacity: 1; }

        .action-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            font-size: 1.2rem;
            margin-bottom: 14px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
        }

        .action h2 {
            margin: 0 0 7px;
            font-family: 'Syne', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
        }

        .action p {
            margin: 0;
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.55;
        }

        .action-arrow {
            position: absolute;
            bottom: 20px; right: 18px;
            font-size: 0.95rem;
            color: var(--muted);
            opacity: 0;
            transform: translate(-4px, 4px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .action:hover .action-arrow { opacity: 1; transform: translate(0,0); }


        .footer-strip {
            padding: 12px 36px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            color: rgba(125,143,163,0.4);
            letter-spacing: 0.04em;
        }
        .footer-strip .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--emerald);
            opacity: 0.55;
        }
    </style>
</head>
<body>

    <div class="boxes-scene" aria-hidden="true">
        <div class="box b1"></div>
        <div class="box b2"></div>
        <div class="box b3"></div>
        <div class="box b4"></div>
        <div class="box b5"></div>
        <div class="box b6"></div>
        <div class="box b7"></div>
        <div class="box b8"></div>
        <div class="boxes-fade"></div>
    </div>

    <main class="container">

        <header class="header">
            <div class="header-inner">
                <div class="header-eyebrow"><span></span> Panel de Control</div>
                <h1>Sistema de <em>Gestión</em><br>de Productos</h1>
                <p class="header-sub">Panel principal para administrar inventario y registrar ventas en tiempo real.</p>
            </div>
        </header>

        <section class="content">
            <div class="stat-row">
                <div class="badge">
                    <div class="badge-dot"></div>
                    Productos registrados: <?php echo htmlspecialchars($totalProductos, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>

            <div class="actions">
                <a class="action" href="productos.php">
                    <div class="action-icon">📦</div>
                    <h2>Gestionar Productos</h2>
                    <p>Crear, editar y eliminar productos del inventario.</p>
                    <span class="action-arrow">→</span>
                </a>
                <a class="action" href="ventas.php">
                    <div class="action-icon">💰</div>
                    <h2>Registrar Venta</h2>
                    <p>Registrar ventas de forma rápida y sencilla.</p>
                    <span class="action-arrow">→</span>
                </a>
            </div>
        </section>
    </main>
</body>
</html>