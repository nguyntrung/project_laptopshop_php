<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/slick.css"/>
    <link rel="stylesheet" type="text/css" href="css/slick-theme.css"/>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/Logincss.css">
    <title>MaxShop_Register</title>
</head>

<?php
    session_start();
    include("Connection.php");

    $error_message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        

        // Kiểm tra mật khẩu và xác nhận mật khẩu khớp nhau
        if ($password != $confirm_password) {
            $error_message = "Mật khẩu và xác nhận mật khẩu không khớp!";
        } else {
            // Kiểm tra xem tên tài khoản đã tồn tại hay chưa
            $sql = "SELECT * FROM user WHERE TenTaiKhoan = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $error_message = "Tên tài khoản đã tồn tại!";
            } else {
                // Thêm tài khoản mới vào cơ sở dữ liệu
                $sql = "INSERT INTO user (TenKhachHang, TenTaiKhoan, MatKhau, Email) VALUES (:fullname, :username, :password, :email)";
                $sql2 = "INSERT INTO giohang (MaTaiKhoan) VALUES (:matk)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':fullname', $fullname);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password); // Lưu mật khẩu nguyên thủy
                $stmt->bindParam(':email', $email);

                if ($stmt->execute()) {
                    $matk = $pdo->lastInsertId();
                    $stmt2 = $pdo->prepare($sql2);
                    $stmt2->bindParam(':matk', $matk);
                    $stmt2->execute();

                    $_SESSION['username'] = $username;
                    $_SESSION['tenkhachhang'] = $fullname;
                    $_SESSION['email'] = $email;
                    header("Location: Login.php");
                    exit();
                } else {
                    $error_message = "Đã xảy ra lỗi khi đăng ký tài khoản!";
                }
            }
        }
    }
?>

<body>
    <!--HEADER-->
    <div id="header">
        <div class="container">
            <div class="row" id="header-main">
                <div class="col-md-4">
                    <a href="index.php">
                        <img src="../images/logo-default.png" alt="Logo">
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
                <!--menu main-->
                <ul id="menu-main">
                    <li><a href="index.php">Trang chủ</a></li>
                    <li><a href="ProductList.php">Laptop</a></li>
                    <li><a href="LienHe.php">Liên hệ</a></li>
                    <li><a href="GioiThieu.php">Giới thiệu</a></li>
                </ul>
                <!-- end menu main-->
                <!--Shopping-->
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
                <!--end Shopping-->
            </nav>
        </div>
    </div>

    <div class="container login-container">
        <h3>ĐĂNG KÝ TÀI KHOẢN</h3>
        <form id="form_register" method="post" action="Register.php">
            <p>Tên Tài Khoản: <span class="required">*</span></p>
            <input type="text" name="username" placeholder="Nhập tên tài khoản" required><br>
            <p>Tên Khách Hàng: <span class="required">*</span></p>
            <input type="text" name="fullname" placeholder="Nhập tên khách hàng" required><br>
            <p>Mật Khẩu: <span class="required">*</span></p>
            <input type="password" name="password" placeholder="Mật khẩu" required><br>
            <p>Xác Nhận Mật Khẩu: <span class="required">*</span></p>
            <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu" required><br>
            <p>Email: <span class="required">*</span></p>
            <input type="email" name="email" placeholder="Nhập email" required><br>
            <input type="submit" class="btn-login" value="Đăng Ký">
            <?php if ($error_message != "") { ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php } ?>
        </form>
        <p class="sign_up">Bạn đã có tài khoản ? <a href="login.php">Đăng nhập tại đây.</a></p>
    </div>

    <div class="container-pluid">
        <section id="footer">
            <div class="container">
                <div class="col-md-3" id="shareicon">
                    <ul>
                        <li>
                            <a href=""><i class="fa fa-facebook"></i></a>
                        </li>
                        <li>
                            <a href=""><i class="fa fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href=""><i class="fa fa-google-plus"></i></a>
                        </li>
                        <li>
                            <a href=""><i class="fa fa-youtube"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8" id="title-block">
                    <div class="pull-left">
                    </div>
                    <div class="pull-right">
                    </div>
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
                    <div class="col-md-3 box-footer" >
                        <h3 class="tittle-footer">my accout</h3>
                        <ul>
                            <li>
                                <i class="fa fa-angle-double-right"></i>
                                <a href=""><i></i> Giới thiệu</a>
                            </li>
                            <li>
                                <i class="fa fa-angle-double-right"></i>
                                <a href=""><i></i> Liên hệ </a></li>
                            <li>
                                <i class="fa fa-angle-double-right"></i>
                                <a href=""><i></i>  Contact </a></li>
                            <li>
                                <i class="fa fa-angle-double-right"></i>
                                <a href=""><i></i> My Account</a></li>
                            <li>
                                <i class="fa fa-angle-double-right"></i>
                                <a href=""><i></i> Giới thiệu</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 box-footer">
                        <h3 class="tittle-footer">my accout</h3>
                        <ul>
                            <li>
                                <i class="fa fa-angle-double-right"></i>
                                <a href=""><i></i> Giới thiệu</a></li>
                            <li>
                                <i class="fa fa-angle-double-right"></i>
                                <a href=""><i></i> Liên hệ </a></li>
                            <li>
                                <i class="fa fa-angle-double-right"></i>
                                <a href=""><i></i>  Contact </a></li>
                            <li>
                                <i class="fa fa-angle-double-right"></i>
                                <a href=""><i></i> My Account</a></li>
                            <li>
                                <i class="fa fa-angle-double-right"></i>
                                <a href=""><i></i> Giới thiệu</a></li>
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
</body>
</html>

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
