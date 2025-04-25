<?php
header("Content-Type: application/json");
include "db.php"; // This defines $pdo using PDO

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    // Lấy danh sách users
    case 'list':
        $sql = "SELECT * FROM kh";
        $stmt = $pdo->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;

    // Thêm user
    case 'add':
        $data = json_decode(file_get_contents("php://input"), true);
        // Generate new IDKH (e.g., KH006 if KH005 is the last)
        $stmt = $pdo->query("SELECT MAX(IDKH) as max_id FROM kh");
        $max_id = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'];
        $new_id_num = (int)substr($max_id, 2) + 1;
        $id = "KH" . sprintf("%03d", $new_id_num);

        $name = $data['name'];
        $phone = $data['phone'];
        $password = $data['password'];
        $diachi = $data['diachi']; // Changed from gmail to diachi
        $status = "Available"; // Default status for new users

        $sql = "INSERT INTO kh (IDKH, NAME, SDT, MK, DC, STATUS) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $name, $phone, $password, $diachi, $status]);
        echo json_encode(["message" => "Thêm tài khoản thành công", "id" => $id]);
        break;

    // Sửa user
    case 'edit':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];
        $name = $data['name'];
        $phone = $data['phone'];
        $password = $data['password'];
        $diachi = $data['diachi']; // Changed from gmail to diachi

        $sql = "UPDATE kh SET NAME = ?, SDT = ?, MK = ?, DC = ? WHERE IDKH = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $phone, $password, $diachi, $id]);
        echo json_encode(["message" => "Sửa tài khoản thành công"]);
        break;

    // Khóa/Mở user
    case 'toggle':
        $id = $_GET['id'];
        $sql = "UPDATE kh SET STATUS = IF(STATUS = 'Available', 'Locked', 'Available') WHERE IDKH = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo json_encode(["message" => "Cập nhật trạng thái thành công"]);
        break;

    default:
        echo json_encode(["error" => "Hành động không hợp lệ"]);
}
?>