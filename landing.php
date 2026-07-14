<?php
/**
 * LANDING PAGE - InvenTrack
 * Layout inspired by modern SaaS landing pages
 * Theme: Ink & Copper
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
  --ink:#161A23; --ink-2:#1D222C;
  --copper:#C2632E; --copper-dark:#A14E22; --copper-light:#F3E3D6;
  --teal:#1F6F66; --teal-light:#DCEEEA;
  --danger:#C23B3B; --danger-light:#FBE7E5;
  --paper:#F6F4EF; --paper-2:#EFEBE2;
  --white:#FFFFFF;
  --text:#20242C; --muted:#6B6F76; --border:#E6E1D8;
  --font-display:'Space Grotesk',sans-serif;
  --font-body:'Inter',sans-serif;
  --font-mono:'JetBrains Mono',monospace;
}
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{font-family:var(--font-body);color:var(--text);background:var(--white);line-height:1.6;-webkit-font-smoothing:antialiased;}
a{color:inherit;text-decoration:none;}
img{display:block;max-width:100%;}
.wrap{max-width:1200px;margin:0 auto;padding:0 40px;}

/* ── NAV ── */
.nav{background:var(--white);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:100;}
.nav-inner{display:flex;align-items:center;justify-content:space-between;padding:16px 40px;max-width:1200px;margin:0 auto;}
.brand{display:flex;align-items:center;gap:10px;}
.brand-icon{width:36px;height:36px;background:var(--ink);border-radius:8px;display:flex;align-items:center;justify-content:center;}
.brand-icon span{color:var(--copper);font-size:18px;font-weight:700;font-family:var(--font-display);}
.brand-text{font-family:var(--font-display);font-weight:700;font-size:17px;color:var(--ink);}
.brand-sub{font-size:10px;color:var(--muted);font-weight:500;letter-spacing:0.5px;text-transform:uppercase;margin-top:1px;}
.nav-links{display:flex;gap:34px;}
.nav-links a{font-size:14px;font-weight:500;color:var(--muted);padding-bottom:3px;border-bottom:2px solid transparent;transition:all .18s;}
.nav-links a:hover{color:var(--text);}
.nav-links a.active{color:var(--copper-dark);border-bottom-color:var(--copper);}
.nav-actions{display:flex;gap:10px;align-items:center;}
.btn{display:inline-flex;align-items:center;gap:6px;padding:9px 20px;border-radius:8px;font-size:13.5px;font-weight:600;cursor:pointer;border:none;transition:all .18s;font-family:var(--font-body);}
.btn-outline{background:transparent;color:var(--ink);border:1.5px solid var(--border);}
.btn-outline:hover{border-color:var(--ink);}
.btn-dark{background:var(--ink);color:#fff;border:1.5px solid var(--ink);}
.btn-dark:hover{background:var(--copper-dark);border-color:var(--copper-dark);}
.btn-copper{background:var(--copper);color:#fff;}
.btn-copper:hover{background:var(--copper-dark);}
.btn-ghost-white{background:rgba(255,255,255,0.12);color:#fff;border:1.5px solid rgba(255,255,255,0.3);}
.btn-ghost-white:hover{background:rgba(255,255,255,0.22);}
.btn-lg{padding:13px 28px;font-size:15px;border-radius:10px;}

/* ── HERO ── */
.hero{
  position:relative;
  min-height:88vh;
  display:flex;align-items:center;justify-content:center;
  text-align:center;
  background-image:linear-gradient(rgba(22,26,35,0.72),rgba(22,26,35,0.80)),
    url('assets\images\inven_img01.jpeg');
  background-size:cover;background-position:center;
  color:#fff;
  padding:80px 40px;
}
.hero-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:12px;font-weight:600;color:#F3D9C2;background:rgba(194,99,46,0.25);border:1px solid rgba(194,99,46,0.4);padding:7px 16px;border-radius:20px;letter-spacing:0.4px;margin-bottom:28px;}
.hero h1{font-family:var(--font-display);font-size:58px;line-height:1.08;font-weight:700;letter-spacing:-1.2px;max-width:780px;margin:0 auto 24px;}
.hero h1 .accent{color:var(--copper);}
.hero p{font-size:18px;color:rgba(255,255,255,0.75);max-width:560px;margin:0 auto 38px;line-height:1.7;}
.hero-actions{display:flex;gap:14px;justify-content:center;flex-wrap:wrap;}

/* ── SECTION BASE ── */
.section{padding:90px 0;}
.section-tag{font-size:11.5px;font-weight:700;color:var(--copper-dark);text-transform:uppercase;letter-spacing:1.2px;margin-bottom:14px;}
.section-head{text-align:center;max-width:640px;margin:0 auto 60px;}
.section-head h2{font-family:var(--font-display);font-size:36px;font-weight:700;letter-spacing:-0.5px;color:var(--ink);margin-bottom:14px;}
.section-head p{font-size:16px;color:var(--muted);}

/* ── WHY US — feature cards ── */
.why-section{background:var(--paper);}
.feature-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
.feature-card{background:var(--white);border:1px solid var(--border);border-radius:16px;padding:30px 26px;transition:transform .2s,box-shadow .2s;}
.feature-card:hover{transform:translateY(-4px);box-shadow:0 16px 40px rgba(22,26,35,0.09);}
.f-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:21px;margin-bottom:20px;}
.f-icon.copper{background:var(--copper-light);}
.f-icon.teal{background:var(--teal-light);}
.f-icon.paper{background:var(--paper-2);}
.feature-card h3{font-family:var(--font-display);font-size:17px;font-weight:600;color:var(--ink);margin-bottom:10px;}
.feature-card p{font-size:14px;color:var(--muted);line-height:1.65;}

/* ── SERVICES + STATS ── */
.services-section{background:var(--paper);}
.services-grid{display:grid;grid-template-columns:1fr 420px;gap:48px;align-items:center;}
.services-tag{font-size:11.5px;font-weight:700;color:var(--copper-dark);text-transform:uppercase;letter-spacing:1.2px;margin-bottom:14px;}
.services-grid h2{font-family:var(--font-display);font-size:34px;font-weight:700;letter-spacing:-0.4px;color:var(--ink);margin-bottom:14px;}
.services-grid>div>p{font-size:15px;color:var(--muted);margin-bottom:28px;}
.checklist{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:28px;}
.check-item{display:flex;align-items:center;gap:10px;background:var(--white);border:1px solid var(--border);border-radius:10px;padding:12px 16px;font-size:13.5px;font-weight:500;color:var(--text);}
.check-dot{width:22px;height:22px;border-radius:50%;background:var(--teal-light);display:flex;align-items:center;justify-content:center;font-size:11px;color:var(--teal);flex-shrink:0;}
.link-more{font-size:14px;font-weight:700;color:var(--copper-dark);display:inline-flex;align-items:center;gap:6px;}
.link-more:hover{color:var(--copper);}
.stats-card{background:var(--ink);border-radius:20px;padding:36px 32px;color:#fff;}
.stats-card h3{font-family:var(--font-display);font-size:19px;font-weight:700;color:#fff;margin-bottom:28px;}
.stat-row{padding:22px 0;border-top:1px solid rgba(255,255,255,0.08);}
.stat-row:first-of-type{border-top:none;padding-top:0;}
.stat-row .num{font-family:var(--font-display);font-size:42px;font-weight:700;color:#fff;letter-spacing:-1px;line-height:1;}
.stat-row .num .accent{color:var(--copper);}
.stat-row .lbl{font-size:13px;color:#94A3B8;margin-top:6px;}
.stats-card .btn{width:100%;justify-content:center;margin-top:28px;border-radius:10px;padding:13px;}

/* ── HOW IT WORKS ── */
.how-section{background:var(--white);}
.steps-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-bottom:44px;}
.step-card{background:var(--paper);border:1px solid var(--border);border-radius:16px;overflow:hidden;transition:transform .2s,box-shadow .2s;}
.step-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(22,26,35,0.09);}
.step-img{position:relative;height:200px;overflow:hidden;}
.step-img img{width:100%;height:100%;object-fit:cover;}
.step-badge{position:absolute;top:14px;left:14px;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;font-family:var(--font-display);}
.step-badge.b1{background:var(--copper-light);color:var(--copper-dark);}
.step-badge.b2{background:var(--teal-light);color:var(--teal);}
.step-badge.b3{background:var(--paper-2);color:var(--ink);}
.step-body{padding:22px 22px 26px;text-align:center;}
.step-body h4{font-family:var(--font-display);font-size:17px;font-weight:600;color:var(--ink);margin-bottom:8px;}
.step-body p{font-size:13.5px;color:var(--muted);}
.how-cta{text-align:center;}

/* ── CONTACT STRIP ── */
.contact-strip{background:var(--paper);}
.contact-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
.contact-card{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:26px;}
.contact-card-head{display:flex;align-items:center;gap:14px;margin-bottom:16px;}
.contact-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:19px;}
.contact-icon.c1{background:var(--teal-light);}
.contact-icon.c2{background:var(--copper-light);}
.contact-icon.c3{background:var(--paper-2);}
.contact-card-head .label{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--muted);}
.contact-card-head h4{font-family:var(--font-display);font-size:16px;font-weight:700;color:var(--ink);margin-top:3px;}
.contact-card p{font-size:13.5px;color:var(--text);line-height:1.9;}
.contact-card span{color:var(--muted);}

/* ── FOOTER ── */
footer{background:var(--ink);color:#94A3B8;padding:60px 0 0;}
.footer-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr 1fr;gap:48px;padding-bottom:50px;}
.footer-brand{font-family:var(--font-display);font-size:17px;font-weight:700;color:#fff;margin-bottom:12px;display:flex;align-items:center;gap:10px;}
.footer-brand-icon{width:32px;height:32px;background:rgba(194,99,46,0.2);border-radius:7px;display:flex;align-items:center;justify-content:center;color:var(--copper);font-weight:700;font-size:15px;}
.footer-desc{font-size:13px;line-height:1.7;max-width:220px;color:#64748B;margin-bottom:22px;}
.footer-socials{display:flex;gap:10px;}
.social-btn{width:34px;height:34px;border-radius:8px;background:rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center;font-size:14px;color:#94A3B8;transition:.18s;}
.social-btn:hover{background:rgba(194,99,46,0.2);color:var(--copper);}
.footer-col h5{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#64748B;margin-bottom:18px;}
.footer-col a{display:block;font-size:13.5px;color:#94A3B8;margin-bottom:11px;transition:.18s;}
.footer-col a:hover{color:#fff;}
.footer-contact-row{display:flex;align-items:center;gap:9px;font-size:13px;color:#94A3B8;margin-bottom:11px;}
.footer-contact-row .ico{font-size:14px;color:var(--copper);}
.footer-bottom{border-top:1px solid rgba(255,255,255,0.06);padding:20px 0;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;}
.footer-bottom p{font-size:12px;color:#475569;}
.footer-badge{background:var(--copper);color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px;}

/* ── RESPONSIVE ── */
@media(max-width:1024px){
  .services-grid{grid-template-columns:1fr;}
  .stats-card{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;align-items:start;}
  .stats-card h3{grid-column:1/-1;}
  .stats-card .btn{grid-column:1/-1;}
  .footer-grid{grid-template-columns:1fr 1fr;}
}
@media(max-width:768px){
  .nav-links{display:none;}
  .hero h1{font-size:38px;}
  .feature-grid,.steps-grid,.contact-grid{grid-template-columns:1fr;}
  .checklist{grid-template-columns:1fr;}
  .footer-grid{grid-template-columns:1fr;}
  .wrap{padding:0 20px;}
  .nav-inner{padding:14px 20px;}
  .section{padding:60px 0;}
  .stats-card{display:block;}
}
</style>
</head>
<body>

<!-- NAV -->
<nav class="nav">
  <div class="nav-inner">
    <div class="brand">
      <div class="brand-icon"><span>I</span></div>
      <div>
        <div class="brand-text">InvenTrack</div>
        <div class="brand-sub">Inventory Platform</div>
      </div>
    </div>
    <div class="nav-links">
      <a href="#" class="active">Home</a>
      <a href="#features">Features</a>
      <a href="#how">How It Works</a>
      <a href="#contact">Contact</a>
    </div>
    <div class="nav-actions">
      <a href="index.php" class="btn btn-outline">Sign in</a>
      <a href="index.php" class="btn btn-dark">Open Dashboard</a>
    </div>
  </div>
</nav>

<!-- HERO -->
<header class="hero">
  <div>
    <div class="hero-eyebrow"> Built for modern inventory teams</div>
    <h1>Smart Inventory,<br>Zero <span class="accent">Guesswork.</span></h1>
    <p>Track every product, supplier, and stock movement in one place — so restocking is always a decision you make on purpose.</p>
    <div class="hero-actions">
      <a href="index.php" class="btn btn-copper btn-lg">Open Dashboard →</a>
      <a href="#how" class="btn btn-ghost-white btn-lg">How It Works</a>
    </div>
  </div>
</header>

<!-- WHY US -->
<section class="section why-section" id="features">
  <div class="wrap">
    <div class="section-head">
      <div class="section-tag">Why Choose Us</div>
      <h2>What Makes InvenTrack Different</h2>
      <p>We combine clean design with reliable data — so you always know what's on the shelf.</p>
    </div>
    <div class="feature-grid">
      <div class="feature-card">
        <div class="f-icon copper"></div>
        <h3>Product Catalog</h3>
        <p>Add, edit, and organize products by category and supplier, with built-in validation so bad data never reaches the database.</p>
      </div>
      <div class="feature-card">
        <div class="f-icon teal"></div>
        <h3>Live Stock Tracking</h3>
        <p>Log every stock-in and stock-out movement. Current quantities recalculate automatically on every transaction.</p>
      </div>
      <div class="feature-card">
        <div class="f-icon paper"></div>
        <h3>Low Stock Alerts</h3>
        <p>Set a minimum threshold per product and get a clear, prioritized alert list the moment stock dips below it.</p>
      </div>
      <div class="feature-card">
        <div class="f-icon teal"></div>
        <h3>Supplier Management</h3>
        <p>Keep contact details and supply history for every supplier tied directly to the products they provide.</p>
      </div>
      <div class="feature-card">
        <div class="f-icon copper"></div>
        <h3>Reports & Valuation</h3>
        <p>See total inventory value, stock health, and movement trends at a glance — no spreadsheet exports required.</p>
      </div>
      <div class="feature-card">
        <div class="f-icon paper"></div>
        <h3>Reliable by Design</h3>
        <p>Built on a clean PHP/MySQL OOP structure, so every action — add, edit, delete — is validated and predictable.</p>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES + STATS -->
<section class="section services-section">
  <div class="wrap">
    <div class="services-grid">
      <div>
        <div class="services-tag">Our Modules</div>
        <h2>Complete Inventory Control</h2>
        <p>Our system covers the full lifecycle of every product — from the moment it's added to the moment it needs restocking.</p>
        <div class="checklist">
          <div class="check-item"><div class="check-dot">✓</div>Product catalog management</div>
          <div class="check-item"><div class="check-dot">✓</div>Stock transaction logs</div>
          <div class="check-item"><div class="check-dot">✓</div>Low stock alert dashboard</div>
          <div class="check-item"><div class="check-dot">✓</div>Supplier records</div>
          <div class="check-item"><div class="check-dot">✓</div>Inventory value reports</div>
          <div class="check-item"><div class="check-dot">✓</div>Category-grouped product view</div>
        </div>
        <a href="index.php" class="link-more">Explore the Dashboard →</a>
      </div>
      <div class="stats-card">
        <h3>Trusted by Teams Who Count</h3>
        <div class="stat-row">
          <div class="num"><span class="accent">6</span>+</div>
          <div class="lbl">Core system modules</div>
        </div>
        <div class="stat-row">
          <div class="num">100%</div>
          <div class="lbl">MySQL-backed data records</div>
        </div>
        <div class="stat-row">
          <div class="num">0</div>
          <div class="lbl">Spreadsheets needed</div>
        </div>
        <a href="index.php" class="btn btn-outline" style="background:rgba(255,255,255,0.08);color:#fff;border-color:rgba(255,255,255,0.18);">Open Dashboard →</a>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="section how-section" id="how">
  <div class="wrap">
    <div class="section-head">
      <div class="section-tag">Simple Process</div>
      <h2>How It Works</h2>
      <p>Four steps, no training manual required.</p>
    </div>
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-img">
          <img src="assets\images\inven_img02.jpeg" alt="Add products">
          <div class="step-badge b1">1</div>
        </div>
        <div class="step-body">
          <h4>Add Your Products</h4>
          <p>Enter products with name, code, category, supplier, price, and initial stock level — all in one form.</p>
        </div>
      </div>
      <div class="step-card">
        <div class="step-img">
          <img src="assets\images\inven_img03.jpeg">
          <div class="step-badge b2">2</div>
        </div>
        <div class="step-body">
          <h4>Record Stock Movement</h4>
          <p>Log every delivery or sale as a transaction. Stock levels recalculate instantly, in or out.</p>
        </div>
      </div>
      <div class="step-card">
        <div class="step-img">
          <img src="assets\images\inven_img04.jpeg">
          <div class="step-badge b3">3</div>
        </div>
        <div class="step-body">
          <h4>Act on the Dashboard</h4>
          <p>Monitor stock health, spot low-stock items, check reports, and restock before shelves run empty.</p>
        </div>
      </div>
    </div>
    <div class="how-cta">
      <a href="index.php" class="btn btn-copper btn-lg">Get Started →</a>
    </div>
  </div>
</section>

<!-- CONTACT STRIP -->
<section class="section contact-strip" id="contact">
  <div class="wrap">
    <div class="contact-grid">
      <div class="contact-card">
        <div class="contact-card-head">
          <div class="contact-icon c1"></div>
          <div>
            <div class="label">System Access</div>
            <h4>Availability</h4>
          </div>
        </div>
        <p>
          <span>Dashboard:</span> Available 24/7<br>
          <span>Data:</span> Real-time updates<br>
          <span>Reports:</span> Generated on demand
        </p>
      </div>
      <div class="contact-card">
        <div class="contact-card-head">
          <div class="contact-icon c2"></div>
          <div>
            <div class="label">Technology</div>
            <h4>Built With</h4>
          </div>
        </div>
        <p>
          <span>Backend:</span> PHP (OOP)<br>
          <span>Database:</span> MySQL<br>
          <span>Frontend:</span> HTML · CSS · JavaScript
        </p>
      </div>
      <div class="contact-card">
        <div class="contact-card-head">
          <div class="contact-icon c3"></div>
          <div>
            <div class="label">Project Info</div>
            <h4>About InvenTrack</h4>
          </div>
        </div>
        <p>
          <span>Type:</span> Group Assignment<br>
          <span>Subject:</span> CST 226-2 Web App Dev<br>
          <span>Members:</span> 7 group members
        </p>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <div class="footer-brand">
          <div class="footer-brand-icon">I</div>
          InvenTrack
        </div>
        <p class="footer-desc">A complete inventory management platform built with PHP, MySQL, and a clean OOP architecture.</p>
        <div class="footer-socials">
          <a href="#" class="social-btn">f</a>
          <a href="#" class="social-btn">in</a>
          <a href="#" class="social-btn">gh</a>
        </div>
      </div>
      <div class="footer-col">
        <h5>Quick Links</h5>
        <a href="#">Home</a>
        <a href="#features">Features</a>
        <a href="#how">How It Works</a>
        <a href="#contact">Contact</a>
        <a href="index.php">Dashboard</a>
      </div>
      <div class="footer-col">
        <h5>Modules</h5>
        <a href="index.php">Dashboard</a>
        <a href="pages/products/list.php">Products</a>
        <a href="pages/inventory/list.php">Stock Levels</a>
        <a href="pages/suppliers/list.php">Suppliers</a>
        <a href="pages/reports/index.php">Reports</a>
      </div>
      <div class="footer-col">
        <h5>Project</h5>
        <div class="footer-contact-row"><span class="ico"></span> CST 226-2</div>
        <div class="footer-contact-row"><span class="ico"></span> Group Project</div>
        <div class="footer-contact-row"><span class="ico"></span> Group 03 </div>
        <div class="footer-contact-row"><span class="ico"></span> uva wellassa university of srilanka</div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2026 InvenTrack — Inventory Management System. All rights reserved.</p>
      <span class="footer-badge">CST 226-2</span>
    </div>
  </div>
</footer>

</body>
</html>
