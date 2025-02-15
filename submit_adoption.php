<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_COOKIE['loggedInUser'])) {
    echo "You must be logged in to submit an adoption request.";
    exit;
}

$requesterUsername = $_COOKIE['loggedInUser'];
$userStmt = $pdo->prepare("SELECT id, username FROM users WHERE username = ?");
$userStmt->execute([$requesterUsername]);
$userRow = $userStmt->fetch();

if (!$userRow) {
    echo "Could not find your user record. Please re-login.";
    exit;
}
$requesterId = $userRow['id'];

$petId = $_POST['pet_id'];
$adopterAge = $_POST['adopterAge'];
$adoptionReason = $_POST['adoptionReason'];

$petStmt = $pdo->prepare("SELECT name FROM pets WHERE id = ?");
$petStmt->execute([$petId]);
$petRow = $petStmt->fetch();
$petName = $petRow ? $petRow['name'] : 'Unknown';

$insert = $pdo->prepare("
  INSERT INTO adoption_requests 
  (pet_id, pet_name, reason, requester_id, requester_name, status, created_at)
  VALUES (?, ?, ?, ?, ?, 'Pending', NOW())
");
$insert->execute([$petId, $petName, $adoptionReason, $requesterId, $requesterUsername]);

echo "Your adoption request has been submitted!";
?>
