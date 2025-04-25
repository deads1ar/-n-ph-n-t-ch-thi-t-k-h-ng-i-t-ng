<?php
session_start();
include 'db.php';
include 'headerad.php';

// Lấy top 5 khách hàng mua nhiều nhất (tất cả thời gian)
$stats_stmt = $pdo->prepare("
    SELECT kh.IDKH, kh.NAME, SUM(ctdh.SL * sp.GIABANKM) as total 
    FROM dh 
    JOIN ctdh ON dh.IDDH = ctdh.IDDH 
    JOIN kh ON dh.IDKH = kh.IDKH 
    JOIN sp ON ctdh.IDSP = sp.IDSP 
    GROUP BY kh.IDKH, kh.NAME 
    ORDER BY total DESC 
    LIMIT 5
");
$stats_stmt->execute();
$top_stats = $stats_stmt->fetchAll(PDO::FETCH_ASSOC);

// Thống kê theo khoảng thời gian (nếu có)
$filtered_stats = [];
if (isset($_POST['stats_start']) && isset($_POST['stats_end']) && $_POST['stats_start'] !== '' && $_POST['stats_end'] !== '') {
    $stats_stmt = $pdo->prepare("
        SELECT kh.IDKH, kh.NAME, SUM(ctdh.SL * sp.GIABANKM) as total 
        FROM dh 
        JOIN ctdh ON dh.IDDH = ctdh.IDDH 
        JOIN kh ON dh.IDKH = kh.IDKH 
        JOIN sp ON ctdh.IDSP = sp.IDSP 
        WHERE dh.TIME BETWEEN ? AND ? 
        GROUP BY kh.IDKH, kh.NAME 
        ORDER BY total DESC
    ");
    $stats_stmt->execute([$_POST['stats_start'] . ' 00:00:00', $_POST['stats_end'] . ' 23:59:59']);
    $filtered_stats = $stats_stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị</title>
    <!-- Bootstrap CSS (assumed to be included in headerad.php) -->
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Thống Kê Khách Hàng</h1>

        <!-- Form lọc thống kê -->
        <h2 class="mt-5">Thống Kê Theo Thời Gian</h2>
        <form method="post" class="row g-3 mb-4">
            <div class="col-md-5">
                <label class="form-label">Từ ngày:</label>
                <input type="date" name="stats_start" class="form-control" required>
            </div>
            <div class="col-md-5">
                <label class="form-label">Đến ngày:</label>
                <input type="date" name="stats_end" class="form-control" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Thống kê</button>
            </div>
        </form>

        <!-- Bảng thống kê theo thời gian (nếu có) -->
        <?php if ($filtered_stats): ?>
            <h3 class="mb-3">Kết quả thống kê từ <?= htmlspecialchars($_POST['stats_start']) ?> đến <?= htmlspecialchars($_POST['stats_end']) ?></h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Khách hàng</th>
                            <th>Đơn hàng</th>
                            <th>Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($filtered_stats as $stat): ?>
                            <tr>
                                <td><?= htmlspecialchars($stat['NAME']) ?></td>
                                <td>
                                    <?php
                                    $order_stmt = $pdo->prepare("
                                        SELECT dh.IDDH, SUM(ctdh.SL * sp.GIABANKM) as total_order 
                                        FROM dh 
                                        JOIN ctdh ON dh.IDDH = ctdh.IDDH 
                                        JOIN sp ON ctdh.IDSP = sp.IDSP 
                                        WHERE dh.IDKH = ? AND dh.TIME BETWEEN ? AND ?
                                        GROUP BY dh.IDDH
                                    ");
                                    $order_stmt->execute([$stat['IDKH'], $_POST['stats_start'] . ' 00:00:00', $_POST['stats_end'] . ' 23:59:59']);
                                    $orders_by_kh = $order_stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($orders_by_kh as $o) {
                                        echo "<a href='detailbill.php?iddh={$o['IDDH']}'>Đơn {$o['IDDH']}</a> (" . number_format($o['total_order'], 0, ',', '.') . " đ)<br>";
                                    }
                                    ?>
                                </td>
                                <td><?= number_format($stat['total'], 0, ',', '.') . ' đ' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- Bảng top 5 khách hàng -->
        <h2 class="mt-5">Top 5 Khách Hàng Mua Nhiều Nhất</h2>
        <?php if ($top_stats): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Khách hàng</th>
                            <th>Đơn hàng</th>
                            <th>Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_stats as $stat): ?>
                            <tr>
                                <td><?= htmlspecialchars($stat['NAME']) ?></td>
                                <td>
                                    <?php
                                    $order_stmt = $pdo->prepare("
                                        SELECT dh.IDDH, SUM(ctdh.SL * sp.GIABANKM) as total_order 
                                        FROM dh 
                                        JOIN ctdh ON dh.IDDH = ctdh.IDDH 
                                        JOIN sp ON ctdh.IDSP = sp.IDSP 
                                        WHERE dh.IDKH = ? 
                                        GROUP BY dh.IDDH
                                    ");
                                    $order_stmt->execute([$stat['IDKH']]);
                                    $orders_by_kh = $order_stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($orders_by_kh as $o) {
                                        echo "<a href='detailbill.php?iddh={$o['IDDH']}'>Đơn {$o['IDDH']}</a> (" . number_format($o['total_order'], 0, ',', '.') . " đ)<br>";
                                    }
                                    ?>
                                </td>
                                <td><?= number_format($stat['total'], 0, ',', '.') . ' đ' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center">Không có dữ liệu khách hàng.</p>
        <?php endif; ?>

        <a href="Qldh.php" class="btn btn-secondary mt-4">Quản lý đơn hàng</a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>