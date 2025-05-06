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
       /* Cải thiện bố cục tổng thể */
.shop.spad {
    padding: 60px 0;
    background-color: #f8f9fa;
}

/* Container sản phẩm */
.product__item {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 30px;
    overflow: hidden;
}

.product__item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

/* Hình ảnh sản phẩm */
.product__item__pic {
    height: 220px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-bottom: 1px solid #eee;
    background: #fff;
}

.product__item__pic img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.product__item:hover .product__item__pic img {
    transform: scale(1.05);
}

/* Thông tin sản phẩm */
.product__item__text {
    padding: 20px;
    text-align: center;
}

.product__item__text h6 {
    font-size: 1.1rem;
    margin-bottom: 10px;
    font-weight: 600;
    color: #333;
}

.product__item__text h6 a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product__item__text h6 a:hover {
    color: #119206;
}

.product__price {
    font-size: 1rem;
    color: #119206;
    font-weight: 700;
    margin-bottom: 15px;
}

.product__price span {
    color: #999;
    font-size: 0.9rem;
    text-decoration: line-through;
    margin-left: 10px;
}

/* Nút hành động */
.product__actions {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.product__actions button {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.edit-product {
    background-color: #119206;
    color: #fff;
}

.edit-product:hover {
    background-color: #0e7a05;
    transform: translateY(-2px);
}

.delete-product {
    background-color: #ff6347;
    color: #fff;
}

.delete-product:hover {
    background-color: #e5533d;
    transform: translateY(-2px);
}

/* Nút quay lại */
.back-button {
    display: inline-block;
    padding: 12px 24px;
    border: none;
    background-color: #119206;
    color: #fff;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.back-button:hover {
    background-color: #0e7a05;
    transform: translateY(-2px);
}

/* Phân trang */
.pagination {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    margin: 40px 0;
}

.pagination a {
    padding: 10px 16px;
    border: 1px solid #ddd;
    color: #333;
    border-radius: 8px;
    background-color: #fff;
    text-decoration: none;
    font-size: 1rem;
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

.pagination a:hover {
    background-color: #119206;
    color: #fff;
    border-color: #119206;
}

.pagination a.active {
    background-color: #119206;
    color: #fff;
    border-color: #119206;
    font-weight: 600;
}

/* Thông báo không có kết quả */
.no-results {
    font-size: 1.2rem;
    color: #555;
    text-align: center;
    padding: 40px 0;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .product__item {
        margin-bottom: 20px;
    }

    .product__item__pic {
        height: 180px;
    }

    .product__item__text {
        padding: 15px;
    }

    .back-button {
        padding: 10px 20px;
        font-size: 0.9rem;
    }

    .pagination a {
        padding: 8px 12px;
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .product__item__pic {
        height: 150px;
    }

    .product__actions {
        flex-direction: column;
        gap: 8px;
    }

    .product__actions button {
        width: 100%;
    }
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
