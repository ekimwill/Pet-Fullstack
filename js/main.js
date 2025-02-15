// Force scroll to top on page refresh
window.addEventListener('beforeunload', function() {
    window.scrollTo(0, 0);
  });
  
  // CART FUNCTIONALITY
  let cart = [];
  
  function addToCart(productId, productTitle, productPrice) {
    if (!loggedIn) {
      alert("Please log in to add items to your cart.");
      return;
    }
    const found = cart.find(item => item.id === productId);
    if (found) {
      found.quantity++;
    } else {
      cart.push({ id: productId, title: productTitle, price: parseFloat(productPrice), quantity: 1 });
    }
    updateCartCount();
  }
  
  function updateCartCount() {
    const count = cart.reduce((acc, item) => acc + item.quantity, 0);
    const bubble = document.getElementById('cartCountBubble');
    if (count > 0) {
      bubble.style.display = 'inline-block';
      bubble.textContent = count;
    } else {
      bubble.style.display = 'none';
    }
  }
  
  function openCart() {
    if (!loggedIn) {
      alert("Please log in to view your cart.");
      return;
    }
    generateCartModalContent();
    document.getElementById('cartModal').style.display = 'flex';
  }
  
  function closeCart() {
    document.getElementById('cartModal').style.display = 'none';
  }
  
  function generateCartModalContent() {
    const cartItemsDiv = document.getElementById('cartItems');
    const cartSummaryDiv = document.getElementById('cartSummary');
    cartItemsDiv.innerHTML = '';
    let subtotal = 0;
    if (cart.length === 0) {
      cartItemsDiv.innerHTML = '<p>Your cart is empty.</p>';
      cartSummaryDiv.innerHTML = '';
      return;
    }
    let table = document.createElement('table');
    let thead = document.createElement('thead');
    thead.innerHTML = '<tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr>';
    table.appendChild(thead);
    let tbody = document.createElement('tbody');
    cart.forEach(item => {
      let row = document.createElement('tr');
      let itemTotal = item.price * item.quantity;
      subtotal += itemTotal;
      row.innerHTML = `
        <td>${item.title}</td>
        <td>${item.quantity}</td>
        <td>$${item.price.toFixed(2)}</td>
        <td>$${itemTotal.toFixed(2)}</td>
      `;
      tbody.appendChild(row);
    });
    table.appendChild(tbody);
    table.style.width = '100%';
    table.style.borderCollapse = 'collapse';
    cartItemsDiv.appendChild(table);
  
    const tax = subtotal * 0.1;
    const total = subtotal + tax;
    cartSummaryDiv.innerHTML = `
      <p>Subtotal: $${subtotal.toFixed(2)}</p>
      <p>Tax (10%): $${tax.toFixed(2)}</p>
      <p><strong>Total: $${total.toFixed(2)}</strong></p>
    `;
  }
  
  function proceedToDelivery() {
    if (!loggedIn) {
      alert("Please log in to proceed to delivery.");
      return;
    }
    const deliveryDate = document.getElementById('deliveryDate').value;
    if (!deliveryDate) {
      alert('Please select a delivery date.');
      return;
    }
    alert('Proceeding to delivery on ' + deliveryDate + '.');
    // ... additional checkout logic ...
  }
  
  document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
      if (!loggedIn) {
        alert("Please log in to add items to your cart.");
        return;
      }
      const productId = this.getAttribute('data-id');
      const productTitle = this.getAttribute('data-title');
      const productPrice = this.getAttribute('data-price');
      addToCart(productId, productTitle, productPrice);
    });
  });
  
  // SCROLL ANIMATION (Intersection Observer)
  document.addEventListener("DOMContentLoaded", function() {
    const animatables = document.querySelectorAll('.animatable');
    const observerOptions = { threshold: 0.2 };
    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate');
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);
    animatables.forEach(el => observer.observe(el));
  });
  
  // SEARCH TOGGLE
  const searchIcon = document.querySelector('.search-icon');
  const searchInput = document.querySelector('.search-input');
  searchIcon.addEventListener('click', () => {
    searchInput.style.display = (searchInput.style.display === 'block') ? 'none' : 'block';
    if (searchInput.style.display === 'block') searchInput.focus();
  });
  
  // LOGOUT FUNCTION
  function logoutUser() {
    document.cookie = "loggedInUser=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
    window.location.reload();
  }
  
  // CLICK-OUTSIDE MODALS & PROFILE DROPDOWN
  window.onclick = function(event) {
    const cartModal = document.getElementById('cartModal');
    const messageModal = document.getElementById('messageModal');
    const profileDropdown = document.getElementById('profileDropdown');
    const profileCircle = document.querySelector('.profile-circle');
    
    if (event.target === cartModal) {
      closeCart();
    }
    if (event.target === messageModal) {
      closeMessageModal();
    }
    if (
      profileDropdown &&
      event.target !== profileDropdown &&
      event.target !== profileCircle &&
      !profileDropdown.contains(event.target)
    ) {
      profileDropdown.style.display = 'none';
    }
  };
  
  // CARE TIPS CAROUSEL (Auto-Rotate)
  let currentSlide = 0;
  function showSlide(index) {
    const slides = document.querySelectorAll('.carousel-slide');
    if (slides.length === 0) return;
    if (index >= slides.length) currentSlide = 0;
    else if (index < 0) currentSlide = slides.length - 1;
    else currentSlide = index;
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === currentSlide);
    });
  }
  setInterval(() => { showSlide(currentSlide + 1); }, 5000);
  
  // CAT/DOG PRODUCTS TOGGLE
  const catSection = document.getElementById('catProducts');
  const dogSection = document.getElementById('dogProducts');
  function showCatProducts() {
    catSection.style.display = 'block';
    dogSection.style.display = 'none';
    window.scrollTo({ top: catSection.offsetTop - 60, behavior: 'smooth' });
  }
  function showDogProducts() {
    dogSection.style.display = 'block';
    catSection.style.display = 'none';
    window.scrollTo({ top: dogSection.offsetTop - 60, behavior: 'smooth' });
  }
  
  // AUTO-SET DELIVERY DATE TO TOMORROW
  document.addEventListener("DOMContentLoaded", function() {
    const deliveryInput = document.getElementById("deliveryDate");
    if (deliveryInput) {
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      deliveryInput.valueAsDate = tomorrow;
    }
  });
  
  // PROFILE DROPDOWN TOGGLE
  function toggleProfileDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    if (!dropdown) return;
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
  }
  
  // MESSAGE MODAL FUNCTIONS
  function openMessageModal() {
    document.getElementById('messageModal').style.display = 'flex';
  }
  function closeMessageModal() {
    document.getElementById('messageModal').style.display = 'none';
  }
  