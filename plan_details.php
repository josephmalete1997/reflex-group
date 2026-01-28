<?php
declare(strict_types=1);

include 'includes/header.php';
require_once 'backend/config/db.php';

$plan_id = isset($_GET['id']) ? trim((string)$_GET['id']) : '';
if ($plan_id === '') {
    header('Location: index');
    exit;
}

/* 1) Fetch plan */
$stmt = $pdo->prepare("SELECT * FROM plans WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $plan_id]);
$current_plan = $stmt->fetch();

if (!$current_plan) {
    header('Location: index');
    exit;
}

/* Map DB fields to your existing template keys */
$current_plan['desc'] = $current_plan['short_desc'];
$current_plan['full_desc'] = $current_plan['full_desc'] ?? $current_plan['short_desc'];

/* 2) Fetch features */
$f = $pdo->prepare("SELECT feature FROM plan_features WHERE plan_id = :id ORDER BY id ASC");
$f->execute([':id' => $plan_id]);
$current_plan['features'] = array_map(fn($r) => $r['feature'], $f->fetchAll());

/* 3) Fetch gallery */
$g = $pdo->prepare("SELECT image FROM plan_gallery WHERE plan_id = :id ORDER BY id ASC");
$g->execute([':id' => $plan_id]);
$current_plan['gallery'] = array_map(fn($r) => $r['image'], $g->fetchAll());

/* 4) Related plans (same style OR same bedrooms) */
$r = $pdo->prepare("
    SELECT id, name, img, short_desc, old_price, new_price
    FROM plans
    WHERE id <> :id
      AND (style = :style OR bedrooms = :bedrooms)
    ORDER BY created_at DESC
    LIMIT 4
");
$r->execute([
    ':id' => $plan_id,
    ':style' => $current_plan['style'],
    ':bedrooms' => (int)$current_plan['bedrooms'],
]);
$related = $r->fetchAll();

/* 5) Discount (avoid division by zero) */
$old = (float)$current_plan['old_price'];
$new = (float)$current_plan['new_price'];
$discount = ($old > 0 && $new < $old) ? (int)round((($old - $new) / $old) * 100) : 0;
?>

<main class="page">
    <!-- Breadcrumb -->
    <section class="container">
        <nav class="breadcrumb">
            <a href="/">Home</a>
            <span class="sep"><i class="fa-solid fa-chevron-right"></i></span>
            <a href="/#plans">Building Plans</a>
            <span class="sep"><i class="fa-solid fa-chevron-right"></i></span>
            <span class="current"><?php echo htmlspecialchars($current_plan['name']); ?></span>
        </nav>
    </section>

    <!-- Main -->
    <section class="container plan-page">
        <div class="plan-layout">
            <!-- Gallery -->
            <aside class="card gallery-card">
                <div class="gallery-main">
                    <img
                        src="<?php echo htmlspecialchars($current_plan['img']); ?>"
                        alt="<?php echo htmlspecialchars($current_plan['name']); ?>"
                        id="mainImage"
                        loading="eager"
                    />
                    <?php if ($discount > 0): ?>
                        <span class="badge badge-discount">-<?php echo $discount; ?>% OFF</span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($current_plan['gallery'])): ?>
                    <div class="gallery-thumbs" role="list">
                        <button class="thumb active" type="button"
                            onclick="changeImage('<?php echo htmlspecialchars($current_plan['img']); ?>', this)">
                            <img src="<?php echo htmlspecialchars($current_plan['img']); ?>" alt="Main view" loading="lazy">
                        </button>

                        <?php foreach ($current_plan['gallery'] as $image): ?>
                            <button class="thumb" type="button"
                                onclick="changeImage('<?php echo htmlspecialchars($image); ?>', this)">
                                <img src="<?php echo htmlspecialchars($image); ?>" alt="Gallery image" loading="lazy">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </aside>

            <!-- Details -->
            <section class="card details-card">
                <div class="details-top">
                    <span class="sku">SKU: <?php echo strtoupper(htmlspecialchars($current_plan['id'])); ?></span>
                    <h1 class="title"><?php echo htmlspecialchars($current_plan['name']); ?></h1>

                    <div class="price-row">
                        <div class="price">
                            <?php if ($old > 0): ?>
                                <span class="old">R<?php echo number_format($old, 2); ?></span>
                            <?php endif; ?>
                            <span class="new">R<?php echo number_format($new, 2); ?></span>
                        </div>

                        <?php if ($discount > 0): ?>
                            <div class="save">
                                You save <strong>R<?php echo number_format($old - $new, 2); ?></strong>
                                <span>(<?php echo $discount; ?>%)</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="summary">
                        <?php echo htmlspecialchars((string)$current_plan['desc']); ?>
                    </div>

                    <div class="cta-row">
                        <a href="cart?add=<?php echo urlencode($current_plan['id']); ?>" class="btn btn-primary">
                            <i class="fa-solid fa-cart-plus"></i> Add to Cart
                        </a>
                        <a href="checkout?buy=<?php echo urlencode($current_plan['id']); ?>" class="btn btn-dark">
                            <i class="fa-solid fa-bolt"></i> Buy Now
                        </a>
                        <button class="btn btn-ghost wishlist-btn" onclick="addToWishlist(event, '<?php echo htmlspecialchars($current_plan['id']); ?>')">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </div>

                    <div class="trust">
                        <div class="trust-item"><i class="fa-solid fa-shield-halved"></i><span>Secure Payment</span></div>
                        <div class="trust-item"><i class="fa-solid fa-download"></i><span>Instant Download</span></div>
                        <div class="trust-item"><i class="fa-solid fa-headset"></i><span>24/7 Support</span></div>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Specs -->
                <div class="block">
                    <h3 class="block-title">Specifications</h3>
                    <div class="spec-grid">
                        <div class="spec">
                            <i class="fa-solid fa-bed"></i>
                            <div>
                                <div class="label">Bedrooms</div>
                                <div class="value"><?php echo (int)$current_plan['bedrooms']; ?></div>
                            </div>
                        </div>

                        <div class="spec">
                            <i class="fa-solid fa-bath"></i>
                            <div>
                                <div class="label">Bathrooms</div>
                                <div class="value"><?php echo (int)$current_plan['bathrooms']; ?></div>
                            </div>
                        </div>

                        <div class="spec">
                            <i class="fa-solid fa-car"></i>
                            <div>
                                <div class="label">Garage</div>
                                <div class="value"><?php echo (int)$current_plan['garage']; ?> Car</div>
                            </div>
                        </div>

                        <div class="spec">
                            <i class="fa-solid fa-ruler-combined"></i>
                            <div>
                                <div class="label">Floor Area</div>
                                <div class="value"><?php echo htmlspecialchars((string)$current_plan['sqm']); ?> m2</div>
                            </div>
                        </div>

                        <div class="spec">
                            <i class="fa-solid fa-building"></i>
                            <div>
                                <div class="label">Stories</div>
                                <div class="value"><?php echo (int)$current_plan['stories']; ?></div>
                            </div>
                        </div>

                        <div class="spec">
                            <i class="fa-solid fa-arrows-left-right-to-line"></i>
                            <div>
                                <div class="label">Dimensions</div>
                                <div class="value"><?php echo htmlspecialchars((string)$current_plan['dimensions']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <?php if (!empty($current_plan['features'])): ?>
                    <div class="block">
                        <h3 class="block-title">Key Features</h3>
                        <ul class="feature-list">
                            <?php foreach ($current_plan['features'] as $feature): ?>
                                <li><i class="fa-solid fa-check-circle"></i> <?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </section>

    <!-- Tabs -->
    <section class="container">
        <div class="card tabs-card">
            <div class="tabs-header" role="tablist">
                <button class="tab-btn active" onclick="openTab(event, 'description')" role="tab">Full Description</button>
                <button class="tab-btn" onclick="openTab(event, 'floor-plan')" role="tab">Floor Plan</button>
                <button class="tab-btn" onclick="openTab(event, 'whats-included')" role="tab">What's Included</button>
            </div>

            <div class="tabs-body">
                <div id="description" class="tab-content active">
                    <h3>Detailed Description</h3>
                    <p><?php echo nl2br(htmlspecialchars((string)$current_plan['full_desc'])); ?></p>

                    <h4 class="subhead">Plan Highlights</h4>
                    <div class="highlights">
                        <div class="highlight">
                            <i class="fa-solid fa-home"></i>
                            <div>
                                <div class="label">Style</div>
                                <div class="value"><?php echo htmlspecialchars((string)$current_plan['style']); ?></div>
                            </div>
                        </div>
                        <div class="highlight">
                            <i class="fa-solid fa-ruler"></i>
                            <div>
                                <div class="label">Total Area</div>
                                <div class="value"><?php echo htmlspecialchars((string)$current_plan['sqm']); ?> m2</div>
                            </div>
                        </div>
                        <div class="highlight">
                            <i class="fa-solid fa-layer-group"></i>
                            <div>
                                <div class="label">Levels</div>
                                <div class="value"><?php echo (int)$current_plan['stories']; ?> <?php echo ((int)$current_plan['stories'] > 1) ? 'Levels' : 'Level'; ?></div>
                            </div>
                        </div>
                        <div class="highlight">
                            <i class="fa-solid fa-expand"></i>
                            <div>
                                <div class="label">Plot Size</div>
                                <div class="value"><?php echo htmlspecialchars((string)$current_plan['dimensions']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="floor-plan" class="tab-content">
                    <h3>Floor Plan Layout</h3>

                    <div class="floor-wrap">
                        <?php if (!empty($current_plan['floor_plan'])): ?>
                            <img
                                src="<?php echo htmlspecialchars((string)$current_plan['floor_plan']); ?>"
                                alt="Floor Plan for <?php echo htmlspecialchars($current_plan['name']); ?>"
                                class="floor-img"
                                loading="lazy"
                            >
                        <?php else: ?>
                            <div class="floor-placeholder">
                                <i class="fa-solid fa-drafting-compass"></i>
                                <p>Floor plan image included with purchase</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="note">
                        <i class="fa-solid fa-info-circle"></i>
                        <p>Detailed architectural floor plans with measurements are included in your purchase. Plans can be customized to suit your specific requirements.</p>
                    </div>
                </div>

                <div id="whats-included" class="tab-content">
                    <h3>What's Included in Your Purchase</h3>

                    <div class="included-grid">
                        <div class="included">
                            <i class="fa-solid fa-file-pdf"></i>
                            <h4>PDF Plans</h4>
                            <p>Complete architectural drawings in high-resolution PDF format</p>
                        </div>
                        <div class="included">
                            <i class="fa-solid fa-compass-drafting"></i>
                            <h4>CAD Files</h4>
                            <p>Editable AutoCAD DWG files for customization</p>
                        </div>
                        <div class="included">
                            <i class="fa-solid fa-list-check"></i>
                            <h4>Bill of Quantities</h4>
                            <p>Detailed material list for accurate cost estimation</p>
                        </div>
                        <div class="included">
                            <i class="fa-solid fa-cube"></i>
                            <h4>3D Renders</h4>
                            <p>High-quality 3D visualization images</p>
                        </div>
                        <div class="included">
                            <i class="fa-solid fa-plug"></i>
                            <h4>Electrical Layout</h4>
                            <p>Complete electrical plan with outlet positions</p>
                        </div>
                        <div class="included">
                            <i class="fa-solid fa-faucet-drip"></i>
                            <h4>Plumbing Layout</h4>
                            <p>Plumbing plan showing all connections</p>
                        </div>
                        <div class="included">
                            <i class="fa-solid fa-window-maximize"></i>
                            <h4>Window & Door Schedule</h4>
                            <p>Detailed specifications for all openings</p>
                        </div>
                        <div class="included">
                            <i class="fa-solid fa-headset"></i>
                            <h4>Support</h4>
                            <p>Email support for any questions about your plan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related -->
    <section class="related">
        <div class="container">
            <div class="related-head">
                <h2>You May Also Like</h2>
            </div>

            <div class="plans-grid">
                <?php foreach ($related as $plan): ?>
                    <div class="plan-card">
                        <div class="plan-img">
                            <img src="<?php echo htmlspecialchars($plan['img']); ?>" alt="<?php echo htmlspecialchars($plan['name']); ?>" loading="lazy">
                        </div>
                        <div class="plan-info">
                            <h3><?php echo htmlspecialchars($plan['name']); ?></h3>
                            <div class="plan-desc"><?php echo htmlspecialchars($plan['short_desc']); ?></div>
                            <div class="plan-pricing">
                                <?php if ((float)$plan['old_price'] > 0): ?>
                                    <span class="plan-old">R<?php echo number_format((float)$plan['old_price'], 2); ?></span>
                                <?php endif; ?>
                                <span class="plan-new">R<?php echo number_format((float)$plan['new_price'], 2); ?></span>
                            </div>
                            <div class="plan-actions">
                                <a href="plan_details?id=<?php echo urlencode($plan['id']); ?>" class="cta-btn">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<script>
    function changeImage(src, el) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    function openTab(evt, tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabName).classList.add('active');
        evt.currentTarget.classList.add('active');
    }

    function addToWishlist(evt, planId) {
        const btn = evt.currentTarget;
        const icon = btn.querySelector('i');
        icon.classList.toggle('fa-regular');
        icon.classList.toggle('fa-solid');
        btn.classList.toggle('is-active');
    }
</script>

<style>
    :root{
        --bg: #0b0f14;
        --card: #0f1621;
        --card2: #0c121b;
        --text: #e9eef5;
        --muted: rgba(233,238,245,.72);
        --line: rgba(233,238,245,.10);
        --brand: #0d6efd;
        --brand2: #22c55e;
        --shadow: 0 10px 30px rgba(0,0,0,.35);
        --r: 16px;
    }

    .page{ background: transparent; }

    .container{
        max-width: 1180px;
        margin: 0 auto;
        padding: 0 16px;
    }

    /* Breadcrumb */
    .breadcrumb{
        display:flex;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
        padding: 18px 0 6px;
        color: var(--muted);
        font-size: 13px;
    }
    .breadcrumb a{ color: var(--muted); text-decoration:none; }
    .breadcrumb a:hover{ color: var(--text); }
    .breadcrumb .sep{ opacity:.6; font-size: 11px; }
    .breadcrumb .current{ color: var(--text); font-weight: 600; }

    /* Layout */
    .plan-page{ padding: 16px 0 18px; }
    .plan-layout{
        display:grid;
        grid-template-columns: 1.05fr .95fr;
        gap: 18px;
        align-items:start;
    }

    .card{
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: var(--r);
        box-shadow: 0 10px 24px rgba(0,0,0,.06);
        overflow:hidden;
    }

    /* Gallery */
    .gallery-card{ padding: 14px; }
    .gallery-main{
        position:relative;
        border-radius: 14px;
        overflow:hidden;
        border: 1px solid #e9ecef;
        background: #f8f9fa;
    }
    .gallery-main img{
        width:100%;
        height: 420px;
        object-fit: cover;
        display:block;
    }
    .badge{
        position:absolute;
        top: 12px;
        left: 12px;
        padding: 8px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing:.3px;
        color:#fff;
        background: rgba(13,110,253,.95);
        box-shadow: 0 10px 20px rgba(13,110,253,.25);
    }
    .badge-discount{ background: rgba(220,53,69,.95); box-shadow: 0 10px 20px rgba(220,53,69,.22); }

    .gallery-thumbs{
        display:grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 10px;
        margin-top: 12px;
    }
    .thumb{
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 0;
        background: #fff;
        cursor:pointer;
        overflow:hidden;
        transition: transform .12s ease, border-color .12s ease, box-shadow .12s ease;
    }
    .thumb img{ width:100%; height: 78px; object-fit: cover; display:block; }
    .thumb:hover{ transform: translateY(-2px); box-shadow: 0 10px 18px rgba(0,0,0,.08); }
    .thumb.active{ border-color: rgba(13,110,253,.55); box-shadow: 0 0 0 3px rgba(13,110,253,.15); }

    /* Details */
    .details-card{ padding: 18px; }
    .sku{
        display:inline-flex;
        padding: 6px 12px;
        background: #f1f3f5;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing:.3px;
        color: #495057;
    }
    .title{
        margin: 10px 0 10px;
        font-size: 30px;
        line-height: 1.15;
    }

    .price-row{ display:flex; gap: 14px; align-items: flex-end; flex-wrap: wrap; }
    .price{
        display:flex;
        align-items: baseline;
        gap: 10px;
    }
    .price .old{
        color:#868e96;
        text-decoration: line-through;
        font-weight: 700;
    }
    .price .new{
        font-size: 26px;
        font-weight: 900;
        color:#111827;
    }
    .save{
        font-size: 13px;
        color:#0f5132;
        background: #d1e7dd;
        border: 1px solid #badbcc;
        padding: 8px 10px;
        border-radius: 12px;
    }

    .summary{
        margin: 14px 0 16px;
        padding: 14px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        color:#343a40;
    }

    /* Buttons */
    .cta-row{
        display:flex;
        gap: 10px;
        align-items:center;
        flex-wrap: wrap;
        margin: 6px 0 14px;
    }
    .btn{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap: 10px;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid transparent;
        text-decoration:none;
        font-weight: 800;
        cursor:pointer;
        transition: transform .12s ease, box-shadow .12s ease, background .12s ease, border-color .12s ease;
        min-height: 44px;
    }
    .btn-primary{
        background: #0d6efd;
        color:#fff;
        box-shadow: 0 10px 18px rgba(13,110,253,.20);
    }
    .btn-primary:hover{ transform: translateY(-1px); box-shadow: 0 14px 22px rgba(13,110,253,.24); }
    .btn-dark{
        background:#111827;
        color:#fff;
        box-shadow: 0 10px 18px rgba(17,24,39,.18);
    }
    .btn-dark:hover{ transform: translateY(-1px); box-shadow: 0 14px 22px rgba(17,24,39,.22); }
    .btn-ghost{
        background:#fff;
        border-color:#e9ecef;
        color:#111827;
        width: 46px;
        padding: 0;
    }
    .wishlist-btn.is-active{ border-color: rgba(220,53,69,.45); color:#dc3545; }
    .wishlist-btn.is-active i{ color:#dc3545; }

    /* Trust */
    .trust{
        display:grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
        margin-top: 10px;
    }
    .trust-item{
        display:flex;
        gap: 10px;
        align-items:center;
        padding: 10px 12px;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        background: #fff;
        font-size: 13px;
        color:#343a40;
    }
    .trust-item i{ color:#0d6efd; }

    .divider{
        height: 1px;
        background: #e9ecef;
        margin: 16px 0;
    }

    .block{ margin-top: 10px; }
    .block-title{ margin: 0 0 10px; font-size: 16px; }

    .spec-grid{
        display:grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }
    .spec{
        display:flex;
        gap: 10px;
        padding: 12px;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        background: #fff;
    }
    .spec i{ color:#0d6efd; margin-top: 2px; }
    .label{ font-size: 12px; color:#6c757d; font-weight: 800; text-transform: uppercase; letter-spacing:.5px; }
    .value{ font-weight: 900; color:#111827; }

    .feature-list{
        list-style:none;
        padding:0;
        margin: 0;
        display:grid;
        gap: 10px;
    }
    .feature-list li{
        display:flex;
        gap: 10px;
        align-items:flex-start;
        padding: 10px 12px;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        background: #fff;
        color:#343a40;
    }
    .feature-list i{ color:#22c55e; margin-top: 2px; }

    /* Tabs */
    .tabs-card{ margin-top: 18px; }
    .tabs-header{
        display:flex;
        gap: 8px;
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        background: #fbfcfe;
        overflow:auto;
    }
    .tab-btn{
        border: 1px solid #e9ecef;
        background:#fff;
        color:#495057;
        padding: 10px 12px;
        border-radius: 999px;
        font-weight: 900;
        cursor:pointer;
        white-space: nowrap;
        transition: background .12s ease, border-color .12s ease, transform .12s ease;
    }
    .tab-btn:hover{ transform: translateY(-1px); }
    .tab-btn.active{
        background: rgba(13,110,253,.10);
        border-color: rgba(13,110,253,.35);
        color:#0d6efd;
    }
    .tabs-body{ padding: 16px; }
    .tab-content{ display:none; }
    .tab-content.active{ display:block; }
    .subhead{ margin-top: 14px; }

    .highlights{
        display:grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-top: 10px;
    }
    .highlight{
        display:flex;
        gap: 10px;
        padding: 12px;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        background:#fff;
    }
    .highlight i{ color:#0d6efd; margin-top: 2px; }

    /* Floor */
    .floor-wrap{ margin-top: 10px; text-align:center; }
    .floor-img{
        max-width: 100%;
        border-radius: 14px;
        border: 1px solid #e9ecef;
        box-shadow: 0 10px 24px rgba(0,0,0,.07);
    }
    .floor-placeholder{
        padding: 52px 16px;
        border-radius: 14px;
        border: 1px dashed #ced4da;
        background:#f8f9fa;
        color:#6c757d;
    }
    .floor-placeholder i{ font-size: 54px; color:#adb5bd; margin-bottom: 10px; }

    .note{
        display:flex;
        gap: 10px;
        margin-top: 12px;
        padding: 12px;
        border-radius: 14px;
        background:#e7f3ff;
        border: 1px solid #cfe2ff;
        color:#084298;
    }
    .note i{ color:#0d6efd; margin-top: 2px; }

    /* Included */
    .included-grid{
        margin-top: 12px;
        display:grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }
    .included{
        padding: 14px;
        border-radius: 16px;
        border: 1px solid #e9ecef;
        background:#fff;
        box-shadow: 0 10px 20px rgba(0,0,0,.05);
    }
    .included i{ color:#0d6efd; font-size: 22px; }
    .included h4{ margin: 10px 0 6px; font-size: 14px; }
    .included p{ margin: 0; color:#6c757d; font-size: 13px; line-height: 1.45; }

    /* Related */
    .related{ padding: 22px 0 40px; }
    .related-head{ display:flex; align-items:center; justify-content:space-between; padding: 0 0 10px; }

    /* Responsive */
    @media (max-width: 1024px){
        .plan-layout{ grid-template-columns: 1fr; }
        .gallery-main img{ height: 360px; }
        .included-grid{ grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 560px){
        .gallery-thumbs{ grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .thumb img{ height: 66px; }
        .spec-grid, .highlights{ grid-template-columns: 1fr; }
        .trust{ grid-template-columns: 1fr; }
        .title{ font-size: 24px; }
    }
</style>

<?php include 'includes/footer.php'; ?>
