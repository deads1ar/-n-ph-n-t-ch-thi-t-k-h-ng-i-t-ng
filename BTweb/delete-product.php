<?php
include 'db.php';

$id = $_GET['id'];

// Check if the product has been sold
$stmt = $pdo->prepare("SELECT COUNT(*) FROM ctdh WHERE IDSP = ?");
$stmt->execute([$id]);
$sold = $stmt->fetchColumn();

if ($sold > 0) {
    // Hide the product (e.g., add a status column to `sp` table and set it to 'hidden')
    $stmt = $pdo->prepare("UPDATE sp SET STATUS = 'hidden' WHERE IDSP = ?"); // Assuming a STATUS column is added
    $stmt->execute([$id]);
} else {
    // Delete the product
    $stmt = $pdo->prepare("DELETE FROM sp WHERE IDSP = ?");
    $stmt->execute([$id]);
}

header("Location: Qlsp.php");
exit;
?>