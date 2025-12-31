<?php
include 'includes/header.php';
include 'plans.php';

// Get plan ID from URL (now handles string IDs)
$plan_id = isset($_GET['id']) ? $_GET['id'] : '';

// Find the plan using helper function
$current_plan = getPlanById($plans, $plan_id);

// Redirect if plan not found
if (!$current_plan) {
    header('Location: index.php');
    exit;
}

// Calculate discount percentage
$discount = round((($current_plan['old_price'] - $current_plan['new_price']) / $current_plan['old_price']) * 100);
?>

<main>
    <!-- Breadcrumb Navigation -->
    <section class="breadcrumb-section container">
        <nav class="breadcrumb">
            <a href="index.php">Home</a>
            <span class="separator"><i class="fa-solid fa-chevron-right"></i></span>
            <a href="index.php#plans">Building Plans</a>
            <span class="separator"><i class="fa-solid fa-chevron-right"></i></span>
            <span class="current"><?php echo htmlspecialchars($current_plan['name']); ?></span>
        </nav>
    </section>

    <!-- Plan Details Section -->
    <section class="plan-details-section container">
        <div class="plan-details-grid">
            <!-- Image Gallery -->
            <div class="plan-gallery">
                <div class="main-image">
                    <img src="<?php echo $current_plan['img']; ?>" alt="<?php echo htmlspecialchars($current_plan['name']); ?>" id="mainImage">
                    <?php if ($discount > 0): ?>
                        <span class="discount-badge">-<?php echo $discount; ?>% OFF</span>
                    <?php endif; ?>
                </div>
                <?php if (isset($current_plan['gallery']) && is_array($current_plan['gallery'])): ?>
                    <div class="thumbnail-gallery">
                        <div class="thumbnail active" onclick="changeImage('<?php echo $current_plan['img']; ?>', this)">
                            <img src="<?php echo $current_plan['img']; ?>" alt="Main view">
                        </div>
                        <?php foreach ($current_plan['gallery'] as $image): ?>
                            <div class="thumbnail" onclick="changeImage('<?php echo $image; ?>', this)">
                                <img src="<?php echo $image; ?>" alt="Gallery image">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Plan Information -->
            <div class="plan-info-details">
                <span class="plan-sku">SKU: <?php echo strtoupper($current_plan['id']); ?></span>
                <h1 class="plan-title"><?php echo htmlspecialchars($current_plan['name']); ?></h1>

                <div class="plan-rating">
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                    <span class="rating-text">(4.5 / 5 based on 24 reviews)</span>
                </div>

                <div class="plan-pricing-details">
                    <span class="old-price">R<?php echo number_format($current_plan['old_price'], 2); ?></span>
                    <span class="new-price">R<?php echo number_format($current_plan['new_price'], 2); ?></span>
                    <?php if ($discount > 0): ?>
                        <span class="savings">You save R<?php echo number_format($current_plan['old_price'] - $current_plan['new_price'], 2); ?> (<?php echo $discount; ?>%)</span>
                    <?php endif; ?>
                </div>

                <div class="plan-quick-specs">
                    <p><?php echo $current_plan['desc']; ?></p>
                </div>

                <div class="plan-description">
                    <h3>Description</h3>
                    <p><?php echo isset($current_plan['full_desc']) ? $current_plan['full_desc'] : $current_plan['desc']; ?></p>
                </div>

                <!-- Plan Specifications -->
                <div class="plan-specifications">
                    <h3>Specifications</h3>
                    <div class="specs-grid">
                        <div class="spec-item">
                            <i class="fa-solid fa-bed"></i>
                            <span class="spec-label">Bedrooms</span>
                            <span class="spec-value"><?php echo $current_plan['bedrooms']; ?></span>
                        </div>

                        <div class="spec-item">
                            <i class="fa-solid fa-bath"></i>
                            <span class="spec-label">Bathrooms</span>
                            <span class="spec-value"><?php echo $current_plan['bathrooms']; ?></span>
                        </div>

                        <div class="spec-item">
                            <i class="fa-solid fa-car"></i>
                            <span class="spec-label">Garage</span>
                            <span class="spec-value"><?php echo $current_plan['garage']; ?> Car</span>
                        </div>

                        <div class="spec-item">
                            <i class="fa-solid fa-ruler-combined"></i>
                            <span class="spec-label">Floor Area</span>
                            <span class="spec-value"><?php echo $current_plan['sqm']; ?> m²</span>
                        </div>

                        <div class="spec-item">
                            <i class="fa-solid fa-building"></i>
                            <span class="spec-label">Stories</span>
                            <span class="spec-value"><?php echo $current_plan['stories']; ?></span>
                        </div>

                        <div class="spec-item">
                            <i class="fa-solid fa-arrows-left-right-to-line"></i>
                            <span class="spec-label">Dimensions</span>
                            <span class="spec-value"><?php echo $current_plan['dimensions']; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <?php if (isset($current_plan['features']) && is_array($current_plan['features'])): ?>
                    <div class="plan-features">
                        <h3>Key Features</h3>
                        <ul class="features-list">
                            <?php foreach ($current_plan['features'] as $feature): ?>
                                <li><i class="fa-solid fa-check-circle"></i> <?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="plan-actions-details">
                    <div class="action-buttons">
                        <a href="cart.php?add=<?php echo $current_plan['id']; ?>" class="cta-btn add-to-cart">
                            <i class="fa-solid fa-cart-plus"></i> Add to Cart
                        </a>
                        <a href="checkout.php?buy=<?php echo $current_plan['id']; ?>" class="cta-btn buy-now">
                            <i class="fa-solid fa-bolt"></i> Buy Now
                        </a>
                        <button class="wishlist-btn" onclick="addToWishlist('<?php echo $current_plan['id']; ?>')">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="trust-badges">
                    <div class="badge">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Secure Payment</span>
                    </div>
                    <div class="badge">
                        <i class="fa-solid fa-download"></i>
                        <span>Instant Download</span>
                    </div>
                    <div class="badge">
                        <i class="fa-solid fa-headset"></i>
                        <span>24/7 Support</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tabs Section -->
    <section class="plan-tabs-section container">
        <div class="tabs-header">
            <button class="tab-btn active" onclick="openTab(event, 'description')">Full Description</button>
            <button class="tab-btn" onclick="openTab(event, 'floor-plan')">Floor Plan</button>
            <button class="tab-btn" onclick="openTab(event, 'whats-included')">What's Included</button>
            <button class="tab-btn" onclick="openTab(event, 'reviews')">Reviews (24)</button>
        </div>

        <div id="description" class="tab-content active">
            <h3>Detailed Description</h3>
            <p><?php echo isset($current_plan['full_desc']) ? $current_plan['full_desc'] : $current_plan['desc']; ?></p>

            <h4>Plan Highlights</h4>
            <div class="highlights-grid">
                <div class="highlight-item">
                    <i class="fa-solid fa-home"></i>
                    <div>
                        <h5>Style</h5>
                        <p><?php echo $current_plan['style']; ?></p>
                    </div>
                </div>
                <div class="highlight-item">
                    <i class="fa-solid fa-ruler"></i>
                    <div>
                        <h5>Total Area</h5>
                        <p><?php echo $current_plan['sqm']; ?> m²</p>
                    </div>
                </div>
                <div class="highlight-item">
                    <i class="fa-solid fa-layer-group"></i>
                    <div>
                        <h5>Levels</h5>
                        <p><?php echo $current_plan['stories']; ?> <?php echo $current_plan['stories'] > 1 ? 'Levels' : 'Level'; ?></p>
                    </div>
                </div>
                <div class="highlight-item">
                    <i class="fa-solid fa-expand"></i>
                    <div>
                        <h5>Plot Size</h5>
                        <p><?php echo $current_plan['dimensions']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div id="floor-plan" class="tab-content">
            <h3>Floor Plan Layout</h3>
            <div class="floor-plan-container">
                <?php if (isset($current_plan['floor_plan'])): ?>
                    <img src="<?php echo $current_plan['floor_plan']; ?>" alt="Floor Plan for <?php echo htmlspecialchars($current_plan['name']); ?>" class="floor-plan-image">
                <?php else: ?>
                    <div class="floor-plan-placeholder">
                        <i class="fa-solid fa-drafting-compass"></i>
                        <p>Floor plan image included with purchase</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="floor-plan-note">
                <i class="fa-solid fa-info-circle"></i>
                <p>Detailed architectural floor plans with measurements are included in your purchase. Plans can be customized to suit your specific requirements.</p>
            </div>
        </div>

        <div id="whats-included" class="tab-content">
            <h3>What's Included in Your Purchase</h3>
            <div class="included-grid">
                <div class="included-item">
                    <i class="fa-solid fa-file-pdf"></i>
                    <h4>PDF Plans</h4>
                    <p>Complete architectural drawings in high-resolution PDF format</p>
                </div>
                <div class="included-item">
                    <i class="fa-solid fa-compass-drafting"></i>
                    <h4>CAD Files</h4>
                    <p>Editable AutoCAD DWG files for customization</p>
                </div>
                <div class="included-item">
                    <i class="fa-solid fa-list-check"></i>
                    <h4>Bill of Quantities</h4>
                    <p>Detailed material list for accurate cost estimation</p>
                </div>
                <div class="included-item">
                    <i class="fa-solid fa-cube"></i>
                    <h4>3D Renders</h4>
                    <p>High-quality 3D visualization images</p>
                </div>
                <div class="included-item">
                    <i class="fa-solid fa-plug"></i>
                    <h4>Electrical Layout</h4>
                    <p>Complete electrical plan with outlet positions</p>
                </div>
                <div class="included-item">
                    <i class="fa-solid fa-faucet-drip"></i>
                    <h4>Plumbing Layout</h4>
                    <p>Plumbing plan showing all connections</p>
                </div>
                <div class="included-item">
                    <i class="fa-solid fa-window-maximize"></i>
                    <h4>Window & Door Schedule</h4>
                    <p>Detailed specifications for all openings</p>
                </div>
                <div class="included-item">
                    <i class="fa-solid fa-headset"></i>
                    <h4>Support</h4>
                    <p>Email support for any questions about your plan</p>
                </div>
            </div>
        </div>

        <div id="reviews" class="tab-content">
            <h3>Customer Reviews</h3>
            <div class="reviews-summary">
                <div class="overall-rating">
                    <span class="rating-number">4.5</span>
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                    <span class="review-count">Based on 24 reviews</span>
                </div>
                <div class="rating-bars">
                    <div class="rating-bar">
                        <span>5 stars</span>
                        <div class="bar">
                            <div class="fill" style="width: 70%"></div>
                        </div>
                        <span>17</span>
                    </div>
                    <div class="rating-bar">
                        <span>4 stars</span>
                        <div class="bar">
                            <div class="fill" style="width: 20%"></div>
                        </div>
                        <span>5</span>
                    </div>
                    <div class="rating-bar">
                        <span>3 stars</span>
                        <div class="bar">
                            <div class="fill" style="width: 8%"></div>
                        </div>
                        <span>2</span>
                    </div>
                    <div class="rating-bar">
                        <span>2 stars</span>
                        <div class="bar">
                            <div class="fill" style="width: 0%"></div>
                        </div>
                        <span>0</span>
                    </div>
                    <div class="rating-bar">
                        <span>1 star</span>
                        <div class="bar">
                            <div class="fill" style="width: 0%"></div>
                        </div>
                        <span>0</span>
                    </div>
                </div>
            </div>

            <div class="reviews-list">
                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">JM</div>
                            <div>
                                <span class="reviewer-name">John Mokoena</span>
                                <span class="verified-badge"><i class="fa-solid fa-circle-check"></i> Verified Purchase</span>
                            </div>
                        </div>
                        <div class="review-rating">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                    <p class="review-text">Excellent plan! Very detailed and easy to understand. The architect made some minor adjustments for us at no extra cost. Highly recommended!</p>
                    <span class="review-date"><i class="fa-regular fa-calendar"></i> 2 weeks ago</span>
                </div>

                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">SK</div>
                            <div>
                                <span class="reviewer-name">Sarah Khumalo</span>
                                <span class="verified-badge"><i class="fa-solid fa-circle-check"></i> Verified Purchase</span>
                            </div>
                        </div>
                        <div class="review-rating">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <p class="review-text">Great value for money. The CAD files were very helpful for our builder. Would definitely recommend Reflex Perspectives to anyone looking for quality house plans.</p>
                    <span class="review-date"><i class="fa-regular fa-calendar"></i> 1 month ago</span>
                </div>

                <div class="review-item">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <div class="reviewer-avatar">TN</div>
                            <div>
                                <span class="reviewer-name">Thabo Ndlovu</span>
                                <span class="verified-badge"><i class="fa-solid fa-circle-check"></i> Verified Purchase</span>
                            </div>
                        </div>
                        <div class="review-rating">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                    <p class="review-text">Building our dream home was made so much easier with these plans. Everything was clearly laid out and the support team answered all our questions promptly.</p>
                    <span class="review-date"><i class="fa-regular fa-calendar"></i> 2 months ago</span>
                </div>
            </div>

            <button class="load-more-btn">Load More Reviews</button>
        </div>
    </section>

    <!-- Related Plans -->
    <section class="related-plans-section">
        <div class="container">
            <h2>You May Also Like</h2>
            <div class="plans-grid">
                <?php
                $related = getRelatedPlans($plans, $plan_id, 4);
                foreach ($related as $plan):
                ?>
                    <div class="plan-card">
                        <div class="plan-img">
                            <img src="<?php echo $plan['img']; ?>" alt="<?php echo htmlspecialchars($plan['name']); ?>">
                        </div>
                        <div class="plan-info">
                            <h3><?php echo $plan['name']; ?></h3>
                            <div class="plan-desc"><?php echo $plan['desc']; ?></div>
                            <div class="plan-pricing">
                                <span class="plan-old">R<?php echo number_format($plan['old_price'], 2); ?></span>
                                <span class="plan-new">R<?php echo number_format($plan['new_price'], 2); ?></span>
                            </div>
                            <div class="plan-actions">
                                <a href="details.php?id=<?php echo $plan['id']; ?>" class="cta-btn">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<script>
    // Image gallery functionality
    function changeImage(src, element) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
        element.classList.add('active');
    }

    // Tab functionality
    function openTab(evt, tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById(tabName).classList.add('active');
        evt.currentTarget.classList.add('active');
    }

    // Wishlist functionality
    function addToWishlist(planId) {
        const btn = event.currentTarget;
        const icon = btn.querySelector('i');
        icon.classList.toggle('fa-regular');
        icon.classList.toggle('fa-solid');
        btn.classList.toggle('active');
    }
</script>
<style>
    /* SKU */
    .plan-sku {
        display: inline-block;
        background: #e9ecef;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 12px;
        color: #666;
        margin-bottom: 10px;
    }

    /* Quick Specs */
    .plan-quick-specs {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #007bff;
    }

    /* Highlights Grid */
    .highlights-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .highlight-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .highlight-item i {
        font-size: 24px;
        color: #007bff;
    }

    .highlight-item h5 {
        margin: 0 0 5px 0;
        font-size: 14px;
        color: #666;
    }

    .highlight-item p {
        margin: 0;
        font-weight: 600;
    }

    /* Floor Plan */
    .floor-plan-container {
        text-align: center;
        margin: 20px 0;
    }

    .floor-plan-image {
        max-width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .floor-plan-placeholder {
        padding: 60px;
        background: #f8f9fa;
        border-radius: 8px;
        text-align: center;
    }

    .floor-plan-placeholder i {
        font-size: 60px;
        color: #ccc;
        margin-bottom: 15px;
    }

    .floor-plan-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 15px;
        background: #e7f3ff;
        border-radius: 8px;
        margin-top: 20px;
    }

    .floor-plan-note i {
        color: #007bff;
    }

    /* Rating Bars */
    .rating-bars {
        flex: 1;
    }

    .rating-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .rating-bar .bar {
        flex: 1;
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .rating-bar .fill {
        height: 100%;
        background: #f1c40f;
        border-radius: 4px;
    }

    /* Reviewer Avatar */
    .reviewer-avatar {
        width: 45px;
        height: 45px;
        background: #007bff;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .verified-badge {
        display: block;
        font-size: 12px;
        color: #27ae60;
    }

    /* Load More Button */
    .load-more-btn {
        display: block;
        width: 100%;
        padding: 15px;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 20px;
        transition: all 0.3s;
    }

    .load-more-btn:hover {
        background: #e9ecef;
    }

    /* Wishlist Active */
    .wishlist-btn.active {
        color: #e74c3c;
        border-color: #e74c3c;
    }

    .wishlist-btn.active i {
        color: #e74c3c;
    }
</style>

<?php include 'includes/footer.php'; ?>