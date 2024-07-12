<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của bạn</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/slick.css">
    <link rel="stylesheet" href="css/slick-theme.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/Info.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/slick.min.js"></script>
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['mataikhoan'])) {
        header('Location: Login.php');
        exit();
    }

    include("Connection.php");

    $bills = [];
    if (isset($_GET['id'])) {
        $mahoadon = $_GET['id'];
        $sql = 'SELECT sp.TenSanPham, ct.MaHoaDon, sp.HinhAnh, ct.SoLuong, ct.ThanhTien 
                FROM sanpham sp 
                JOIN chitiethoadon ct ON sp.MaSanPham = ct.MaSanPham
                WHERE ct.MaHoaDon = :mahd';
        $st = $pdo->prepare($sql);
        $st->execute([':mahd' => $mahoadon]);
        $bills = $st->fetchAll(PDO::FETCH_OBJ);
    }
    ?>

<div id="wrapper">
    <!--HEADER-->
    <div id="header">
        <div class="container">
            <div class="row" id="header-main">
                <div class="col-md-4">
                    <a href="index.php">
                        <img src="../images/logo-default.png">
                    </a>
                </div>
                <div class="col-md-5">
                    <form class="form-inline" method="post" action="ProductList.php">
                        <div class="form-group" style="border-radius: 5px;">
                            <input type="text" name="txt_search" placeholder="Tìm kiếm..." class="form-control" style="width: 300px; border-radius: 10px; font-size: 13px;">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="col-md-3" id="header-right">
                    <div class="pull-right">
                        <div class="pull-left">
                            <i class="glyphicon glyphicon-phone-alt"></i>
                        </div>
                        <div class="pull-right">
                            <p id="hotline">HOTLINE</p>
                            <p>0986420994</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--END HEADER-->

    <!--MENUNAV-->
    <div id="menunav">
        <div class="container">
            <nav>
                <ul id="menu-main">
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="ProductList.php">Laptop</a></li>
                    <li><a href="LienHe.php">Liên hệ</a></li>
                    <li><a href="GioiThieu.php">Giới thiệu</a></li>
                </ul>
                <ul class="pull-right" id="main-shopping">
                    <li class="account-menu">
                                <?php
                                    if(isset($_SESSION['username'])) {
                                        // Nếu đã đăng nhập, hiển thị tên khách hàng và menu dropdown
                                        echo '<a href="#"><i class="fa fa-user"></i><span style="color: white; font-weight: bold; margin-left: 8px">' . $_SESSION['tenkhachhang'] . '</span></a>';
                                        echo '<div class="account-menu-content">';
                                        echo '<p><a href="CustomerInfo.php">Thông Tin Khách Hàng</a></p>';
                                        echo '<p><a href="Logout.php">Đăng Xuất</a></p>';
                                        echo '</div>';
                                    } else {
                                        // Nếu chưa đăng nhập, hiển thị nút Tài khoản
                                        echo '<a href="#"><i class="fa fa-user"></i><span style="color: white; font-weight: bold; margin-left: 8px">Tài khoản</span></a>';
                                        echo '<div class="account-menu-content">';
                                        echo '<p><a href="Login.php">Đăng Nhập</a></p>';
                                        echo '<p><a href="Register.php">Đăng Ký</a></p>';
                                        echo '</div>';
                                    }
                                ?>
                            </li>
                            <?php
                                if(isset($_SESSION["username"]))
                                {
                            ?>
                                    <li>
                                        <a href="ShowCart.php"><i class="fa fa-shopping-basket"></i></a>
                                    </li>
                            <?php
                                }
                                else
                                {
                            ?>
                                    <li>
                                        <a href="Login.php"><i class="fa fa-shopping-basket"></i></a>
                                    </li>
                            <?php
                                }
                            ?>
                </ul>
            </nav>
        </div>
    </div>
    <!--ENDMENUNAV-->

    <!-- MAINCONTENT -->
    <main id="maincontent">
        <div class="container">
            <div class="row">
                <aside class="col-md-4" style="padding-right: 20px;">
                    <div class="block-account">
                        <h5 class="title-account">TRANG TÀI KHOẢN</h5>
                        <p style="font-size: 14px; margin-bottom: 20px; margin: 0 0 20px 5px; font-weight: 590;">
                            Xin chào, <span style="color:#2d2d2d; font-size: 17px; font-weight: 500;"><?php echo $_SESSION['tenkhachhang']; ?></span>!
                        </p>
                        <ul>
                            <li><a class="title-info" href="CustomerInfo.php">Thông tin tài khoản</a></li>
                            <li><a class="title-info active" href="Orders.php">Đơn hàng của bạn</a></li>
                            <li><a class="title-info" href="ChangePassword.php">Đổi mật khẩu</a></li>
                        </ul>
                    </div>
                </aside>
                <section class="col-md-8" style="padding-left: 60px;">
                    <h1 class="title-head margin-top-0">CHI TIẾT ĐƠN HÀNG CỦA BẠN</h1>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Đơn Hàng</th>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Hình Ảnh</th>
                                    <th>Số Lượng</th>
                                    <th>Thành Tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count($bills) > 0) {
                                    foreach ($bills as $bill) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($bill->MaHoaDon) . "</td>";
                                        echo "<td>" . htmlspecialchars($bill->TenSanPham) . "</td>";
                                        echo "<td><img src='../images/" . htmlspecialchars($bill->HinhAnh) . "' alt='Hình ảnh sản phẩm' width='100'></td>";
                                        echo "<td>" . htmlspecialchars($bill->SoLuong) . "</td>";
                                        echo "<td>" . htmlspecialchars($bill->ThanhTien) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr class='no-orders'><th class='no-border-left' colspan='5' class='text-center'>Không có chi tiết đơn hàng nào.</th></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <!-- END MAINCONTENT -->

    <!-- FOOTER -->
    <div class="container-pluid">
        <section id="footer">
            <div class="container">
                <div class="col-md-3" id="shareicon">
                    <ul>
                        <li><a href=""><i class="fa fa-facebook"></i></a></li>
                        <li><a href=""><i class="fa fa-twitter"></i></a></li>
                        <li><a href=""><i class="fa fa-google-plus"></i></a></li>
                        <li><a href=""><i class="fa fa-youtube"></i></a></li>
                    </ul>
                </div>
                <div class="col-md-8" id="title-block">
                    <div class="pull-left"></div>
                    <div class="pull-right"></div>
                </div>
            </div>
        </section>
        <section id="footer-button">
            <div class="container-pluid">
                <div class="container">
                    <div class="col-md-3" id="ft-about">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco </p>
                    </div>
                    <div class="col-md-3 box-footer">
                        <h3 class="tittle-footer">my accout</h3>
                        <ul>
                            <li><i class="fa fa-angle-double-right"></i><a href=""><i></i> Giới thiệu</a></li>
                            <li><i class="fa fa-angle-double-right"></i><a href=""><i></i> Liên hệ </a></li>
                            <li><i class="fa fa-angle-double-right"></i><a href=""><i></i>  Contact </a></li>
                            <li><i class="fa fa-angle-double-right"></i><a href=""><i></i> My Account</a></li>
                            <li><i class="fa fa-angle-double-right"></i><a href=""><i></i> Giới thiệu</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 box-footer">
                        <h3 class="tittle-footer">my accout</h3>
                        <ul>
                            <li><i class="fa fa-angle-double-right"></i><a href=""><i></i> Giới thiệu</a></li>
                            <li><i class="fa fa-angle-double-right"></i><a href=""><i></i> Liên hệ </a></li>
                            <li><i class="fa fa-angle-double-right"></i><a href=""><i></i>  Contact </a></li>
                            <li><i class="fa fa-angle-double-right"></i><a href=""><i></i> My Account</a></li>
                            <li><i class="fa fa-angle-double-right"></i><a href=""><i></i> Giới thiệu</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3" id="footer-support">
                        <h3 class="tittle-footer"> Liên hệ</h3>
                        <ul>
                            <li>
                                <p><i class="fa fa-home" style="font-size: 16px;padding-right: 5px;"></i> 140 Lê Trọng Tấn - Tây Thạnh - Tân Phú - TP. Hồ Chí Minh </p>
                                <p><i class="sp-ic fa fa-mobile" style="font-size: 22px;padding-right: 5px;"></i> 012345678</p>
                                <p><i class="sp-ic fa fa-envelope" style="font-size: 13px;padding-right: 5px;"></i> support@gmail.com</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <section id="ft-bottom">
            <p class="text-center">Copyright © 2024</p>
        </section>
    </div>
</div>

<script src="js/slick.min.js"></script>
<script type="text/javascript">
    $(function() {
        $hidenitem = $(".hidenitem");
        $itemproduct = $(".item-product");
        $itemproduct.hover(function(){
            $(this).children(".hidenitem").show(100);
        },function(){
            $hidenitem.hide(500);
        })
    })
</script>

<script>
    $(function() {
        $(".account-menu").hover(function(){
            $(this).find(".account-menu-content").show();
        }, function(){
            $(this).find(".account-menu-content").hide();
        });
    });
</script>
</body>
</html>
