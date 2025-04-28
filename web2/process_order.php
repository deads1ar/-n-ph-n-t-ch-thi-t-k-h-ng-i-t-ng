<?php
session_start();
$conn = mysqli_connect("localhost","root","","web_db");
$userCartCookie = "cart_" . $_SESSION["IDKH"];
$cartItems = json_decode($_COOKIE[$userCartCookie] ?? "{}", true);

// Read the raw POST body
$rawData = file_get_contents("php://input");

// Decode the JSON into a PHP array
$data = json_decode($rawData, true); // `true` makes it an associative array

// Now you can access the 'address'
$diachi = $data['address'];
//echo $diachi;

if (empty($cartItems)) {
    echo json_encode(["status" => "error", "message" => "Giỏ hàng trống, không thể tạo đơn hàng."]);
    exit;
}

// Calculate total amount
$tongTien = 0;
foreach ($cartItems as $productId => $quantity) {
    $query = "SELECT GIABANKM FROM sp WHERE IDSP = ?";
    $stmtSP = $conn->prepare($query);
    $stmtSP->bind_param("i", $productId);
    $stmtSP->execute();
    $resultSP = $stmtSP->get_result();
    $rowSP = $resultSP->fetch_assoc();
    $tongTien += $rowSP['GIABANKM'] * $quantity;
}

// Insert into `dh`
$sqlInsertDH = "INSERT INTO dh (IDKH, TONG, DC) VALUES (?, ?, ?)";
$stmtDH = $conn->prepare($sqlInsertDH);
$stmtDH->bind_param("sis", $_SESSION["IDKH"], $tongTien, $diachi);
$stmtDH->execute();

// Get IDDH for `ctdh` insert
$orderId = $conn->insert_id;

// Insert products into `ctdh`
$sqlInsertCTDH = "INSERT INTO ctdh (IDDH, IDSP, SL) VALUES (?, ?, ?)";
$stmtCTDH = $conn->prepare($sqlInsertCTDH);
foreach ($cartItems as $productId => $quantity) {
    $stmtCTDH->bind_param("iii", $orderId, $productId, $quantity);
    $stmtCTDH->execute();
}

echo json_encode(["status" => "success", "message" => "Đã đặt hàng thành công!"]);
// Clear the cart cookie after successful purchase
setcookie($userCartCookie, '', time() - 3600, '/');
unset($_COOKIE[$userCartCookie]);
?>