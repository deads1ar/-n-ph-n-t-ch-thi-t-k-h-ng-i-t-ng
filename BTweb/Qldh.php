<?php
session_start();
include 'db.php';
include 'headerad.php';

// Cập nhật trạng thái đơn hàng
if (isset($_POST['update_status'])) {
    $iddh = $_POST['iddh'];
    $new_status = $_POST['new_status'];
    $stmt = $pdo->prepare("SELECT TRANGTHAI FROM dh WHERE IDDH = ?");
    $stmt->execute([$iddh]);
    $current_status = $stmt->fetchColumn();
    $status_order = [
        'Chưa xác nhận' => 1,
        'Đã xác nhận' => 2,
        'Đã giao - Thành công' => 3,
        'Đã giao - Hủy đơn' => 3
    ];
    if ($status_order[$new_status] >= $status_order[$current_status]) {
        $stmt = $pdo->prepare("UPDATE dh SET TRANGTHAI = ? WHERE IDDH = ?");
        $stmt->execute([$new_status, $iddh]);
    }
}

// Lọc đơn hàng
$where = "WHERE 1=1";
$params = [];
if (isset($_GET['status']) && $_GET['status'] !== '') {
    $where .= " AND dh.TRANGTHAI = ?";
    $params[] = $_GET['status'];
}
if (isset($_GET['start_date']) && $_GET['start_date'] !== '' && isset($_GET['end_date']) && $_GET['end_date'] !== '') {
    $where .= " AND dh.TIME BETWEEN ? AND ?";
    $params[] = $_GET['start_date'] . ' 00:00:00';
    $params[] = $_GET['end_date'] . ' 23:59:59';
}
if (isset($_GET['location']) && $_GET['location'] !== '') {
    $where .= " AND kh.DC LIKE ?";
    $params[] = "%" . $_GET['location'] . "%";
}

$stmt = $pdo->prepare("SELECT dh.IDDH, kh.NAME, kh.DC, dh.TIME, dh.TRANGTHAI, dh.TONG 
    FROM dh 
    JOIN kh ON dh.IDKH = kh.IDKH 
    $where");
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Quản lý đơn hàng</h1>

    <!-- Form lọc đơn hàng -->
    <form method="get" class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tình trạng:</label>
                <select name="status" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="Chưa xác nhận" <?= ($_GET['status'] ?? '') === 'Chưa xác nhận' ? 'selected' : '' ?>>Chưa xác nhận</option>
                    <option value="Đã xác nhận" <?= ($_GET['status'] ?? '') === 'Đã xác nhận' ? 'selected' : '' ?>>Đã xác nhận</option>
                    <option value="Đã giao - Thành công" <?= ($_GET['status'] ?? '') === 'Đã giao - Thành công' ? 'selected' : '' ?>>Đã giao - Thành công</option>
                    <option value="Đã giao - Hủy đơn" <?= ($_GET['status'] ?? '') === 'Đã giao - Hủy đơn' ? 'selected' : '' ?>>Đã giao - Hủy đơn</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Từ ngày:</label>
                <input type="date" name="start_date" class="form-control" value="<?= $_GET['start_date'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Đến ngày:</label>
                <input type="date" name="end_date" class="form-control" value="<?= $_GET['end_date'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Địa điểm:</label>
                <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($_GET['location'] ?? '') ?>" placeholder="Nhập địa chỉ">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Lọc</button>
    </form>

    <!-- Danh sách đơn hàng -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID Đơn hàng</th>
                    <th>Khách hàng</th>
                    <th>Địa chỉ</th>
                    <th>Thời gian đặt</th>
                    <th>Tổng tiền</th>
                    <th>Chi tiết đơn hàng</th>
                    <th>Cập nhật</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['IDDH'] ?></td>
                        <td><?= htmlspecialchars($order['NAME']) ?></td>
                        <td><?= htmlspecialchars($order['DC']) ?></td>
                        <td><?= $order['TIME'] ?></td>
                        <td><?= number_format($order['TONG'], 0, ',', '.') . ' đ' ?></td>
                        <td>
                            <a href="detailbill.php?iddh=<?= $order['IDDH'] ?>" class="btn btn-sm btn-info">Xem chi tiết</a>
                        </td>
                        <td>
                            <form method="post" class="d-flex flex-column">
                                <input type="hidden" name="iddh" value="<?= $order['IDDH'] ?>">
                                <select name="new_status" class="form-select mb-1">
                                    <?php
                                    $statuses = ['Chưa xác nhận', 'Đã xác nhận', 'Đã giao - Thành công', 'Đã giao - Hủy đơn'];
                                    foreach ($statuses as $status) {
                                        $selected = $order['TRANGTHAI'] === $status ? 'selected' : '';
                                        echo "<option value=\"$status\" $selected>$status</option>";
                                    }
                                    ?>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-sm btn-success">Cập nhật</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <a href="indexadmin.php" class="btn btn-secondary mt-4">Quay lại</a>
</div>
