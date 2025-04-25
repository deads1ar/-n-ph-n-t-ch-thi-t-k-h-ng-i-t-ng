<?php
session_start();
include 'db.php';
include 'headerad.php';

// Kiểm tra xem ID đơn hàng có được truyền qua URL không
if (!isset($_GET['iddh']) || empty($_GET['iddh'])) {
    header("Location: Qldh.php");
    exit;
}

$iddh = $_GET['iddh'];

// Lấy thông tin đơn hàng
$stmt = $pdo->prepare("
    SELECT dh.IDDH, dh.TIME, dh.TRANGTHAI, dh.TONG, kh.NAME, kh.DC, kh.SDT 
    FROM dh 
    JOIN kh ON dh.IDKH = kh.IDKH 
    WHERE dh.IDDH = ?
");
$stmt->execute([$iddh]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: Qldh.php");
    exit;
}

// Lấy chi tiết đơn hàng
$details_stmt = $pdo->prepare("
    SELECT sp.TEN, sp.GIABANKM, ctdh.SL, sp.URL 
    FROM ctdh 
    JOIN sp ON ctdh.IDSP = sp.IDSP 
    WHERE ctdh.IDDH = ?
");
$details_stmt->execute([$iddh]);
$order_details = $details_stmt->fetchAll(PDO::FETCH_ASSOC);

// Cập nhật trạng thái đơn hàng
if (isset($_POST['update_status'])) {
    $new_status = $_POST['new_status'];
    $stmt = $pdo->prepare("SELECT TRANGTHAI FROM dh WHERE IDDH = ?");
    $stmt->execute([$iddh]);
    $current_status = $stmt->fetchColumn();
    $status_order = [
        'Chưa xác nhận' => 1,
        'Đã xác nhận' => 2,
        'Đang giao' => 3,
        'Đã giao - Thành công' => 4,
        'Đã giao - Hủy đơn' => 4
    ];
    if ($status_order[$new_status] >= $status_order[$current_status]) {
        $stmt = $pdo->prepare("UPDATE dh SET TRANGTHAI = ? WHERE IDDH = ?");
        $stmt->execute([$new_status, $iddh]);
        // Cập nhật lại trạng thái đơn hàng
        $order['TRANGTHAI'] = $new_status;
    }
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng #<?= $iddh ?></title>
    <!-- Bootstrap CSS (giả định đã được include trong headerad.php) -->
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Chi tiết đơn hàng #<?= $iddh ?></h1>

        <!-- Thông tin đơn hàng -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Thông tin đơn hàng</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID Đơn hàng:</strong> <?= $order['IDDH'] ?></p>
                        <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['NAME']) ?></p>
                        <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['SDT']) ?></p>
                        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['DC']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Thời gian đặt:</strong> <?= $order['TIME'] ?></p>
                        <p><strong>Tổng tiền:</strong> <?= number_format($order['TONG'], 0, ',', '.') ?> đ</p>
                        <p><strong>Trạng thái:</strong> <?= $order['TRANGTHAI'] ?></p>
                        <!-- Form cập nhật trạng thái -->
                        <form method="post" class="d-flex flex-column">
                            <label class="form-label">Cập nhật trạng thái:</label>
                            <select name="new_status" class="form-select mb-2">
                                <?php
                                $statuses = ['Chưa xác nhận', 'Đã xác nhận', 'Đang giao', 'Đã giao - Thành công', 'Đã giao - Hủy đơn'];
                                foreach ($statuses as $status) {
                                    $selected = $order['TRANGTHAI'] === $status ? 'selected' : '';
                                    echo "<option value=\"$status\" $selected>$status</option>";
                                }
                                ?>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-success">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm trong đơn hàng -->
<h3 class="mb-3">Sản phẩm trong đơn hàng</h3>
<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá khuyến mãi</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody id="order-items-list">
            <?php foreach ($order_details as $item) { ?>
                <tr class="product-item">
                    <td><img src="<?= htmlspecialchars($item['URL']) ?>" alt="IMG" style="max-width: 100px;"></td>
                    <td><?= htmlspecialchars($item['TEN']) ?></td>
                    <td><?= $item['SL'] ?></td>
                    <td>
                        <?= number_format($item['GIABANKM'], 0, ',', '.') ?>đ 
                        <!-- Note: GIABAN is not in $order_details, remove or fetch it if needed -->
                    </td>
                    <td><?= number_format(($item['GIABANKM'] * $item['SL']), 0, ',', '.') ?>đ</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

        <!-- Nút quay lại -->
        <a href="Qldh.php" class="btn btn-secondary mt-4">Quay lại quản lý đơn hàng</a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>