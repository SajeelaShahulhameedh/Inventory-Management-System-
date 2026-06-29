<?php
/**
 * LANDING PAGE - InvenTrack
 * Marketing entry point. Links through to the dashboard (index.php).
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>InvenTrack — Inventory Management, Without the Guesswork</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root{
  --ink:#161A23; --ink-2:#2B2118;
  --copper:#C2632E; --copper-dark:#A14E22; --copper-light:#F3E3D6;
  --teal:#1F6F66; --teal-light:#DCEEEA;
  --paper:#F6F4EF; --white:#FFFFFF;
  --text:#20242C; --muted:#6B6F76; --border:#E6E1D8;
  --font-display:'Space Grotesk',sans-serif;
  --font-body:'Inter',sans-serif;
  --font-mono:'JetBrains Mono',monospace;
}
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{font-family:var(--font-body);color:var(--text);background:var(--paper);line-height:1.6;-webkit-font-smoothing:antialiased;}
a{color:inherit;}
.wrap{max-width:1180px;margin:0 auto;padding:0 32px;}

/* NAV */
.nav{position:sticky;top:0;z-index:50;background:rgba(246,244,239,0.92);backdrop-filter:blur(8px);border-bottom:1px solid var(--border);}
.nav-inner{display:flex;align-items:center;justify-content:space-between;padding:18px 32px;max-width:1180px;margin:0 auto;}
.brand{display:flex;align-items:center;gap:10px;font-family:var(--font-display);font-weight:700;font-size:18px;letter-spacing:-0.2px;}
.brand .dot{width:9px;height:9px;border-radius:2px;background:var(--copper);display:inline-block;}
.nav-links{display:flex;gap:30px;font-size:14px;font-weight:500;color:var(--muted);}
.nav-links a:hover{color:var(--text);}
.nav-cta{display:flex;gap:14px;align-items:center;}
.btn{display:inline-flex;align-items:center;gap:6px;padding:10px 20px;border-radius:8px;font-weight:600;font-size:13.5px;text-decoration:none;border:none;cursor:pointer;transition:all .18s ease;}
.btn-primary{background:var(--ink);color:#fff;}
.btn-primary:hover{background:var(--copper-dark);}
.btn-ghost{color:var(--text);font-weight:600;font-size:13.5px;}

/* HERO */
.hero{
  padding:100px 0 80px;
  position:relative;
  overflow:hidden;
  background-image:
    linear-gradient(120deg, rgba(22,26,35,0.94) 0%, rgba(22,26,35,0.88) 38%, rgba(22,26,35,0.55) 100%),
    url('https://images.unsplash.com/photo-1749244768351-2726dc23d26c?fm=jpg&q=80&w=2000&auto=format&fit=crop');
  background-size:cover;
  background-position:center;
  color:#fff;
}
.hero-grid{display:grid;grid-template-columns:1.1fr 1fr;gap:60px;align-items:center;}
.eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:12px;font-weight:600;color:#F3D9C2;background:rgba(194,99,46,0.22);padding:6px 14px;border-radius:20px;letter-spacing:0.3px;margin-bottom:22px;}
h1.headline{font-family:var(--font-display);font-size:50px;line-height:1.08;font-weight:700;letter-spacing:-1px;color:#fff;}
h1.headline .accent{color:var(--copper);}
.sub{font-size:17px;color:#C8C2B8;margin-top:22px;max-width:480px;}
.hero-actions{display:flex;gap:14px;margin-top:34px;}
.hero-actions .btn-ghost{color:#fff;}
.btn-lg{padding:13px 26px;font-size:14.5px;}

/* HERO VISUAL — stock shelf motif */
.shelf-card{background:rgba(22,26,35,0.7);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,0.1);border-radius:18px;padding:26px;box-shadow:0 20px 50px rgba(0,0,0,0.35);position:relative;}
.shelf-card .label{font-size:11px;color:#A39788;text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:16px;}
.shelf-row{display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:10px;padding:13px 16px;margin-bottom:10px;}
.shelf-row:last-child{margin-bottom:0;}
.shelf-name{color:#fff;font-size:13.5px;font-weight:600;}
.shelf-code{color:#8B91A0;font-size:11px;font-family:var(--font-mono);margin-top:2px;}
.shelf-bar{width:64px;height:6px;border-radius:4px;background:rgba(255,255,255,0.08);overflow:hidden;}
.shelf-bar span{display:block;height:100%;border-radius:4px;}
.fill-good{background:var(--teal);width:82%;}
.fill-mid{background:var(--copper);width:46%;}
.fill-low{background:#C23B3B;width:14%;}
.shelf-qty{color:#fff;font-family:var(--font-mono);font-size:12.5px;font-weight:500;min-width:46px;text-align:right;}

/* LOGO/TRUST STRIP */
.trust{padding:30px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);}
.trust-inner{display:flex;align-items:center;justify-content:center;gap:54px;flex-wrap:wrap;font-family:var(--font-display);font-weight:600;color:#A8A096;font-size:14px;letter-spacing:0.3px;}

/* FEATURES */
.section{padding:90px 0;}
.section-head{max-width:560px;margin-bottom:54px;}
.section-tag{font-size:12px;font-weight:700;color:var(--copper-dark);text-transform:uppercase;letter-spacing:1px;margin-bottom:14px;}
.section-head h2{font-family:var(--font-display);font-size:34px;font-weight:700;letter-spacing:-0.6px;color:var(--ink);}
.section-head p{color:var(--muted);font-size:15.5px;margin-top:14px;}

.feature-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;}
.feature-card{background:var(--white);border:1px solid var(--border);border-radius:16px;padding:30px 26px;transition:transform .2s,box-shadow .2s;}
.feature-card:hover{transform:translateY(-4px);box-shadow:0 16px 36px rgba(22,26,35,0.08);}
.feature-icon{width:46px;height:46px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:18px;}
.feature-icon.c1{background:var(--copper-light);}
.feature-icon.c2{background:var(--teal-light);}
.feature-icon.c3{background:#EFEBE2;}
.feature-card h3{font-family:var(--font-display);font-size:17px;font-weight:600;margin-bottom:10px;color:var(--ink);}
.feature-card p{font-size:14px;color:var(--muted);}

/* HOW IT WORKS */
.how{background:var(--ink);color:#fff;}
.how .section-tag{color:#E0A074;}
.how .section-head h2{color:#fff;}
.how .section-head p{color:#A8A096;}
.steps{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;}
.step{padding:0;}
.step-num{font-family:var(--font-mono);font-size:13px;color:var(--copper);margin-bottom:14px;}
.step h4{font-family:var(--font-display);font-size:16px;font-weight:600;margin-bottom:10px;}
.step p{font-size:13.5px;color:#A8A096;}
.step-line{height:1px;background:rgba(255,255,255,0.1);margin:0 0 24px;}

/* STATS */
.stat-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;text-align:center;padding:50px 0;}
.stat-strip .num{font-family:var(--font-display);font-size:38px;font-weight:700;color:var(--ink);letter-spacing:-0.5px;}
.stat-strip .num .accent{color:var(--copper);}
.stat-strip .lbl{font-size:13px;color:var(--muted);margin-top:6px;}

/* CTA */
.cta-band{background:linear-gradient(135deg,var(--ink),var(--ink-2));border-radius:22px;padding:60px 50px;display:flex;align-items:center;justify-content:space-between;color:#fff;position:relative;overflow:hidden;}
.cta-band::after{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;background:rgba(194,99,46,0.2);border-radius:50%;}
.cta-band h2{font-family:var(--font-display);font-size:30px;font-weight:700;letter-spacing:-0.5px;max-width:420px;}
.cta-band p{color:#A8A096;margin-top:10px;font-size:14.5px;}

/* FOOTER */
footer{border-top:1px solid var(--border);padding:40px 0;}
.footer-inner{display:flex;justify-content:space-between;align-items:center;font-size:13px;color:var(--muted);flex-wrap:wrap;gap:14px;}

@media (max-width:900px){
  .hero-grid{grid-template-columns:1fr;}
  .feature-grid{grid-template-columns:1fr 1fr;}
  .steps{grid-template-columns:1fr 1fr;}
  .stat-strip{grid-template-columns:1fr 1fr;}
  h1.headline{font-size:38px;}
  .nav-links{display:none;}
  .cta-band{flex-direction:column;gap:24px;text-align:center;padding:44px 28px;}
}
@media (max-width:600px){
  .feature-grid,.steps,.stat-strip{grid-template-columns:1fr;}
  .wrap{padding:0 20px;}
}
</style>
</head>
<body>

<nav class="nav">
  <div class="nav-inner">
    <div class="brand"><span class="dot"></span> InvenTrack</div>
    <div class="nav-links">
      <a href="#features">Features</a>
      <a href="#how">How it Works</a>
      <a href="#stats">Why InvenTrack</a>
    </div>
    <div class="nav-cta">
      <a href="index.php" class="btn-ghost">Sign in</a>
      <a href="index.php" class="btn btn-primary">Open Dashboard →</a>
    </div>
  </div>
</nav>

<header class="hero">
  <div class="wrap hero-grid">
    <div>
      <span class="eyebrow">● Built for small & growing teams</span>
      <h1 class="headline">Know what's on the shelf<br>before it runs <span class="accent">out.</span></h1>
      <p class="sub">InvenTrack tracks every product, supplier, and stock movement in one place — so restocking is a decision you make on purpose, not a surprise you find out too late.</p>
      <div class="hero-actions">
        <a href="index.php" class="btn btn-primary btn-lg">Open Dashboard →</a>
        <a href="#features" class="btn-ghost" style="display:flex;align-items:center;">See how it works</a>
      </div>
    </div>
    <div class="shelf-card">
      <div class="label">Live Stock Snapshot</div>
      <div class="shelf-row">
        <div><div class="shelf-name">Wireless Mouse</div><div class="shelf-code">SKU-1042</div></div>
        <div class="shelf-bar"><span class="fill-good"></span></div>
        <div class="shelf-qty">164</div>
      </div>
      <div class="shelf-row">
        <div><div class="shelf-name">USB-C Cable 1m</div><div class="shelf-code">SKU-2207</div></div>
        <div class="shelf-bar"><span class="fill-mid"></span></div>
        <div class="shelf-qty">58</div>
      </div>
      <div class="shelf-row">
        <div><div class="shelf-name">Laptop Stand</div><div class="shelf-code">SKU-3391</div></div>
        <div class="shelf-bar"><span class="fill-low"></span></div>
        <div class="shelf-qty">9</div>
      </div>
    </div>
  </div>
</header>

<div class="trust">
  <div class="wrap trust-inner">
    <span>Real-Time Stock Tracking · </span><span>Supplier Management ·</span><span>Low-Stock Alerts ·</span><span>Inventory Reports</span>
  </div>
</div>

<section class="section" id="features">
  <div class="wrap">
    <div class="section-head">
      <div class="section-tag">Features</div>
      <h2>Everything inventory needs, nothing it doesn't</h2>
      <p>Four modules cover the full lifecycle of a product — from the moment it's added to the moment it's running low.</p>
    </div>
    <div class="feature-grid">
      <div class="feature-card">
        <div class="feature-icon c1">📦</div>
        <h3>Product Catalog</h3>
        <p>Add, edit, and organize products by category and supplier, with built-in validation so bad data never reaches the database.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon c2">🔄</div>
        <h3>Stock Transactions</h3>
        <p>Log every stock-in and stock-out movement, with current quantities recalculated automatically.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon c3">⚠️</div>
        <h3>Low Stock Alerts</h3>
        <p>Set a minimum threshold per product and get a clear, prioritized list the moment stock dips below it.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon c1">🏢</div>
        <h3>Supplier Records</h3>
        <p>Keep contact details and order history for every supplier tied directly to the products they provide.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon c2">📊</div>
        <h3>Reports & Valuation</h3>
        <p>See total inventory value, stock health, and trends at a glance — no spreadsheet exports required.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon c3">🔐</div>
        <h3>Reliable by Design</h3>
        <p>Built on a clean PHP/MySQL OOP structure, so every action — add, edit, delete — is validated and predictable.</p>
      </div>
    </div>
  </div>
</section>

<section class="section how" id="how">
  <div class="wrap">
    <div class="section-head">
      <div class="section-tag">How it works</div>
      <h2>From empty database to full visibility</h2>
      <p>Four steps, no training manual required.</p>
    </div>
    <div class="steps">
      <div class="step">
        <div class="step-num">01</div>
        <div class="step-line"></div>
        <h4>Add your products</h4>
        <p>Enter products with category, supplier, and a minimum stock threshold.</p>
      </div>
      <div class="step">
        <div class="step-num">02</div>
        <div class="step-line"></div>
        <h4>Record stock movement</h4>
        <p>Every delivery or sale is logged as a transaction, in or out.</p>
      </div>
      <div class="step">
        <div class="step-num">03</div>
        <div class="step-line"></div>
        <h4>Watch the dashboard</h4>
        <p>Stock levels, value, and alerts update automatically as you go.</p>
      </div>
      <div class="step">
        <div class="step-num">04</div>
        <div class="step-line"></div>
        <h4>Act before it's empty</h4>
        <p>Restock straight from the low-stock list — no digging required.</p>
      </div>
    </div>
  </div>
</section>

<section class="section" id="stats">
  <div class="wrap">
    <div class="stat-strip">
      <div><div class="num"><span class="accent">6</span>+</div><div class="lbl">Core modules</div></div>
      <div><div class="num">100%</div><div class="lbl">MySQL-backed records</div></div>
      <div><div class="num">3</div><div class="lbl">Tap-to-restock alerts</div></div>
      <div><div class="num">0</div><div class="lbl">Spreadsheets needed</div></div>
    </div>
  </div>
</section>

<section class="section" style="padding-top:0;">
  <div class="wrap">
    <div class="cta-band">
      <div>
        <h2>Stop guessing what's in stock.</h2>
        <p>Open the dashboard and see your full inventory picture in seconds.</p>
      </div>
      <a href="index.php" class="btn btn-primary btn-lg" style="background:#fff;color:var(--ink);">Open Dashboard →</a>
    </div>
  </div>
</section>

<footer>
  <div class="wrap footer-inner">
    <div>&copy; 2026 InvenTrack — Inventory Management System</div>
    <div>Built with PHP · MySQL · HTML · CSS · JavaScript</div>
  </div>
</footer>

</body>
</html>
