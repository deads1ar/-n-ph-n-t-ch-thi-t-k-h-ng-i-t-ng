<!DOCTYPE html>
<html lang="vi">

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
    <link rel="stylesheet" href="web2/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="web2/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="web2/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="web2/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="web2/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="web2/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="web2/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="web2/css/style.css" type="text/css">
    <link rel="stylesheet" href="web2/css/index.css" type="text/css">

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
            <li><a href="#"><span class="icon_heart_alt"></span>
                <div class="tip">2</div>
            </a></li>
            <li><a href="#"><span class="icon_bag_alt"></span>
                <div class="tip">2</div>
            </a></li>
        </ul>
        <div class="offcanvas__logo">
            <a href="../index.html"><img src="web2/img/logo.png" alt=""></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__auth">
            <a href="dangnhap.html">Đăng nhập</a>
        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
   <?php include 'web2/header.php' ?> 
    <!-- Header Section End -->

    <!-- Categories Section Begin -->
    <section class="categories">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 p-0">
                    <div class="categories__item categories__large__item set-bg"
                    data-setbg="../web2/img/categories/12.jpg">
                    <div class="categories__text">
                        <h1>Nike</h1>
                        <p><strong style="color: rgb(37, 20, 13);">Thương hiệu Nike ấn tượng với hình Logo như đôi cánh đã miêu tả ý nghĩa trên, điều này đã nhanh chóng trở thành biểu tượng địa vị trong làng thời trang hip hop, thành phố hiện đại nhờ gắn liền với thành công trong việc sản xuất các thiết bị và thời trang thể thao.</strong></p>
                        <a href="sanpham_nike.php">Mua ngay</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                        <div class="categories__item set-bg" data-setbg="../web2/img/categories/3.jpg">
                            <div class="categories__text">
                                <h4>Adidas</h4>
                                <p>13 items</p>
                                <a href="./sanpham_adidas.php">Mua ngay</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                        <div class="categories__item set-bg" data-setbg="../web2/img/categories/7.jpg">
                            <div class="categories__text">
                                <h4>Liên hệ</h4>
                                <a href="https://www.instagram.com/_hbaohuyy/">Liên hệ ngay</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                        <div class="categories__item set-bg" data-setbg="../web2/img/categories/10.jpg">
                            <div class="categories__text">
                                <h4>Jordan</h4>
                                <p>159 items</p>
                                <a href="sanpham_jordan.php">Mua ngay</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 p-0">
                        <div class="categories__item set-bg" data-setbg="../web2/img/categories/8.png">
                            <div class="categories__text">
                                <h4>Phụ kiện</h4>
                                <p>4 items</p>
                                <a href="sanpham_nike.php">Shop now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Categories Section End -->

<!-- Product Section Begin -->
<section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="section-title">
                        <h4>Sản phẩm mới</h4>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8">
                    <ul class="filter__controls">
                        <li class="active" data-filter="*">Tất cả</li>
                        <?php
                        // Kết nối cơ sở dữ liệu
                        $conn = new mysqli("localhost", "root", "", "web_db");

                        if ($conn->connect_error) {
                            die("Kết nối thất bại: " . $conn->connect_error);
                        }

                        // Truy vấn danh sách thương hiệu từ bảng loaisp
                        $sql_brands = "SELECT TENLOAI FROM loaisp";
                        $result_brands = $conn->query($sql_brands);

                        if ($result_brands->num_rows > 0) {
                            while ($brand_row = $result_brands->fetch_assoc()) {
                                $brand_name = htmlspecialchars($brand_row['TENLOAI']);
                                echo '<li data-filter="' . $brand_name . '">' . $brand_name . '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="owl-carousel owl-theme product-slider">
                <?php
                // Truy vấn lấy 8 sản phẩm mới nhất (dựa trên IDSP giảm dần)
                $sql = "SELECT sp.*, loaisp.TENLOAI 
                        FROM sp 
                        JOIN loaisp ON sp.IDLSP = loaisp.IDLSP 
                        ORDER BY sp.IDSP DESC LIMIT 8";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Lấy thương hiệu từ cơ sở dữ liệu
                        $brand = htmlspecialchars($row['TENLOAI']);

                        // Hiển thị sản phẩm với thuộc tính data-brand
                        echo '<div class="product__item mix" data-brand="' . $brand . '">';
                        echo '<div class="product__item__pic set-bg" data-setbg="' . htmlspecialchars($row["URL"]) . '">';
                        echo '<div class="label new">Mới</div>';
                        echo '<ul class="product__hover">';
                        echo '<li><a href="' . htmlspecialchars($row["URL"]) . '" class="image-popup"><span class="arrow_expand"></span></a></li>';
                        echo '<li><a href="#"><span class="icon_bag_alt"></span></a></li>';
                        echo '</ul>';
                        echo '</div>';
                        echo '<div class="product__item__text">';
                        echo '<h6><a href="./chitietsanpham.html" target="_blank">' . htmlspecialchars($row["TEN"]) . '</a></h6>';
                        echo '<div class="rating">';
                        echo '<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>';
                        echo '</div>';
                        echo '<div class="product__price">' . number_format(str_replace('.', '', $row["GIABAN"]), 0) . ' đ';
                        if (!empty($row["GIABANKM"])) {
                            echo ' <span>' . number_format(str_replace('.', '', $row["GIABANKM"]), 0) . ' đ</span>';
                        }
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p style="text-align:center; width:100%;">Không có sản phẩm mới nào.</p>';
                }

                // Đóng kết nối
                $conn->close();
                ?>
            </div>
        </div>
    </section>
<!-- Product Section End -->



<!-- Banner Section Begin -->
<section class="banner set-bg" data-setbg="img/banner/banner1.jpg">
    <div class="container">
        <div class="row">
            <div class="col-xl-7 col-lg-8 m-auto">
                <div class="banner__slider owl-carousel">
                    <div class="banner__item">
                        <div class="banner__text">
                            <h1>Nike's Just do it</h1>
                            <a href="../web2/sanpham_nike.php">Mua ngay</a>
                        </div>
                    </div>
                    <div class="banner__item">
                        <div class="banner__text">
                            <h1>Adidas's Impossible is nothing</h1>
                            <a href="../web2/sanpham_nike.php">Mua ngay</a>
                        </div>
                    </div>
                    <div class="banner__item">
                        <div class="banner__text">
                            <span>The Chloe Collection</span>
                            <h1>Jordan's Our Turn</h1>
                            <a href="../web2/sanpham.php">Mua ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Banner Section End -->
<!-- Discount Section Begin -->
<section class="discount">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 p-0">
                <div class="discount__pic">
                    <img src="../web2/img/discount.jpg" alt="">
                </div>
            </div>
            <div class="col-lg-6 p-0">
                <div class="discount__text">
                    <div class="discount__text__title">
                        <span>Discount</span>
                        <h2>Winter 2024</h2>
                        <h5><span>Sale</span> 50%</h5>
                    </div>
                    <div class="discount__countdown" id="countdown-time">
                        <div class="countdown__item">
                            <span>22</span>
                            <p>Days</p>
                        </div>
                        <div class="countdown__item">
                            <span>18</span>
                            <p>Hour</p>
                        </div>
                        <div class="countdown__item">
                            <span>46</span>
                            <p>Min</p>
                        </div>
                        <div class="countdown__item">
                            <span>05</span>
                            <p>Sec</p>
                        </div>
                    </div>
                    <a href="#">Mua ngay</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Discount Section End -->

<!-- Services Section Begin -->
<section class="services spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-car"></i>
                    <h6>Miễn phí Ship</h6>
                    <p>Cho đơn hàng trên 5.000.000 đ</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-money"></i>
                    <h6>Thanh toán gọn gàng</h6>
                    <p>Tiền mặt hoặc chuyển khoản</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-support"></i>
                    <h6>Hỗ trợ 24/7</h6>
                    <p>Đội ngũ hỗ trợ tận tình</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="services__item">
                    <i class="fa fa-headphones"></i>
                    <h6>Chuyển khoản an toàn</h6>
                    <p>100% bảo mật thông tin</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Section End -->

<!-- Instagram Begin -->
<div class="instagram">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/2.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="https://www.instagram.com/minhla.tu/" target="_blank">@nhom4</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/6.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="https://www.instagram.com/minhla.tu/" target="_blank">@nhom4</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/9.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="https://www.instagram.com/minhla.tu/" target="_blank">@nhom4</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/11.png">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="https://www.instagram.com/minhla.tu/" target="_blank">@nhom4</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/15.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="https://www.instagram.com/minhla.tu/" target="_blank">@nhom4</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4 p-0">
                <div class="instagram__item set-bg" data-setbg="img/instagram/7.jpg">
                    <div class="instagram__text">
                        <i class="fa fa-instagram"></i>
                        <a href="https://www.instagram.com/minhla.tu/" target="_blank">@nhom4</a>
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
            <input type="text" id="search-input" placeholder="Giày giá rẻ">
            <a href="./shop.html" class="cart-btn"><span class="icon_bag_alt"></span> Tìm</a>
        </form>
    </div>
</div>
<!-- Search End -->

<!-- Js Plugins -->
<script src="web2/js/jquery-3.3.1.min.js"></script>
<script src="web2/js/bootstrap.min.js"></script>
<script src="web2/js/jquery.magnific-popup.min.js"></script>
<script src="web2/js/jquery-ui.min.js"></script>
<script src="web2/js/mixitup.min.js"></script>
<script src="web2/js/jquery.countdown.min.js"></script>
<script src="web2/js/jquery.slicknav.js"></script>
<script src="web2/js/owl.carousel.min.js"></script>
<script src="web2/js/jquery.nicescroll.min.js"></script>
<script src="web2/js/main.js"></script>
<script src="web2/js/authen.js"></script>
</body>
</html>