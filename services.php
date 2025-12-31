<?php include 'includes/header.php'; ?>
<main>
  <section class="info-section">
    <h2><i class="fa-solid fa-screwdriver-wrench"></i> Core Services and Products</h2>
    <div class="plans-grid">
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/labour-hire.jpg" alt="Labour Hire Services">
        </div>
        <h3><i class="fa-solid fa-users"></i> Labour Hire (Man Power)</h3>
        <p>Provision of skilled and unskilled labor for tailored project needs.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/welding.jpg" alt="Welding Work Services">
        </div>
        <h3><i class="fa-solid fa-fire-burner"></i> Welding Work</h3>
        <p>Professional welding for all equipment, with safety and precision.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/mechanical.jpg" alt="Mechanical Services">
        </div>
        <h3><i class="fa-solid fa-cogs"></i> Mechanical Services</h3>
        <p>Comprehensive diagnostics, maintenance, and immediate breakdown support.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/equipment-monitoring.jpg" alt="Equipment Monitoring">
        </div>
        <h3><i class="fa-solid fa-desktop"></i> Equipment Monitoring</h3>
        <p>Real-time data and analytics for operational uptime and diagnostics.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/fuel-supplies.jpg" alt="Fuel and Diesel Supplies">
        </div>
        <h3><i class="fa-solid fa-gas-pump"></i> Fuel / Diesel Supplies</h3>
        <p>Reliable supply and delivery of industrial fuel and diesel.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/steel-supplies.jpg" alt="Steel Supplies">
        </div>
        <h3><i class="fa-solid fa-industry"></i> Steel Supplies</h3>
        <p>Quality steel products for construction, fabrication, and manufacturing.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/line-boring.jpg" alt="Line Boring Services">
        </div>
        <h3><i class="fa-solid fa-screwdriver"></i> Line Boring Services</h3>
        <p>Maintenance/repairs for heavy equipment bores.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/cleaning.jpg" alt="Cleaning Services">
        </div>
        <h3><i class="fa-solid fa-broom"></i> Cleaning Services</h3>
        <p>Industrial, commercial, and residential cleaning, with high hygiene standards.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/parts-supplies.jpg" alt="Parts Supplies">
        </div>
        <h3><i class="fa-solid fa-box"></i> Parts Supplies</h3>
        <p>Inventory of parts and accessories for all equipment/machinery types.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/laundry.jpg" alt="Laundry Services">
        </div>
        <h3><i class="fa-solid fa-shirt"></i> Laundry Services</h3>
        <p>Uniform and textile laundry for cleanliness and hygiene.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/oils-lubricants.jpg" alt="Oils and Lubricants">
        </div>
        <h3><i class="fa-solid fa-oil-can"></i> Oils and Lubricants</h3>
        <p>High-performance oils and lubricants for machinery and engines.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/architecture.jpg" alt="Architecture and Construction">
        </div>
        <h3><i class="fa-solid fa-drafting-compass"></i> Architecture & Construction</h3>
        <p>Design, management, and execution of quality architecture/construction.</p>
      </div>
    </div>
  </section>
</main>
<style>
  /* Service Card Image Styles */
  .card-image {
    width: 100%;
    height: 180px;
    overflow: hidden;
    border-radius: 8px 8px 0 0;
    margin-bottom: 15px;
  }

  .card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .plan-card:hover .card-image img {
    transform: scale(1.05);
  }

  /* Optional: Add overlay effect */
  .card-image {
    position: relative;
  }

  .card-image::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, transparent 60%, rgba(0, 0, 0, 0.3));
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .plan-card:hover .card-image::after {
    opacity: 1;
  }
</style>
<?php include 'includes/footer.php'; ?>