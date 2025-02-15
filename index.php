<?php
session_start();
require_once 'db.php';

// Check if user is "logged in" via cookie
$loggedInUser = isset($_COOKIE['loggedInUser']) ? $_COOKIE['loggedInUser'] : null;

// Fetch all products (if you want DB products too)
$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Separate products into cat and dog arrays (for DB-based items)
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
  
  <!-- Link external CSS file -->
  <link rel="stylesheet" href="css/style.css">
  
  <!-- Pass the PHP variable 'loggedIn' to JS -->
  <script>
    var loggedIn = <?php echo $loggedInUser ? 'true' : 'false'; ?>;
  </script>
  
  <!-- Link external JS file -->
  <script src="js/main.js" defer></script>
</head>
<body>
  <!-- ========== HEADER ========== -->
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
      <!-- SEARCH ICON & INPUT -->
      <div class="search">
        <span class="search-icon">&#128269;</span>
        <input type="text" class="search-input" placeholder="Search...">
      </div>

      <!-- LOGIN OR PROFILE -->
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

      <!-- CART ICON -->
      <div class="cart-wrapper" style="position: relative;">
        <span class="cart-btn" onclick="openCart()">&#128722;</span>
        <span class="cart-count" id="cartCountBubble">0</span>
      </div>
    </div>
  </header>

  <!-- ========== HERO SECTION ========== -->
  <section class="hero">
    <div class="hero-content animatable">
      <h1>Welcome to Our Pet Store</h1>
      <p>Your one-stop hub for pet products, adoption resources, and expert care tips.</p>
    </div>
  </section>

  <!-- ========== CATEGORY SELECTION ========== -->
  <section class="category-selection animatable">
    <h2>Select a Category</h2>
    <div class="category-grid">
      <!-- Images referenced from /uploads/ folder -->
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

  <!-- ========== CAT PRODUCTS ========== -->
  <section class="products-container" id="catProducts">
    <h2 style="text-align: center; color: #8fbc8f;">Cat Products</h2>
    <div class="products-grid">
      <!-- ========== DB CATS (if any) ========== -->
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

      <!-- ========== ORIGINAL STATIC CAT PRODUCTS ========== -->
      <div class="product-card">
        <img src="uploads/Kitty tuna.webp" alt="Cat Food 1">
        <h3>Kitty Tuna & Salmon</h3>
        <p class="price">$5.50</p>
        <p>Delicious wet food to keep your cat happy and healthy.</p>
        <button class="btn add-to-cart" data-id="cat1" data-title="Kitty Tuna & Salmon" data-price="5.50">Add to Cart</button>
      </div>
      <div class="product-card">
        <img src="uploads/catToy.webp" alt="Cat Toy 1">
        <h3>Interactive Feather Toy</h3>
        <p class="price">$7.99</p>
        <p>Engaging toy designed to keep your cat active and entertained.</p>
        <button class="btn add-to-cart" data-id="cat2" data-title="Interactive Feather Toy" data-price="7.99">Add to Cart</button>
      </div>
      <div class="product-card">
        <img src="uploads/bed.webp" alt="Cat Bed 1">
        <h3>Plush Cat Bed</h3>
        <p class="price">$15.99</p>
        <p>Soft, plush bed offering warmth and comfort for naptime.</p>
        <button class="btn add-to-cart" data-id="cat3" data-title="Plush Cat Bed" data-price="15.99">Add to Cart</button>
      </div>
      <div class="product-card">
        <img src="uploads/collar.jpg" alt="Cat Collar 1">
        <h3>Stylish Cat Collar</h3>
        <p class="price">$8.50</p>
        <p>Comfortable collar with a safety release buckle.</p>
        <button class="btn add-to-cart" data-id="cat4" data-title="Stylish Cat Collar" data-price="8.50">Add to Cart</button>
      </div>
      <div class="product-card">
        <img src="uploads/Scratch.jpg" alt="Cat Scratcher Lounge">
        <h3>Cat Scratcher Lounge</h3>
        <p class="price">$22.00</p>
        <p>Sturdy corrugated surface to satisfy your cat’s scratching instincts.</p>
        <button class="btn add-to-cart" data-id="cat5" data-title="Cat Scratcher Lounge" data-price="22.00">Add to Cart</button>
      </div>
      <div class="product-card">
        <img src="uploads/DentalChew.webp" alt="Cat Dental Chew">
        <h3>Dental Chew Treats</h3>
        <p class="price">$4.99</p>
        <p>Helps maintain oral health and fresh breath for your cat.</p>
        <button class="btn add-to-cart" data-id="cat6" data-title="Dental Chew Treats" data-price="4.99">Add to Cart</button>
      </div>
    </div>
  </section>

  <!-- ========== DOG PRODUCTS ========== -->
  <section class="products-container" id="dogProducts">
    <h2 style="text-align: center; color: #8fbc8f;">Dog Products</h2>
    <div class="products-grid">
      <!-- ========== DB DOGS (if any) ========== -->
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

      <!-- ========== ORIGINAL STATIC DOG PRODUCTS ========== -->
      <div class="product-card">
        <img src="uploads/Premium dog Food.webp" alt="Dog Food 1">
        <h3>Premium Dog Food</h3>
        <p class="price">$24.99</p>
        <p>Nutrient-rich kibble for optimal canine health and energy.</p>
        <button class="btn add-to-cart" data-id="dog1" data-title="Premium Dog Food" data-price="24.99">Add to Cart</button>
      </div>
      <div class="product-card">
        <img src="uploads/chewtoy.jpg" alt="Dog Toy 1">
        <h3>Durable Chew Toy</h3>
        <p class="price">$12.49</p>
        <p>Long-lasting chew designed to help clean teeth and prevent boredom.</p>
        <button class="btn add-to-cart" data-id="dog2" data-title="Durable Chew Toy" data-price="12.49">Add to Cart</button>
      </div>
      <div class="product-card">
        <img src="uploads/Gold_Choke_Parent.jpg" alt="Dog Collar 1">
        <h3>Stylish Dog Collar</h3>
        <p class="price">$9.99</p>
        <p>Comfortable and durable collar with adjustable fit.</p>
        <button class="btn add-to-cart" data-id="dog3" data-title="Stylish Dog Collar" data-price="9.99">Add to Cart</button>
      </div>
      <div class="product-card">
        <img src="uploads/Cozy dog bed.jpg" alt="Dog Bed 1">
        <h3>Cozy Dog Bed</h3>
        <p class="price">$19.99</p>
        <p>Soft, plush bed offering warmth and comfort for naptime.</p>
        <button class="btn add-to-cart" data-id="dog4" data-title="Cozy Dog Bed" data-price="19.99">Add to Cart</button>
      </div>
      <div class="product-card">
        <img src="uploads/Shampoo dog.jpg" alt="Dog Shampoo">
        <h3>Gentle Dog Shampoo</h3>
        <p class="price">$6.99</p>
        <p>Keep your dog’s coat clean and smelling fresh.</p>
        <button class="btn add-to-cart" data-id="dog5" data-title="Gentle Dog Shampoo" data-price="6.99">Add to Cart</button>
      </div>
      <div class="product-card">
        <img src="uploads/Harness.jpg" alt="Dog Harness">
        <h3>Comfort Fit Harness</h3>
        <p class="price">$14.99</p>
        <p>Easy on/off design with adjustable straps for all breeds.</p>
        <button class="btn add-to-cart" data-id="dog6" data-title="Comfort Fit Harness" data-price="14.99">Add to Cart</button>
      </div>
    </div>
  </section>

  <!-- ========== CARE TIPS SECTION ========== -->
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
      <div class="carousel-slide">
        <img src="uploads/balanced.jpg" alt="Nutrition Tip">
        <div class="tip-text">
          <h3>Balanced Nutrition</h3>
          <p>Providing a well-rounded diet is crucial for your pet’s overall wellbeing.</p>
        </div>
      </div>
      <!-- ... additional slides as needed ... -->
    </div>
    <a href="care_tips.php" class="btn explore-btn">Explore More Care Tips</a>
  </section>

  <!-- ========== EVENTS SECTION ========== -->
  <section class="events animatable" id="events">
    <h2>Upcoming Events</h2>
    <div class="events-grid">
      <div class="event-card animatable">
        <h3>Adoption Drive & Pet Meet</h3>
        <p class="event-date">March 12, 2025</p>
        <p class="event-time">10am - 2pm</p>
        <p>Meet adorable pets looking for their forever homes.</p>
      </div>
      <div class="event-card animatable">
        <h3>Pet Care Workshop</h3>
        <p class="event-date">March 20, 2025</p>
        <p class="event-time">3pm - 5pm</p>
        <p>Learn best practices for grooming, training, and nutrition.</p>
      </div>
    </div>
    <div style="text-align: center; margin-top: 2rem;">
      <a href="#" class="btn">View Full Calendar</a>
    </div>
  </section>

  <!-- ========== ABOUT SECTION ========== -->
  <section class="about animatable" id="about">
    <h2>About Us</h2>
    <p>Our Pet Store has been serving the community for over a decade, providing quality pet products and adoption resources.</p>
    <p>We believe in the power of pets to bring joy and companionship. Join us in making a difference in the lives of animals.</p>
  </section>

  <!-- ========== FOOTER ========== -->
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
      <div>
        <h3>Follow Us</h3>
        <p>Facebook</p>
        <p>Instagram</p>
        <p>Twitter</p>
      </div>
    </div>
  </footer>

  <!-- ========== CART MODAL ========== -->
  <div id="cartModal" class="modal">
    <div class="modal-content">
      <button class="close-modal-btn" onclick="closeCart()">X</button>
      <h2>Your Cart</h2>
      <div id="cartItems"></div>
      <div id="cartSummary"></div>
      <label for="deliveryDate">Delivery Date:</label>
      <input type="date" id="deliveryDate" name="deliveryDate" />
      <button class="btn small-btn" onclick="proceedToDelivery()">Proceed to Delivery</button>
    </div>
  </div>

  <!-- ========== MESSAGE MODAL (TABLE + SCROLL) ========== -->
  <div id="messageModal" class="modal">
    <div class="modal-content">
      <button class="close-modal-btn" onclick="closeMessageModal()">X</button>
      <h2>Your Adoption Requests</h2>
      <div class="messages-table-container">
        <?php
        if ($loggedInUser) {
            $stmt = $pdo->prepare("
                SELECT a.pet_name, a.status, a.requester_name, p.age, a.admin_message
                FROM adoption_requests a
                LEFT JOIN pets p ON a.pet_id = p.id
                WHERE a.requester_name = ?
                ORDER BY a.created_at DESC
            ");
            $stmt->execute([$loggedInUser]);
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$requests) {
                echo "<p style='padding:1rem;'>You have not submitted any adoption requests yet.</p>";
            } else {
                echo "<table style='width:100%; border-collapse:collapse;'>";
                echo "<thead>
                        <tr>
                          <th style='background:#f9f9f9; padding:0.5rem;'>Pet Name</th>
                          <th style='background:#f9f9f9; padding:0.5rem;'>Age</th>
                          <th style='background:#f9f9f9; padding:0.5rem;'>Status</th>
                          <th style='background:#f9f9f9; padding:0.5rem;'>Message</th>
                        </tr>
                      </thead>";
                echo "<tbody>";
                foreach ($requests as $req) {
                    $petName = htmlspecialchars($req['pet_name']);
                    $status  = $req['status'];
                    $age     = isset($req['age']) ? htmlspecialchars($req['age']) : "Unknown";
                    $requester = htmlspecialchars($req['requester_name']);
                    $adminMsg = trim($req['admin_message']);

                    echo "<tr>";
                    echo "<td style='padding:0.5rem; border-bottom:1px solid #ddd;'>$petName</td>";
                    echo "<td style='padding:0.5rem; border-bottom:1px solid #ddd;'>$age</td>";
                    echo "<td style='padding:0.5rem; border-bottom:1px solid #ddd;'>$status</td>";
                    
                    echo "<td style='padding:0.5rem; border-bottom:1px solid #ddd;'>";
                    if ($status === 'Approved') {
                        if ($adminMsg === '') {
                            echo "🎉 Congratulations, $requester! Your adoption request for <strong>$petName</strong> (age $age) has been approved. Please bring your ID to our center to complete the process.";
                        } else {
                            echo htmlspecialchars($adminMsg);
                        }
                    } elseif ($status === 'Rejected') {
                        if ($adminMsg === '') {
                            echo "😞 Sorry, $requester. Your request for <strong>$petName</strong> (age $age) was rejected. Please contact our support team for more info.";
                        } else {
                            echo htmlspecialchars($adminMsg);
                        }
                    } else {
                        echo "Your request for <strong>$petName</strong> is still pending.";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            }
        } else {
            echo "<p>Please <a href='login_form.php'>log in</a> to see your adoption requests.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</body>
</html>
