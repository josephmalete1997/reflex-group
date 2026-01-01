<?php include 'includes/header.php'; ?>
<main>
  <section class="info-section">
    <h2>Our core Services</h2>
    <div class="plans-grid">
      <div class="plan-card">
        <div class="card-image">
          <img src="services/man_power.png" alt="Labour Hire Services">
        </div>
        <h3> Labour Hire (Man Power)</h3>
        <p>Provision of skilled and unskilled labor for tailored project needs.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="services/welding.png" alt="Welding Work Services">
        </div>
        <h3> Welding Work</h3>
        <p>Professional welding for all equipment, with safety and precision.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="services/mechanic.png" alt="Mechanical Services">
        </div>
        <h3> Mechanical Services</h3>
        <p>Comprehensive diagnostics, maintenance, and immediate breakdown support.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="services/equipment monitoring.png" alt="Equipment Monitoring">
        </div>
        <h3> Equipment Monitoring</h3>
        <p>Real-time data and analytics for operational uptime and diagnostics.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="services/fuel.png" alt="Fuel and Diesel Supplies">
        </div>
        <h3> Fuel / Diesel Supplies</h3>
        <p>Reliable supply and delivery of industrial fuel and diesel.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="services/steel.png" alt="Steel Supplies">
        </div>
        <h3> Steel Supplies</h3>
        <p>Quality steel products for construction, fabrication, and manufacturing.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="services/line_boring.png" alt="Line Boring Services">
        </div>
        <h3> Line Boring Services</h3>
        <p>Maintenance/repairs for heavy equipment bores.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="services/cleaning.png" alt="Cleaning Services">
        </div>
        <h3> Cleaning Services</h3>
        <p>Industrial, commercial, and residential cleaning, with high hygiene standards.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="services/parts.png" alt="Parts Supplies">
        </div>
        <h3> Parts Supplies</h3>
        <p>Inventory of parts and accessories for all equipment/machinery types.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/laundry.jpg" alt="Laundry Services">
        </div>
        <h3> Laundry Services</h3>
        <p>Uniform and textile laundry for cleanliness and hygiene.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/oils-lubricants.jpg" alt="Oils and Lubricants">
        </div>
        <h3> Oils and Lubricants</h3>
        <p>High-performance oils and lubricants for machinery and engines.</p>
      </div>
      <div class="plan-card">
        <div class="card-image">
          <img src="images/services/architecture.jpg" alt="Architecture and Construction">
        </div>
        <h3> Architecture & Construction</h3>
        <p>Design, management, and execution of quality architecture/construction.</p>
      </div>
    </div>
  </section>
</main>
<style>
  /* Service Card Image Styles */
  .plan-card h3,
  .plan-card p{
    padding: 10px;
    margin: 0px;
  }
  .card-image {
    width: 100%;
    height: 210px;
    overflow: hidden;
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