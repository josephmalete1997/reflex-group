<?php include 'includes/header.php'; ?>
<main>
<section class="hero-section">
  <div class="hero-content">
    <h1>Get In Touch</h1>
    <p class="subtitle">We're here to help you with all your business needs</p>
  </div>
</section>

<section class="info-section">
  <h2>Contact Information</h2>
  <div class="contact-grid">
    <div class="contact-card">
      <h3>Address</h3>
      <p>6986 Xidweta Street<br>Bela-Bela, 0480<br>South Africa</p>
    </div>
    <div class="contact-card">
      <h3>Email</h3>
      <p><a href="mailto:simon.kgaugelo94@gmail.com">simon.kgaugelo94@gmail.com</a></p>
    </div>
    <div class="contact-card">
      <h3>Phone</h3>
      <p><a href="tel:0670730755">067 073 0755</a><br><a href="tel:0649911270">064 991 1270</a></p>
    </div>
  </div>
  
  <div style="margin-top: 48px; text-align: center;">
    <h3 style="color: #143c6f; margin-bottom: 16px;">Our Motto</h3>
    <blockquote style="font-size: 1.3rem; margin: 0 auto; max-width: 600px;">Uncovering Opportunities, Building Foundations</blockquote>
  </div>
</section>
</main>

<style>
.contact-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
  margin-top: 32px;
}
.contact-card {
  background: #f6f9fc;
  padding: 32px 24px;
  border-radius: 0;
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border: 2px solid #e6eeff;
}
.contact-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(20, 60, 111, 0.12);
  border-color: #cfe0ff;
}
.contact-icon {
  width: 70px;
  height: 70px;
  background: linear-gradient(135deg, #143c6f, #4781b7);
  border-radius: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  color: #fff;
  font-size: 28px;
  box-shadow: 0 4px 12px rgba(20, 60, 111, 0.2);
}
.contact-card h3 {
  color: #143c6f;
  margin: 0 0 12px 0;
  font-size: 1.2rem;
}
.contact-card p {
  color: #4a5568;
  margin: 0;
  line-height: 1.8;
}
.contact-card a {
  color: #4781b7;
  text-decoration: none;
  transition: color 0.2s;
}
.contact-card a:hover {
  color: #143c6f;
  text-decoration: underline;
}
@media (max-width: 768px) {
  .contact-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<?php include 'includes/footer.php'; ?>
