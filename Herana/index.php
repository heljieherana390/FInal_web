<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Herana Pastry | Handcrafted Pastries</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <style>
        body { overflow: hidden; }
        body.ls-revealed { overflow: auto; }

        #loading-screen {
            position: fixed;
            inset: 0;
            background: #FAF6F0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            overflow: hidden;
            transition: opacity 0.65s ease;
        }
        #loading-screen.ls-hide {
            opacity: 0;
            pointer-events: none;
        }
        #loading-screen::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.035'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }
        .ls-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.2;
            animation: ls-drift 9s ease-in-out infinite alternate;
            pointer-events: none;
        }
        .ls-blob-1 {
            width: 500px; height: 500px;
            background: #E8C98A;
            top: -160px; left: -160px;
        }
        .ls-blob-2 {
            width: 420px; height: 420px;
            background: #F5E8CC;
            bottom: -120px; right: -120px;
            animation-delay: -4.5s;
        }
        @keyframes ls-drift {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(40px,30px) scale(1.12); }
        }
        .ls-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 38px;
            z-index: 1;
            animation: ls-fadeIn 0.7s ease both;
        }
        @keyframes ls-fadeIn {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .ls-wordmark {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-weight: 700;
            font-size: clamp(1.8rem, 5vw, 3rem);
            color: #5C3A1E;
            letter-spacing: 0.01em;
        }
        .ls-wordmark em { font-style: italic; color: #C9973A; }
        .ls-spinner-ring {
            position: relative;
            width: 96px; height: 96px;
        }
        .ls-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2px solid transparent;
        }
        .ls-ring-outer {
            border-top-color: #C9973A;
            border-right-color: #C9973A;
            animation: ls-spin 1.8s cubic-bezier(0.68,-0.4,0.27,1.4) infinite;
        }
        .ls-ring-middle {
            inset: 13px;
            border-top-color: #E8C98A;
            border-left-color: #E8C98A;
            animation: ls-spin 1.2s linear infinite reverse;
        }
        .ls-ring-inner {
            inset: 27px;
            border: 1.5px solid #E2D5C0;
            border-top-color: #5C3A1E;
            animation: ls-spin 0.85s ease-in-out infinite;
        }
        @keyframes ls-spin { to { transform: rotate(360deg); } }
        .ls-ring-dot {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .ls-ring-dot::after {
            content: '';
            width: 8px; height: 8px;
            background: #C9973A;
            border-radius: 50%;
            box-shadow: 0 0 10px #E8C98A, 0 0 22px #F5E8CC;
            animation: ls-pulse 1.6s ease-in-out infinite;
        }
        @keyframes ls-pulse {
            0%,100% { transform: scale(1); opacity: 1; }
            50%      { transform: scale(1.45); opacity: 0.65; }
        }
        .ls-ticks {
            display: flex;
            gap: 5px;
            align-items: flex-end;
        }
        .ls-tick {
            width: 3px;
            border-radius: 2px;
            background: #E2D5C0;
            animation: ls-tickIn 0.4s ease both;
        }
        .ls-tick-active { background: #E8C98A; }
        .ls-tick:nth-child(1){ height:8px;  animation-delay:0.15s; }
        .ls-tick:nth-child(2){ height:14px; animation-delay:0.25s; }
        .ls-tick:nth-child(3){ height:20px; animation-delay:0.35s; }
        .ls-tick:nth-child(4){ height:14px; animation-delay:0.45s; }
        .ls-tick:nth-child(5){ height:10px; animation-delay:0.55s; }
        .ls-tick:nth-child(6){ height:16px; animation-delay:0.65s; }
        .ls-tick:nth-child(7){ height:22px; animation-delay:0.75s; }
        .ls-tick:nth-child(8){ height:12px; animation-delay:0.85s; }
        @keyframes ls-tickIn {
            from { transform: scaleY(0); opacity: 0; }
            to   { transform: scaleY(1); opacity: 1; }
        }
        .ls-progress-wrap {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: min(300px, 86vw);
        }
        .ls-progress-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }
        .ls-status {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.7rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #8A7560;
        }
        .ls-status span { color: #C9973A; }
        .ls-pct {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-weight: 700;
            font-size: 0.88rem;
            color: #5C3A1E;
        }
        .ls-bar-track {
            width: 100%;
            height: 2px;
            background: #E2D5C0;
            border-radius: 2px;
            overflow: hidden;
        }
        .ls-bar-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #E8C98A, #C9973A);
            border-radius: 2px;
            animation: ls-load 3.5s cubic-bezier(0.25,0.46,0.45,0.94) forwards;
            box-shadow: 0 0 6px #E8C98A;
        }
        @keyframes ls-load {
            0%   { width: 0%;   }
            30%  { width: 38%;  }
            60%  { width: 67%;  }
            80%  { width: 84%;  }
            95%  { width: 97%;  }
            100% { width: 100%; }
        }
        .ls-tag {
            position: absolute;
            bottom: 26px;
            left: 50%;
            transform: translateX(-50%);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.68rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #E8C98A;
            z-index: 1;
            white-space: nowrap;
            opacity: 0;
            animation: ls-fadeIn 0.9s 0.9s ease both;
        }
        body:not(.ls-revealed) .navbar,
        body:not(.ls-revealed) .hero,
        body:not(.ls-revealed) .divider,
        body:not(.ls-revealed) .featured,
        body:not(.ls-revealed) footer {
            opacity: 0;
        }
        body.ls-revealed .navbar {
            animation: ls-revealDown 0.6s cubic-bezier(0.22,1,0.36,1) both;
        }
        body.ls-revealed .hero {
            animation: ls-revealUp 0.75s 0.1s cubic-bezier(0.22,1,0.36,1) both;
        }
        body.ls-revealed .divider,
        body.ls-revealed .featured,
        body.ls-revealed footer {
            animation: ls-revealUp 0.7s 0.2s cubic-bezier(0.22,1,0.36,1) both;
        }
        @keyframes ls-revealDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes ls-revealUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<!-- LOADING SCREEN -->
<div id="loading-screen">
    <div class="ls-blob ls-blob-1"></div>
    <div class="ls-blob ls-blob-2"></div>

    <div class="ls-wrap">
        <div class="ls-wordmark">Herana <em>Pastry</em></div>

        <div class="ls-spinner-ring">
            <div class="ls-ring ls-ring-outer"></div>
            <div class="ls-ring ls-ring-middle"></div>
            <div class="ls-ring ls-ring-inner"></div>
            <div class="ls-ring-dot"></div>
        </div>

        <div class="ls-ticks">
            <div class="ls-tick ls-tick-active"></div>
            <div class="ls-tick ls-tick-active"></div>
            <div class="ls-tick ls-tick-active"></div>
            <div class="ls-tick"></div>
            <div class="ls-tick"></div>
            <div class="ls-tick"></div>
            <div class="ls-tick"></div>
            <div class="ls-tick"></div>
        </div>

        <div class="ls-progress-wrap">
            <div class="ls-progress-row">
                <div class="ls-status" id="ls-statusText">Preparing<span id="ls-dots">...</span></div>
                <div class="ls-pct" id="ls-pct">0%</div>
            </div>
            <div class="ls-bar-track">
                <div class="ls-bar-fill"></div>
            </div>
        </div>
    </div>

    
</div>
<!-- END LOADING SCREEN -->


<!-- NAVBAR — only shows Dashboard/Logout when logged in -->
<nav class="navbar">
    <span class="navbar-brand">Herana Pastry</span>
    <div class="nav-links">
        <?php if ($is_logged_in): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        <?php endif; ?>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-tag">Herana Pastry Shop</div>
        <h1>Baked With <em>Love</em> 🍰</h1>
        <p>Handcrafted cakes & served with joy.</p>
        <?php if (!$is_logged_in): ?>
            <a href="#" onclick="openSignup()" class="btn btn-primary">Get Started</a>
        <?php else: ?>
            <a href="dashboard.php" class="btn btn-primary">Order Now</a>
        <?php endif; ?>
    </div>
</section>

<div class="divider">✦ ✦ ✦</div>

<!-- FEATURED -->
<section class="featured" id="featured">
    <h2 class="section-heading">Our Signature Sweets</h2>
    <p class="section-subheading">Handpicked favourites, loved by our customers</p>
    <div class="grid">
        <div class="card">
            <img src="y.webp" alt="Charlotte Raspberry" onerror="this.src='https://via.placeholder.com/280x220?text=Cake'">
            <div class="card-body">
                <h3>Charlotte Raspberry</h3>
                <p>₱1,899.00</p>
            </div>
        </div>
        <div class="card">
            <img src="46.png" alt="Salted Caramel Choco" onerror="this.src='https://via.placeholder.com/280x220?text=Cupcake'">
            <div class="card-body">
                <h3>Salted Caramel Choco</h3>
                <p>₱399.00</p>
            </div>
        </div>
        <div class="card">
            <img src="cup2.jpg" alt="Choco Matcha" onerror="this.src='https://via.placeholder.com/280x220?text=Cupcake'">
            <div class="card-body">
                <h3>Choco Matcha</h3>
                <p>₱499.00</p>
            </div>
        </div>
    </div>
</section>

<div class="divider">✦ ✦ ✦</div>

<!-- FOOTER -->
<footer>
    <div class="footer-divider"></div>
    <p>&copy; <?php echo date("Y"); ?> Herana Pastry. All sweetness reserved.</p>
</footer>

<!-- LOGIN MODAL -->
<div class="modal" id="loginModal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeLogin()">&times;</span>
        <h2>Welcome Back</h2>
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>No account? <a href="#" onclick="switchToSignup()">Sign up</a></p>
    </div>
</div>

<!-- SIGNUP MODAL -->
<div class="modal" id="signupModal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeSignup()">&times;</span>
        <h2>Create Account</h2>
        <form method="POST" action="signup.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="#" onclick="switchToLogin()">Login</a></p>
    </div>
</div>

<script>
/* ── Modal helpers ── */
function openLogin(){ document.getElementById("loginModal").style.display="flex"; }
function closeLogin(){ document.getElementById("loginModal").style.display="none"; }
function openSignup(){ document.getElementById("signupModal").style.display="flex"; }
function closeSignup(){ document.getElementById("signupModal").style.display="none"; }
function switchToSignup(){ closeLogin(); openSignup(); }
function switchToLogin(){ closeSignup(); openLogin(); }
window.onclick = e => { if(e.target.classList.contains("modal")) e.target.style.display="none"; }

/* ── Loading Screen ── */
(function () {
    const messages = ['Preparing', 'Loading sweets', 'Glazing details', 'Almost ready', 'Enjoy!'];
    const statusEl = document.getElementById('ls-statusText');
    const dotsEl   = document.getElementById('ls-dots');
    const pctEl    = document.getElementById('ls-pct');
    const screen   = document.getElementById('loading-screen');

    let dotCount = 0;
    const dotsInterval = setInterval(() => {
        dotCount = (dotCount + 1) % 4;
        if (dotsEl) dotsEl.textContent = '.'.repeat(dotCount || 1);
    }, 400);

    const DURATION = 3500;
    const start    = performance.now();
    const kf = [
        { t: 0,    p: 0   },
        { t: 0.30, p: 38  },
        { t: 0.60, p: 67  },
        { t: 0.80, p: 84  },
        { t: 0.95, p: 97  },
        { t: 1.00, p: 100 },
    ];

    function lerp(a, b, t) { return a + (b - a) * t; }
    function getP(frac) {
        for (let i = 1; i < kf.length; i++) {
            if (frac <= kf[i].t) {
                return lerp(kf[i-1].p, kf[i].p, (frac - kf[i-1].t) / (kf[i].t - kf[i-1].t));
            }
        }
        return 100;
    }

    function revealPage() {
        screen.remove();
        document.body.classList.add('ls-revealed');
        document.body.style.overflow = 'auto';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function tick(now) {
        const frac = Math.min((now - start) / DURATION, 1);
        if (pctEl) pctEl.textContent = Math.round(getP(frac)) + '%';
        const mi = Math.min(Math.floor(frac * messages.length), messages.length - 1);
        if (statusEl) statusEl.childNodes[0].nodeValue = messages[mi];

        if (frac < 1) {
            requestAnimationFrame(tick);
        } else {
            clearInterval(dotsInterval);
            if (dotsEl) dotsEl.textContent = '';
            if (statusEl) statusEl.childNodes[0].nodeValue = 'Ready';

            setTimeout(() => {
                screen.classList.add('ls-hide');
                const fallback = setTimeout(revealPage, 1000);
                screen.addEventListener('transitionend', () => {
                    clearTimeout(fallback);
                    revealPage();
                }, { once: true });
            }, 400);
        }
    }
    requestAnimationFrame(tick);
})();
</script>

</body>
</html>