<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login_form.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['product_image'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check === false) {
            $error = "File is not an image.";
            $uploadOk = 0;
        }
    }
    
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO products (title, price, description, category, image_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['title'],
                $_POST['price'],
                $_POST['description'],
                $_POST['category'],
                $target_file
            ]);
            $success = "Product has been added successfully.";
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pet_image']) && isset($_POST['add_pet'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["pet_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    if (isset($_POST["add_pet"])) {
        $check = getimagesize($_FILES["pet_image"]["tmp_name"]);
        if ($check === false) {
            $pet_error = "File is not an image.";
            $uploadOk = 0;
        }
    }
    
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["pet_image"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO pets (name, species, age, description, image_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['pet_name'],
                $_POST['species'],
                $_POST['pet_age'],
                $_POST['pet_description'],
                $target_file
            ]);
            $pet_success = "Adoptable pet has been added successfully.";
        } else {
            $pet_error = "Sorry, there was an error uploading your file.";
        }
    }
}

if (isset($_POST['delete_product'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_POST['product_id']]);
}

if (isset($_POST['delete_pet'])) {
    $stmt = $pdo->prepare("DELETE FROM pets WHERE id = ?");
    $stmt->execute([$_POST['pet_id']]);
}

if (isset($_POST['approve_request']) || isset($_POST['reject_request'])) {
    $reqStmt = $pdo->prepare("SELECT * FROM adoption_requests WHERE id = ?");
    $reqStmt->execute([$_POST['request_id']]);
    $reqRow = $reqStmt->fetch();
    if ($reqRow) {
        $petId     = $reqRow['pet_id'];
        $petName   = $reqRow['pet_name'];
        $requester = $reqRow['requester_name'];
        
        $petStmt = $pdo->prepare("SELECT age FROM pets WHERE id = ?");
        $petStmt->execute([$petId]);
        $petData  = $petStmt->fetch();
        $petAge   = $petData ? $petData['age'] : 'Unknown';

        if (isset($_POST['approve_request'])) {
            $newStatus = 'Approved';
            $adminMsg = "Congratulations! Your adoption request for $petName (age $petAge) has been approved. Please bring your ID to our center.";
        } else {
            $newStatus = 'Rejected';
            $adminMsg = "Sorry, your adoption request for $petName (age $petAge) has been rejected. Please contact us for more details.";
        }

        $updateStmt = $pdo->prepare("
            UPDATE adoption_requests
               SET status = :status,
                   admin_message = :msg
             WHERE id = :reqid
        ");
        $updateStmt->execute([
            ':status' => $newStatus,
            ':msg'    => $adminMsg,
            ':reqid'  => $_POST['request_id']
        ]);
    }
}

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM pets ORDER BY id DESC");
$adoptable_pets = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM adoption_requests WHERE status = 'Pending' ORDER BY created_at DESC");
$adoption_requests = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - PetStore</title>
  <link rel="stylesheet" href="css/admin.css">
  <script src="js/admin.js" defer></script>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Admin Dashboard</h1>
      <a href="logout.php" class="btn logout">Logout</a>
    </div>

    <section class="section">
      <h2>Add New Product</h2>
      <form method="post" enctype="multipart/form-data">
        <label>Product Title:</label>
        <input type="text" name="title" required>
        <label>Price:</label>
        <input type="number" name="price" step="0.01" required>
        <label>Description:</label>
        <textarea name="description" required></textarea>
        <label>Category:</label>
        <input type="radio" name="category" value="cat" required> Cat
        <input type="radio" name="category" value="dog" required> Dog
        <label>Product Image:</label>
        <input type="file" name="product_image" required>
        <button type="submit">Add Product</button>
      </form>
    </section>

    <section class="section">
      <h2>Manage Products</h2>
      <?php foreach ($products as $product): ?>
        <div>
          <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
          <h3><?php echo htmlspecialchars($product['title']); ?></h3>
          <p>$<?php echo htmlspecialchars($product['price']); ?></p>
          <form method="post">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <button type="submit" name="delete_product">Delete</button>
          </form>
        </div>
      <?php endforeach; ?>
    </section>

    <section class="section">
      <h2>Adoption Requests (Pending)</h2>
      <table>
        <thead>
          <tr>
            <th>Pet Name</th>
            <th>Requester</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($adoption_requests as $request): ?>
            <tr>
              <td><?php echo htmlspecialchars($request['pet_name']); ?></td>
              <td><?php echo htmlspecialchars($request['requester_name']); ?></td>
              <td><?php echo htmlspecialchars($request['status']); ?></td>
              <td>
                <form method="post">
                  <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                  <button type="submit" name="approve_request">Approve</button>
                  <button type="submit" name="reject_request">Reject</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </div>
</body>
</html>
