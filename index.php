<?php include 'includes/header.php'; ?>
<main>
    <section class="hero-section container">
        <div class="hero-content">
            <h1>Welcome to Reflex Perspectives</h1>
            <p class="subtitle">Innovative Architecture, Engineering, and Construction Solutions for a Sustainable Future.</p>
            <a href="#about" class="cta-btn">Learn More</a>
        </div>
    </section>

    <section id="about" class="info-section container">
        <h2>About Us</h2>
        <p>Reflex Perspectives CK is committed to delivering visionary solutions in architecture, engineering, and construction. Our multidisciplinary team transforms ideas into reality with precision and creativity, ensuring client satisfaction at every phase.</p>
    </section>

    <section id="plans" class="plans-section">
        <h2 class="plans-title">Featured Building Plans</h2>
        <div class="plans-grid">
            <?php include 'plans.php'; foreach ($plans as $plan): ?>
            <div class="plan-card">
                <div class="plan-img">
                    <img src="<?php echo $plan['img']; ?>" alt="<?php echo htmlspecialchars($plan['name']); ?>">
                </div>
                <div class="plan-info">
                    <h3><?php echo $plan['name']; ?></h3>
                    <div class="plan-desc"><?php echo $plan['desc']; ?></div>
                    <div class="plan-pricing">
                        <span class="plan-old">R<?php echo number_format($plan['old_price'],2); ?></span>
                        <span class="plan-new">R<?php echo number_format($plan['new_price'],2); ?></span>
                    </div>
                    <div class="plan-actions">
                        <a href="cart.php?add=<?php echo $plan['id']; ?>" class="cta-btn">Add to Cart</a>
                        <a href="#" class="details-link">Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="contact" class="info-section container">
        <h2>Contact Us</h2>
        <p>Email: info@reflexperspectives.co.za &nbsp; | &nbsp; Phone: +27 11 000 0000</p>
        <a href="contact.php" class="cta-btn secondary">Get in Touch</a>
    </section>
</main>
<?php include 'includes/footer.php'; ?>