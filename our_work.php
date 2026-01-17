<?php include 'includes/header.php'; 
include 'services-data.php';
?>
<main>
  <section class="hero-section services-hero">
    <div class="hero-content">
      <h1>Our Core Services</h1>
      <p class="subtitle">Comprehensive solutions across multiple industries to meet all your business needs</p>
    </div>
  </section>

  <section class="info-section services-section">
    <div class="services-grid">
      <?php foreach ($services as $service): ?>
        <div class="service-card">
          <div class="service-image-wrapper">
            <img src="<?php echo $service['image']; ?>" 
                 alt="<?php echo htmlspecialchars($service['name']); ?>" 
                 onerror="this.src='https://via.placeholder.com/400x250/143c6f/ffffff?text=<?php echo urlencode($service['name']); ?>'">
          </div>
          <div class="service-content">
            <h3><?php echo htmlspecialchars($service['name']); ?></h3>
            <p class="service-description"><?php echo htmlspecialchars($service['description']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>

<style>
  .services-hero {
    background: linear-gradient(135deg, #143c6f 0%, #4781b7 100%);
    padding: 60px 0 40px 0;
    margin-bottom: 40px;
  }
  
  .services-section {
    padding: 40px 20px;
    max-width: 1400px;
    margin: 0 auto;
  }
  
  .services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-top: 20px;
  }
  
  .service-card {
    background: #fff;
    border-radius: 0;
    box-shadow: 0 4px 20px rgba(20, 60, 111, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e6eeff;
    display: flex;
    flex-direction: column;
  }
  
  .service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 30px rgba(20, 60, 111, 0.15);
  }
  
  .service-image-wrapper {
    position: relative;
    width: 100%;
    height: 220px;
    overflow: hidden;
    background: linear-gradient(135deg, #f0f4fa 0%, #e6eeff 100%);
  }
  
  .service-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
  }
  
  .service-card:hover .service-image-wrapper img {
    transform: scale(1.1);
  }
  
  .service-icon-overlay {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(20, 60, 111, 0.9);
    color: #fff;
    width: 50px;
    height: 50px;
    border-radius: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  }
  
  .service-content {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }
  
  .service-content h3 {
    color: #143c6f;
    font-size: 1.3rem;
    margin: 0 0 12px 0;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .service-content h3 i {
    color: #4781b7;
  }
  
  .service-description {
    color: #305080;
    font-size: 1rem;
    line-height: 1.6;
    margin: 0 0 16px 0;
    flex: 1;
  }
  
  .service-benefits {
    background: #f6f9fc;
    padding: 14px;
    border-radius: 0;
    border-left: 4px solid #143c6f;
    margin-top: auto;
  }
  
  .service-benefits strong {
    color: #143c6f;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 6px;
    font-size: 0.95rem;
  }
  
  .service-benefits p {
    color: #4a5568;
    font-size: 0.92rem;
    margin: 0;
    line-height: 1.5;
  }
  
  @media (max-width: 768px) {
    .services-grid {
      grid-template-columns: 1fr;
      gap: 20px;
    }
    
    .services-hero {
      padding: 40px 20px;
    }
    
    .service-image-wrapper {
      height: 200px;
    }
  }
</style>

<?php include 'includes/footer.php'; ?>
