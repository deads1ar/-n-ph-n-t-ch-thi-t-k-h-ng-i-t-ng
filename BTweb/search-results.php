<?php
include 'db.php';
include 'headerad.php';

// Nhận dữ liệu tìm kiếm từ form
$search_name = isset($_POST['search_name']) ? trim($_POST['search_name']) : '';
$brands = isset($_POST['brands']) && is_array($_POST['brands']) ? $_POST['brands'] : [];
$price_min = isset($_POST['price_min']) ? max(0, (int)$_POST['price_min']) : 0;
$price_max = isset($_POST['price_max']) ? max(0, (int)$_POST['price_max']) : 10000000;

// Đảm bảo price_max >= price_min
if ($price_max < $price_min) {
    $temp = $price_max;
    $price_max = $price_min;
    $price_min = $temp;
}

// Xây dựng truy vấn SQL
$sql = "
    SELECT sp.*, loaisp.TENLOAI, (SELECT COUNT(*) FROM ctdh WHERE ctdh.IDSP = sp.IDSP) as SOLD 
    FROM sp 
    JOIN loaisp ON sp.IDLSP = loaisp.IDLSP
    WHERE 1=1
";
$params = [];

if (!empty($search_name)) {
    $sql .= " AND sp.TEN LIKE ?";
    $params[] = "%$search_name%";
}

if (!empty($brands)) {
    $placeholders = implode(',', array_fill(0, count($brands), '?'));
    $sql .= " AND loaisp.TENLOAI IN ($placeholders)";
    $params = array_merge($params, $brands);
}

$sql .= " AND sp.GIABANKM BETWEEN ? AND ?";
$params[] = $price_min;
$params[] = $price_max;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <title>Kết Quả Tìm Kiếm</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 40px;
            margin-bottom: 60px;
        }
        .pagination a {
            display: inline-block !important;
            padding: 8px 14px;
            border: 1px solid #ddd;
            color: #333;
            border-radius: 4px;
            background-color: #fff;
            transition: background-color 0.3s, color 0.3s;
            text-decoration: none;
            font-size: 16px;
        }
        .pagination a:hover {
            background-color: #f1f1f1;
            color: #000;
        }
        .pagination a.active {
            background-color: #111;
            color: #fff;
            border-color: #111;
        }
        .back-button {
            font-size: 1.2em;
            padding: 10px 20px;
            border: none;
            background-color: #119206;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #ff6347;
        }
        .product__item__pic {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid #ddd;
        }
        .product__item__pic img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
            max-height: 200px;
        }
        .no-results {
            font-size: 1.2em;
            color: #555;
            text-align: center;
            margin: 40px 0;
        }
    </style>
</head>
<body>
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="text-center mb-4">Kết Quả Tìm Kiếm</h2>
                    <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                        <a href="Qlsp.php"><button class="back-button">Quay lại</button></a>
                    </div>
                    <div class="row" id="product-list">
                        <?php if (empty($products)): ?>
                            <div class="col-lg-12 text-center">
                                <p class="no-results">Không tìm thấy sản phẩm nào phù hợp.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <?php
                                // Xử lý đường dẫn ảnh
                                $image_url = !empty($product['URL']) 
                                    ? htmlspecialchars($product['URL']) 
                                    : 'images/default-product.jpg'; // Ảnh mặc định nếu không có URL
                                ?>
                                <div class="col-lg-4 col-md-6 product__item">
                                    <div class="product__item__pic">
                                        <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($product['TEN']); ?>">
                                    </div>
                                    <div class="product__item__text">
                                        <h6><a href="#"><?php echo htmlspecialchars($product['TEN']); ?></a></h6>
                                        <div class="product__price"><?php echo number_format($product['GIABANKM'], 0, ',', '.'); ?> đ <span><?php echo number_format($product['GIABAN'], 0, ',', '.'); ?> đ</span></div>
                                        <div class="product__actions">
                                            <a href="edit-product.php?id=<?php echo htmlspecialchars($product['IDSP']); ?>"><button class="edit-product">✎ Sửa</button></a>
                                            <a href="delete-product.php?id=<?php echo htmlspecialchars($product['IDSP']); ?>" onclick="return confirmDelete('<?php echo htmlspecialchars($product['IDSP']); ?>')"><button class="delete-product">− Xóa</button></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-12">
                        <div id="pagination-controls" class="pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        function confirmDelete(id) {
            <?php
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM ctdh WHERE IDSP = ?");
            foreach ($products as $product) {
                $stmt->execute([$product['IDSP']]);
                $sold = $stmt->fetchColumn();
                echo "if (id === '" . htmlspecialchars($product['IDSP']) . "' && $sold > 0) { return confirm('Sản phẩm đã được bán. Bạn có muốn ẩn nó không?'); }";
            }
            ?>
            return confirm('Bạn có chắc muốn xóa sản phẩm này không?');
        }

        document.addEventListener("DOMContentLoaded", function() {
            const products = document.querySelectorAll(".product__item");
            const productsPerPage = 6;
            const totalProducts = products.length;
            const totalPages = Math.ceil(totalProducts / productsPerPage);
            let currentPage = 1;

            function showPage(page) {
                products.forEach((product, index) => {
                    product.style.display = (index >= (page - 1) * productsPerPage && index < page * productsPerPage) ? "block" : "none";
                });
                updatePagination(page);
            }

            function updatePagination(activePage) {
                const paginationContainer = document.getElementById("pagination-controls");
                paginationContainer.innerHTML = "";

                if (totalPages > 1) {
                    if (activePage > 1) {
                        paginationContainer.innerHTML += `<a href="#" data-page="${activePage - 1}">« Trước</a>`;
                    }

                    for (let i = 1; i <= totalPages; i++) {
                        paginationContainer.innerHTML += `<a href="#" data-page="${i}" class="${i === activePage ? 'active' : ''}">${i}</a>`;
                    }

                    if (activePage < totalPages) {
                        paginationContainer.innerHTML += `<a href="#" data-page="${activePage + 1}">Tiếp »</a>`;
                    }
                }

                document.querySelectorAll("#pagination-controls a").forEach(link => {
                    link.addEventListener("click", function(e) {
                        e.preventDefault();
                        currentPage = parseInt(this.getAttribute("data-page"));
                        showPage(currentPage);
                    });
                });
            }

            showPage(currentPage);
        });
    </script>
</body>
</html>