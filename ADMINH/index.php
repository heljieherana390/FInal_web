<?php
// Database connection — inline so index.php works standalone outside ADMINH
$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "herana_pastry";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Herana Pastry | Exquisite Cakes & Cupcakes</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream:       #FAF6F0;
            --cream-dark:  #F2EBE0;
            --gold:        #C9973A;
            --gold-light:  #E8C98A;
            --gold-pale:   #F5E8CC;
            --brown:       #5C3A1E;
            --brown-deep:  #2E1A0A;
            --muted:       #8A7560;
            --border:      #E2D5C0;
            --white:       #FFFFFF;
        }
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--cream); color: var(--brown-deep); scroll-behavior: smooth; }

        /* LOADING */
        #loading-screen {
            position: fixed; inset: 0; background: var(--cream);
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            z-index: 9999; transition: opacity 0.6s ease;
        }
        #loading-screen.hide { opacity: 0; pointer-events: none; }
        .loader {
            width: 56px; height: 56px;
            border: 3px solid var(--gold-pale); border-top: 3px solid var(--gold);
            border-radius: 50%; animation: spin 0.9s linear infinite; margin-bottom: 24px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        #loading-screen h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px; font-style: italic;
            color: var(--brown); margin-bottom: 6px; letter-spacing: 0.5px;
        }
        #loading-screen p { font-size: 13px; color: var(--muted); letter-spacing: 0.5px; }

        /* NAV */
        nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 8%; height: 76px;
            background: rgba(250,246,240,0.92);
            backdrop-filter: blur(12px);
            position: sticky; top: 0; z-index: 1000;
            border-bottom: 1px solid var(--border);
        }
        .nav-logo img { height: 52px; object-fit: contain; }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .nav-links a.admin-btn {
            text-decoration: none;
            background: var(--brown); color: var(--white);
            padding: 10px 22px; border-radius: 50px;
            font-weight: 600; font-size: 14px; letter-spacing: 0.3px;
            transition: background 0.25s, transform 0.2s, box-shadow 0.2s;
        }
        .nav-links a.admin-btn:hover {
            background: var(--brown-deep);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(46,26,10,0.2);
        }

        /* HERO */
        .hero {
            min-height: 92vh;
            display: flex; align-items: center; justify-content: center;
            text-align: center;
            background: var(--cream);
            background-image:
                radial-gradient(ellipse at 20% 60%, rgba(201,151,58,0.10) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 30%, rgba(92,58,30,0.07) 0%, transparent 50%);
            padding: 80px 20px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -60px; left: 50%;
            transform: translateX(-50%);
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(201,151,58,0.07), transparent 65%);
            pointer-events: none;
        }
        .hero-tag {
            display: inline-block;
            background: var(--gold-pale);
            color: var(--gold);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            padding: 6px 18px;
            border-radius: 50px;
            border: 1px solid var(--gold-light);
            margin-bottom: 28px;
        }
        .hero-content h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 5rem;
            font-weight: 700;
            color: var(--brown);
            margin-bottom: 22px;
            line-height: 1.1;
            letter-spacing: -0.5px;
        }
        .hero-content h1 em {
            font-style: italic;
            color: var(--gold);
        }
        .hero-content p {
            font-size: 1.05rem;
            margin-bottom: 40px;
            color: var(--muted);
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
        }
        .btn-order {
            display: inline-block; background: var(--brown); color: var(--white);
            padding: 15px 42px; text-decoration: none; border-radius: 50px;
            font-weight: 600; font-size: 14px; letter-spacing: 0.5px;
            transition: 0.3s; box-shadow: 0 4px 20px rgba(46,26,10,0.2);
        }
        .btn-order:hover {
            background: var(--brown-deep);
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(46,26,10,0.28);
        }

        /* DIVIDER */
        .divider {
            text-align: center;
            padding: 12px 0;
            color: var(--gold-light);
            font-size: 1.2rem;
            letter-spacing: 12px;
        }

        /* FOOTER */
        footer {
            background: var(--brown-deep);
            text-align: center;
            padding: 60px 40px;
        }
        footer img { height: 70px; margin-bottom: 22px; object-fit: contain; opacity: 0.85; }
        footer p { color: rgba(255,255,255,0.45); margin-bottom: 5px; font-size: 13px; }
        .footer-divider {
            width: 40px; height: 1px;
            background: var(--gold);
            opacity: 0.4;
            margin: 20px auto;
        }
        .admin-link {
            display: inline-block; margin-top: 20px;
            font-size: 12px; color: rgba(255,255,255,0.25);
            text-decoration: none; transition: 0.3s;
        }
        .admin-link:hover { color: var(--gold-light); }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            nav { padding: 0 5%; }
            .hero-content h1 { font-size: 3rem; }
        }
    </style>
</head>
<body>

    <div id="loading-screen">
        <div class="loader"></div>
        <h2>Herana Pastry</h2>
        <p>Preparing something sweet for you...</p>
    </div>

    <nav>
        <div class="nav-logo">
            <img src="pas.webp" alt="Herana Pastry Logo">
        </div>
        <div class="nav-links">
            <a href="admin_login.php" class="admin-btn">Admin Login</a>
        </div>
    </nav>

    <header class="hero" id="home">
        <div class="hero-content">
            <div class="hero-tag">Artisan Pastry Shop</div>
            <h1>Love Every Bite<br>at <em>Herana</em> Pastry</h1>
            <p>Experience handcrafted cakes and cupcakes made with the finest ingredients — baked with heart, served with joy.</p>
            <a href="#about" class="btn-order">Discover More</a>
        </div>
    </header>

    <div class="divider">✦ ✦ ✦</div>

    <footer id="about">
        <img src="pas.webp" alt="Herana Pastry Logo">
        <div class="footer-divider"></div>
        <p>&copy; 2026 Herana Pastry Bakery. All rights reserved.</p>
        <p>123 Sweet Street, Dessert City</p>
        <a href="admin_login.php" class="admin-link">🔒 Staff Portal Access</a>
    </footer>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () {
                var loader = document.getElementById('loading-screen');
                loader.classList.add('hide');
                setTimeout(function () { loader.style.display = 'none'; }, 600);
            }, 2500);
        });
    </script>

</body>
</html>