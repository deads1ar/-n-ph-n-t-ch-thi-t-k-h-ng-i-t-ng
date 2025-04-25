<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "web_db");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Get search parameters
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$brands = isset($_GET['brand']) ?  $_GET['brand'] : [];
$min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 1000000;
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 5000000;
$current_page = basename($_SERVER['SCRIPT_NAME']);
if (!isset($_GET['page'])) {
    // Convert the $brands array into a query-friendly string (comma-separated)
    $brands_query = !empty($brands) ? '&brand[]=' . implode('&brand[]=', $brands) : '';

    // Redirect with all parameters properly formatted
    header('Location: ' . $_SERVER['PHP_SELF'] . '?page=1'
        . (!empty($keyword) ? '&keyword=' . urlencode($keyword) : '')
        . $brands_query
        . (!empty($max_price) ? '&max_price=' . $max_price : '1000000')
        . (!empty($min_price) ? '&min_price=' . $min_price : '5000000'));
    exit(); // Stop further execution after redirect
}

    else {
        $page = intval($_GET['page']);
    }
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ashion Template">
    <meta name="keywords" content="Ashion, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ashion with Fashion</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cookie&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__close">+</div>
        <ul class="offcanvas__widget">
            <li><span class="icon_search search-switch"></span></li>
        </ul>
        <div class="offcanvas__logo">
            <a href="./index.html"><img src="img/logo.png" alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__auth">
            <a href="dangnhap.html">Đăng Nhập</a>
            <a href="dangnhap.html">Đăng Kí</a>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <?php include 'header.php' ?>
    <!-- Header Section End -->

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="/index.php"><i class="fa fa-home"></i> Trang chủ</a>
                        <span>Sản phẩm</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="shop__sidebar">
                    <div class="sidebar__sizes">
                    <div class="section-title">
                    <h4>TÌM KIẾM SẢN PHẨM</h4>
                    </div>
                         <form action="ketquatimkiem.php?" method="GET">
                         <input type="text" name="keyword" class="search-input" placeholder="Nhập tên sản phẩm" value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" style="margin-bottom: 20px;">
                    <div class="section-title">
                     <h4>TÌM THEO THƯƠNG HIỆU</h4>
                    </div>
                                <div class="size__list">
                                    <label for="nike">
                                        Nike
                                        <input type="checkbox" id="nike" name="brand[]" value="Nike" <?php echo (isset($_GET['brand']) && in_array('Nike', $_GET['brand'])) ? 'checked' : ''; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                    <label for="adidas">
                                        Adidas
                                        <input type="checkbox" id="adidas" name="brand[]" value="Adidas" <?php echo (isset($_GET['brand']) && in_array('Adidas', $_GET['brand'])) ? 'checked' : ''; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                    <label for="jordan">
                                        New Balance
                                        <input type="checkbox" id="jordan" name="brand[]" value="New Balance" <?php echo (isset($_GET['brand']) && in_array('New Balance', $_GET['brand'])) ? 'checked' : ''; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="sidebar__filter">
                                    <div class="section-title">
                                        <h4>TÌM THEO GIÁ</h4>
                                    </div>
                                    <div class="filter-range-wrap">
                                        <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" data-min="1000000" data-max="5000000"></div>
                                        <div class="range-slider">
                                            <div class="price-input">
                                                <p>Giá:</p>
                                                <input type="text" id="minamount" name="min_price" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '1000000'; ?>" readonly>
                                                <input type="text" id="maxamount" name="max_price" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '5000000'; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="site-btn">Tìm kiếm</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9">                   
                    <?php include 'display_header.php'
                // include display header NIKE-ADIDAS-JORDAN-KETQUATIMKIEM 
                ?> 
                <div class="row">                
    <?php
    // Build SQL query
    $limit = 9;
    $offset = ($page - 1) * $limit;
    $sql = "SELECT * FROM sp WHERE 1=1";
    if (!empty($keyword)) {
        $sql .= " AND TEN LIKE '%" . $conn->real_escape_string($keyword) . "%'";
    }
    if (!empty($brands) && is_array($brands)) {
        $brands_str = "'" . implode("','", array_map([$conn, 'real_escape_string'], $brands)) . "'";
        $sql .= " AND IDLSP IN (SELECT IDLSP FROM loaisp WHERE TENLOAI IN ($brands_str))";
    }
    $sql .= " AND GIABANKM BETWEEN $min_price AND $max_price";
    $sql .= " LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        while ($row = $result->fetch_assoc()) { ?>
            <div class="col-lg-4 col-md-6">
            <div class="product__item">
            <div class="product__item__pic set-bg" data-setbg="<?php echo $row['URL']; ?>">
            <ul class="product__hover">
            <li><a href="<?php echo $row['URL']; ?>" class="image-popup"><span class="arrow_expand"></span></a></li>
            <li><a href="#" onclick="addToCart(<?php echo $row['IDSP']; ?>,1)"><span class="icon_bag_alt"></span></a></li>
                                        <script>
                                        function addToCart(productId, quantity) {
                                            fetch('cart_handler.php', {
                                                method: 'POST',
                                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                body: `id=${productId}&quantity=${quantity}`
                                            })
                                            .then(response => response.text())
                                            .then(data => {
                                                if (data === "NOT_LOGGED_IN") {
                                                    alert("Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!");
                                                    window.location.href = '/web2/dangnhap.html'; // Redirect to login page
                                                } else if (data === "SUCCESS") {
                                                    alert("Thêm sản phẩm thành công!");
                                                } else {
                                                    alert("Có lỗi xảy ra!");
                                                }
                                            })
                                            .catch(error => console.error("Error:", error));
                                        }
                                        </script>
            </ul>
            </div>
            <div class="product__item__text">
            <h6><a href="chitietsanpham.php?id=<?php echo $row['IDSP'] ?>"><?php echo $row['TEN'] ?></a></h6>
            <br> 
            <div class="product__price"><?php echo number_format($row['GIABANKM'],0,'','.') ?>đ <span><?php echo number_format($row['GIABAN'],0,'','.') ?>đ</span></div>
            </div>
            </div>
            </div>
    <?php } 
    } 
    else {
        echo  '<div class="col-lg-12 text-center">Không tìm thấy sản phẩm nào.</div>';
    }
    ?>
                                <?php
                                /* PHP FOR PAGE NAVIGATION */
                                    // Pagination variables
$limit = 9;
$offset = ($page - 1) * $limit;
// Query to count total products
$sql_count = "SELECT COUNT(*) AS total FROM sp WHERE 1=1";
if (!empty($keyword)) {
    $sql_count .= " AND TEN LIKE '%" . $conn->real_escape_string($keyword) . "%'";
}
if (!empty($brands) && is_array($brands)) {
    $brands_str = "'" . implode("','", array_map([$conn, 'real_escape_string'], $brands)) . "'";
    $sql_count .= " AND IDLSP IN (SELECT IDLSP FROM loaisp WHERE TENLOAI IN ($brands_str))";
}
$sql_count .= " AND REPLACE(GIABANKM, '.', '') BETWEEN $min_price AND $max_price";

$result_count = $conn->query($sql_count);
$totalproduct = ($result_count->num_rows > 0) ? $result_count->fetch_assoc()['total'] : 0;

// Calculate total pages
$totalpage = ceil($totalproduct / $limit);
                                ?>
                        <div class="col-lg-12 text-center">
                            <div class="pagination__option">
                            <?php  include 'page_navigation.php' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section End -->

    <!-- Instagram Begin -->
    <div class="instagram">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="img/instagram/insta-1.jpg">
                        <div class="instagram__text">
                            <i class="fa fa-instagram"></i>
                            <a href="#">@ ashion_shop</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="img/instagram/insta-2.jpg">
                        <div class="instagram__text">
                            <i class="fa fa-instagram"></i>
                            <a href="#">@ ashion_shop</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="img/instagram/insta-3.jpg">
                        <div class="instagram__text">
                            <i class="fa fa-instagram"></i>
                            <a href="#">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="img/instagram/insta-4.jpg">
                        <div class="instagram__text">
                            <i class="fa fa-instagram"></i>
                            <a href="https://www.instagram.com/_hbaohuyy/">@_hbaohuyy ig
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="img/instagram/insta-5.jpg">
                        <div class="instagram__text">
                            <i class="fa fa-instagram"></i>
                            <a href="#">@ ashion_shop</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                    <div class="instagram__item set-bg" data-setbg="img/instagram/insta-6.jpg">
                        <div class="instagram__text">
                            <i class="fa fa-instagram"></i>
                            <a href="#">@ ashion_shop</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Instagram End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-7">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="./index.html"><img src="img/logo.png" alt=""></a>
                        </div>
                        <p>Trang web bán giày chuyên cung cấp các mẫu giày thời trang, đa dạng từ thể thao đến công sở. Sản phẩm đảm bảo chất lượng cao, với nhiều lựa chọn về kiểu dáng và kích cỡ phù hợp cho mọi lứa tuổi.</p>
                        <div class="footer__payment">
                            <a href="#"><img src="img/payment/payment-1.png" alt=""></a>
                            <a href="#"><img src="img/payment/payment-2.png" alt=""></a>
                            <a href="#"><img src="img/payment/payment-3.png" alt=""></a>
                            <a href="#"><img src="img/payment/payment-4.png" alt=""></a>
                            <a href="#"><img src="img/payment/payment-5.png" alt=""></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-5">
                    <div class="footer__widget">
                        <h6>Đường dẫn</h6>
                        <ul>
                            <li><a href="#">Về chúng tôi</a></li>
                            <li><a href="#">Thông tin liên lạc</a></li>
                            <li><a href="#">Hỏi đáp cùng Ashion</a></li>
                            <li><a href="#">Blogs</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-4">
                    <div class="footer__widget">
                        <h6>Tài khoản</h6>
                        <ul>
                            <li><a href="#">Tài khoản của tôi</a></li>
                            <li><a href="#">Theo dõi đơn hàng</a></li>
                            <li><a href="#">Thanh toán</a></li>
                            <li><a href="#">Danh sách yêu thích</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-8 col-sm-8">
                    <div class="footer__newslatter">
                        <h6>Tạp chí Ashion</h6>
                        <form action="#">
                            <input type="text" placeholder="Email">
                            <button type="submit" class="site-btn">Theo dõi</button>
                        </form>
                        <div class="footer__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-youtube-play"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    <div class="footer__copyright__text">
                        <p>Copyright &copy; <script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a></p>
                    </div>
                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Search Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.nicescroll.min.js"></script>
    <script src="js/main.js"></script>
    <script>
    $(document).ready(function() {
        // Hàm định dạng số với dấu phẩy và ký hiệu "đ"
        function formatNumber(number) {
            return number + " đ";
        }

        // Lấy giá trị min_price và max_price từ PHP
        var minPrice = <?php echo isset($_GET['min_price']) ? (int)$_GET['min_price'] : 1000000; ?>;
        var maxPrice = <?php echo isset($_GET['max_price']) ? (int)$_GET['max_price'] : 5000000; ?>;

        // Đảm bảo giá trị nằm trong khoảng hợp lệ
        minPrice = Math.max(1000000, Math.min(minPrice, 5000000));
        maxPrice = Math.max(minPrice, Math.min(maxPrice, 5000000));

        // Định dạng giá trị ban đầu cho input
        $("#minamount").val(formatNumber(minPrice));
        $("#maxamount").val(formatNumber(maxPrice));
        // Khởi tạo thanh trượt với giá trị từ form
        $(".price-range").slider({
            range: true,
            min: 1000000, // Giá trị tối thiểu của thanh trượt
            max: 5000000, // Giá trị tối đa của thanh trượt
            values: [minPrice, maxPrice], // Giá trị hiện tại từ form
            slide: function(event, ui) {
                $("#minamount").val(formatNumber(ui.values[0]));
                $("#maxamount").val(formatNumber(ui.values[1]));
            }
        });
    });
</script>
</body>
</html>