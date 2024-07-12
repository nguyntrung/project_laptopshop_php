<?php
session_start();
require 'Connection.php'; // Kết nối đến cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Kiểm tra xem email có tồn tại trong cơ sở dữ liệu không
    $stmt = $pdo->prepare("SELECT * FROM user WHERE Email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Nếu email tồn tại, lưu email vào session và chuyển hướng đến trang ResetPassword.php
        $_SESSION['reset_email'] = $email;

        // Chuyển hướng người dùng đến trang ResetPassword.php
        header('Location: ResetPassword.php');
        exit();
    } else {
        // Nếu email không tồn tại, hiển thị thông báo lỗi
        $_SESSION['email_error'] = "Email không tồn tại trong hệ thống.";
        header('Location: ForgetPassword.php');
        exit();
    }
}
?>

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
    <title>MaxShop_ForgetPassword</title>
</head>
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
        <h3>QUÊN MẬT KHẨU</h3>
        <form id="form_forgot_password" method="POST" action="ForgetPassword.php">
            <p>Nhập Email của bạn để đặt lại mật khẩu: <span class="required">*</span></p>
            <input style = "" type="email" name="email" placeholder="Nhập Email" required><br>
            <input type="submit" class="btn-login" value="Gửi Yêu Cầu">
            <?php
                if (isset($_SESSION['email_error'])) {
                    echo '<p style="color: red;">' . $_SESSION['email_error'] . '</p>';
                    unset($_SESSION['email_error']);
                }
            ?>
        </form>
        <p class="sign_up">Quay lại trang <a href="login.php">đăng nhập.</a></p>
    </div>

   <!-- FOOTER -->
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
                                            <a href=""><i></i> Liên hệ </a>
                                        </li>
                                        <li>
                                            <i class="fa fa-angle-double-right"></i>
                                            <a href=""><i></i>  Contact </a>
                                        </li>
                                        <li>
                                            <i class="fa fa-angle-double-right"></i>
                                            <a href=""><i></i> My Account</a>
                                        </li>
                                        <li>
                                            <i class="fa fa-angle-double-right"></i>
                                            <a href=""><i></i> Giới thiệu</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-3 box-footer">
                                    <h3 class="tittle-footer">my accout</h3>
                                    <ul>
                                        <li>
                                            <i class="fa fa-angle-double-right"></i>
                                            <a href=""><i></i> Giới thiệu</a>
                                        </li>
                                        <li>
                                            <i class="fa fa-angle-double-right"></i>
                                            <a href=""><i></i> Liên hệ </a>
                                        </li>
                                        <li>
                                            <i class="fa fa-angle-double-right"></i>
                                            <a href=""><i></i>  Contact </a>
                                        </li>
                                        <li>
                                            <i class="fa fa-angle-double-right"></i>
                                            <a href=""><i></i> My Account</a>
                                        </li>
                                        <li>
                                            <i class="fa fa-angle-double-right"></i>    
                                            <a href=""><i></i> Giới thiệu</a>
                                        </li>
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
            <!-- END MAINCONTENT -->
        </div>     

        <script  src="js/slick.min.js"></script>
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
