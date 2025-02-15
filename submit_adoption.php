<?php
session_start();
require_once __DIR__ . '/db.php';

// Ensure user is logged in
if (!isset($_COOKIE['loggedInUser'])) {
    echo "You must be logged in to submit an adoption request.";
    exit;
}

// Grab username from cookie, find user in DB
$requesterUsername = $_COOKIE['loggedInUser'];
$userStmt = $pdo->prepare("SELECT id, username FROM users WHERE username = ?");
$userStmt->execute([$requesterUsername]);
$userRow = $userStmt->fetch();

if (!$userRow) {
    echo "Could not find your user record. Please re-login.";
    exit;
}
$requesterId = $userRow['id'];

// Gather form data from the POST
$petId          = $_POST['pet_id'];
$adopterAge     = $_POST['adopterAge'];
$adoptionReason = $_POST['adoptionReason'];

// Optionally fetch the pet name from your `pets` table
$petStmt = $pdo->prepare("SELECT name FROM pets WHERE id = ?");
$petStmt->execute([$petId]);
$petRow = $petStmt->fetch();
$petName = $petRow ? $petRow['name'] : 'Unknown';

// Insert into adoption_requests table
// (Make sure your table has these columns: pet_id, pet_name, reason, requester_id, requester_name, status, created_at, etc.)
$insert = $pdo->prepare("
  INSERT INTO adoption_requests 
  (pet_id, pet_name, reason, requester_id, requester_name, status, created_at)
  VALUES (?, ?, ?, ?, ?, 'Pending', NOW())
");
$insert->execute([$petId, $petName, $adoptionReason, $requesterId, $requesterUsername]);

echo "Your adoption request has been submitted!";
?>
