<?php
include 'db.php';
include 'headerad.php';
$stmt = $pdo->query("
    SELECT sp.*, loaisp.TENLOAI, (SELECT COUNT(*) FROM ctdh WHERE ctdh.IDSP = sp.IDSP) as SOLD 
    FROM sp 
    JOIN loaisp ON sp.IDLSP = loaisp.IDLSP
");

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?> 
<!DOCTYPE html>
<html lang="zxx">
<head>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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

</style>

</head>
<body>
 
     <!-- Shop Section Begin -->
     <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="shop__sidebar">
                        <div class="sidebar__sizes">
                            <div class="section-title">
                                <form id="filter-form" method="POST" action="search-results.php">
                                    <input type="text" class="search-input" id="search-name" name="search_name" placeholder="Nhập tên sản phẩm">
                                </div>
                            </div>
                            <div class="sidebar__sizes">
                                <div class="section-title">
                                    <h4>TÌM THEO THƯƠNG HIỆU</h4>
                                </div>
                                <div class="size__list">
                                    <label for="nike">
                                        Nike
                                        <input type="checkbox" name="brands[]" value="Nike" id="nike">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label for="adidas">
                                        Adidas
                                        <input type="checkbox" name="brands[]" value="Adidas" id="adidas">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label for="newbalance">
                                        New Balance
                                        <input type="checkbox" name="brands[]" value="New Balance" id="newbalance">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="sidebar__filter">
                                <div class="section-title">
                                    <h4>TÌM THEO GIÁ</h4>
                                </div>
                                <div class="filter-range-wrap">
                                    <div id="price-range" class="price-range" data-min="0" data-max="10000000"></div>
                                    <div class="range-slider">
                                        <div class="price-input">
                                            <p>Giá:</p>
                                            <input type="text" id="minamount" name="price_min" readonly>
                                            <input type="text" id="maxamount" name="price_max" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" id="apply-filter">Tìm</button>
                        </form>
                    </div>
                </div>
            <!-- Shop Section End -->
            <!-- detail product start -->
                <div class="col-lg-9 col-md-9">
                    <!--add product -->
                <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                <a href="add-product.php"><button class="add-product">&#43; Thêm sản phẩm</button></a>
                </div>
                    <div class="row">
                    <?php foreach ($products as $product): ?>
    <div class="col-lg-4 col-md-6">
        <div class="product__item">
            <div class="product__item__pic">
                <img src="<?php echo htmlspecialchars($product['URL']); ?>" alt="<?php echo htmlspecialchars($product['TEN']); ?>" style="max-width: 100%; height: auto; object-fit: contain; max-height: 300px;">
            </div>
            <div class="product__item__text">
                <h6><a href="#"><?php echo htmlspecialchars($product['TEN']); ?></a></h6>
                <div class="product__price"><?php echo number_format($product['GIABANKM'], 0, ',', '.'); ?> đ <span><?php echo number_format($product['GIABAN'], 0, ',', '.'); ?> đ</span></div>
                <div class="product__actions">
                    <a href="edit-product.php?id=<?php echo $product['IDSP']; ?>"><button class="edit-product">&#9998; Sửa</button></a>
                    <a href="delete-product.php?id=<?php echo $product['IDSP']; ?>" onclick="return confirmDelete(<?php echo $product['IDSP']; ?>)"><button class="delete-product">&#8722; Xóa</button></a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
                        <div class="col-lg-12">
                        <div id="pagination-controls" class="pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </section>
    
    <?php include 'footer.php'; ?>
    <!-- JS Plugins -->
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
                echo "if (id == {$product['IDSP']} && $sold > 0) { return confirm('Sản phẩm đã được bán. Bạn có muốn ẩn nó không?'); }";
            }
            ?>
            return confirm('Bạn có chắc muốn xóa sản phẩm này không?');
        }
    </script>
    <script>
        function confirmDelete(id) {
            <?php
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM ctdh WHERE IDSP = ?");
            foreach ($products as $product) {
                $stmt->execute([$product['IDSP']]);
                $sold = $stmt->fetchColumn();
                echo "if (id == {$product['IDSP']} && $sold > 0) { return confirm('Sản phẩm đã được bán. Bạn có muốn ẩn nó không?'); }";
            }
            ?>
            return confirm('Bạn có chắc muốn xóa sản phẩm này không?');
        }

        $(document).ready(function() {
            // Khởi tạo thanh trượt giá
            $("#price-range").slider({
                range: true,
                min: 0,
                max: 10000000,
                values: [0, 10000000],
                slide: function(event, ui) {
                    $("#minamount").val(ui.values[0]);
                    $("#maxamount").val(ui.values[1]);
                }
            });
            $("#minamount").val($("#price-range").slider("values", 0));
            $("#maxamount").val($("#price-range").slider("values", 1));

            // Phân trang
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
