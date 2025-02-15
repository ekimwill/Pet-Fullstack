<?php
session_start();
require_once 'db.php';

$loggedInUser = isset($_COOKIE['loggedInUser']) ? $_COOKIE['loggedInUser'] : null;

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$catProducts = array_filter($products, function($prod) {
    return strtolower($prod['category']) === 'cat';
});
$dogProducts = array_filter($products, function($prod) {
    return strtolower($prod['category']) === 'dog';
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Pet Store | Home">
  <meta name="keywords" content="Pet Store, Animal Supplies, Adoption, Shop, Care Tips, About">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pet Store | Home</title>
  <link rel="stylesheet" href="css/style.css">
  <script>
    var loggedIn = <?php echo $loggedInUser ? 'true' : 'false'; ?>;
  </script>
  <script src="js/main.js" defer></script>
</head>
<body>
  <header>
    <div class="header-left">
      <a href="index.php" class="logo">PetStore</a>
    </div>
    <div class="header-center">
      <ul class="nav-links">
        <li><a href="adopt_user.php">Adopt</a></li>
        <li><a href="#caretips">Care Tips</a></li>
        <li><a href="#about">About</a></li>
      </ul>
    </div>
    <div class="header-right">
      <div class="search">
        <span class="search-icon">&#128269;</span>
        <input type="text" class="search-input" placeholder="Search...">
      </div>
      <?php if($loggedInUser): ?>
        <div class="profile-wrapper" style="display: flex; align-items: center; gap: 1rem; position: relative;">
          <div class="profile-circle" onclick="toggleProfileDropdown()">
            <?php echo strtoupper(substr($loggedInUser, 0, 1)); ?>
          </div>
          <div id="profileDropdown" class="profile-dropdown">
            <p onclick="openMessageModal()">Messages</p>
            <p onclick="logoutUser()">Logout</p>
          </div>
        </div>
      <?php else: ?>
        <a href="login_form.php" class="login-btn">Login</a>
      <?php endif; ?>
      <div class="cart-wrapper" style="position: relative;">
        <span class="cart-btn" onclick="openCart()">&#128722;</span>
        <span class="cart-count" id="cartCountBubble">0</span>
      </div>
    </div>
  </header>

  <section class="hero">
    <div class="hero-content animatable">
      <h1>Welcome to Our Pet Store</h1>
      <p>Your one-stop hub for pet products, adoption resources, and expert care tips.</p>
    </div>
  </section>

  <section class="category-selection animatable">
    <h2>Select a Category</h2>
    <div class="category-grid">
      <div class="category-card" onclick="showCatProducts()">
        <img src="uploads/CAt.jpg" alt="Cat">
        <h3>Cat</h3>
      </div>
      <div class="category-card" onclick="showDogProducts()">
        <img src="uploads/Dog.jpg" alt="Dog">
        <h3>Dog</h3>
      </div>
    </div>
  </section>

  <section class="products-container" id="catProducts">
    <h2 style="text-align: center; color: #8fbc8f;">Cat Products</h2>
    <div class="products-grid">
      <?php foreach ($catProducts as $catProd): ?>
        <div class="product-card">
          <img src="uploads/<?php echo htmlspecialchars($catProd['image_url']); ?>" alt="<?php echo htmlspecialchars($catProd['title']); ?>">
          <h3><?php echo htmlspecialchars($catProd['title']); ?></h3>
          <p class="price">$<?php echo number_format($catProd['price'], 2); ?></p>
          <p><?php echo htmlspecialchars($catProd['description']); ?></p>
          <button class="btn add-to-cart"
                  data-id="<?php echo $catProd['id']; ?>"
                  data-title="<?php echo htmlspecialchars($catProd['title']); ?>"
                  data-price="<?php echo htmlspecialchars($catProd['price']); ?>">
            Add to Cart
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="products-container" id="dogProducts">
    <h2 style="text-align: center; color: #8fbc8f;">Dog Products</h2>
    <div class="products-grid">
      <?php foreach ($dogProducts as $dogProd): ?>
        <div class="product-card">
          <img src="uploads/<?php echo htmlspecialchars($dogProd['image_url']); ?>" alt="<?php echo htmlspecialchars($dogProd['title']); ?>">
          <h3><?php echo htmlspecialchars($dogProd['title']); ?></h3>
          <p class="price">$<?php echo number_format($dogProd['price'], 2); ?></p>
          <p><?php echo htmlspecialchars($dogProd['description']); ?></p>
          <button class="btn add-to-cart"
                  data-id="<?php echo $dogProd['id']; ?>"
                  data-title="<?php echo htmlspecialchars($dogProd['title']); ?>"
                  data-price="<?php echo htmlspecialchars($dogProd['price']); ?>">
            Add to Cart
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="care-tips animatable" id="caretips">
    <h2>Expert Pet Care Tips</h2>
    <div class="carousel-container">
      <div class="carousel-slide active">
        <img src="uploads/Grooming.webp" alt="Grooming Tip">
        <div class="tip-text">
          <h3>Regular Grooming</h3>
          <p>Maintaining a regular grooming schedule keeps your pet looking great and feeling healthy.</p>
        </div>
      </div>
    </div>
    <a href="care_tips.php" class="btn explore-btn">Explore More Care Tips</a>
  </section>

  <section class="about animatable" id="about">
    <h2>About Us</h2>
    <p>Our Pet Store has been serving the community for over a decade, providing quality pet products and adoption resources.</p>
    <p>We believe in the power of pets to bring joy and companionship. Join us in making a difference in the lives of animals.</p>
  </section>

  <footer>
    <div class="footer-content">
      <div>
        <h3>Contact Us</h3>
        <p>123 Pet Lane, Animal City</p>
        <p>Email: info@petstore.com</p>
        <p>Phone: (123) 456-7890</p>
      </div>
      <div>
        <h3>Hours</h3>
        <p>Mon - Fri: 9am - 7pm</p>
        <p>Sat: 10am - 6pm</p>
        <p>Sun: Closed</p>
      </div>
    </div>
  </footer>
</body>
</html>
