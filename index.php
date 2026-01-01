<?php include 'includes/header.php'; ?>
<main>
    <section class="hero-section container">
        <!-- ✅ Black overlay -->
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <h1>Welcome to Reflex Group</h1>
            <p class="subtitle">Innovative Architecture, Engineering, and Construction Solutions for a Sustainable Future.</p>
            <a href="#about" class="cta-btn">Learn More</a>
        </div>
    </section>

    <section id="about" class="info-section container">
        <div class="info-content">
            <h2>About Us</h2>
            <p>Founded in 2020 during the COVID-19 pandemic, Reflex Perspectives emerged as a forwardthinking multi-disciplinary firm with a vision to adapt and thrive in challenging times. Initially
                focused on supply and delivery services to meet the heightened demand for essential goods
                during the pandemic, we quickly recognized opportunities to expand our offerings into various
                complementary sectors, including architecture, civil and mechanical engineering, automotive
                repairs, construction, and cleaning services.</br></br>
                Our mission at Reflex Perspectives is to enhance the quality of life and functionality within our
                communities by integrating expertise and creativity into every project we undertake. Our diverse
                team of professionals brings a wealth of knowledge and experience, allowing us to approach
                challenges from multiple angles and provide comprehensive, tailored solutions for our clients.
                In our automotive division, we offer a full suite of services to assist our clients with their vehicle
                needs. This includes parts supply, diagnostic services, planned repairs, and support for
                breakdowns, ensuring that our customers have access to reliable and efficient automotive
                solutions. Our team is committed to providing high-quality service and expertise, so our clients
                can feel confident about the safety and performance of their vehicles.</br></br>
                In addition to our automotive services, we excel in architectural design and engineering, with a
                strong emphasis on quality and sustainability. Our dedicated cleaning team works tirelessly to
                leave every space we touch immaculate, promoting health and well-being for all, while our
                construction professionals ensure that every project adheres to the highest standards of safety
                and quality.
        </div>
        <div class="info-image">
            <img src="images/about.png" alt="About Us">
        </div>
        </div>
    </section>

    <section id="plans" class="plans-section">
        <h2 class="plans-title">Featured Building Plans</h2>
        <div class="plans-grid">
            <?php include 'plans.php';
            foreach ($plans as $plan): ?>
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
                            <a href="cart.php?add=<?php echo $plan['id']; ?>" class="cta-btn">Add to Cart</a>
                            <a href="plan_details.php?id=<?php echo $plan['id']; ?>" class="details-link">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<style>
    .info-section {
        padding: 48px;
        margin-bottom: 32px;
        box-shadow: 0 1px 6px rgba(20, 60, 111, 0.045);
        display: flex;
        flex-direction: row;
    }

    .info-content {
        flex: 1;
    }

    .info-image {
        flex: 1;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-section {
        background-image: url('images/cover.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 100vw;
        margin-left: calc(-50vw + 50%);
        padding: 8px 0 54px 0;
        text-align: center;

        /* ✅ needed for overlay layering */
        position: relative;
        overflow: hidden;
        color: #fff;
    }

    /* ✅ overlay */
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.3);
        /* adjust darkness here */
        z-index: 1;
    }

    /* ✅ keep content above overlay */
    .hero-content {
        position: relative;
        z-index: 2;
    }
</style>

<?php include 'includes/footer.php'; ?>