<!DOCTYPE html>
<html>
    <head>
        <title>Giỏ hàng</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        
        <script  src="js/jquery-3.2.1.min.js"></script>
        <script  src="js/bootstrap.min.js"></script>
        <!---->
        <link rel="stylesheet" type="text/css" href="css/slick.css"/>
        <link rel="stylesheet" type="text/css" href="css/slick-theme.css"/>
        <!--slide-->
        <link rel="stylesheet" type="text/css" href="css/style.css">
        
        <style>
            button[name="btn_decrease"] {
                width: 25px;
                height: 25px;
                font-size: 30px;  /* Điều chỉnh kích thước font lên 30px */
                line-height: 25px;
                color: white;
                background-color: gray;
                border: none;
                border-radius: 50%;
                text-align: center;
                padding: 0;
                margin: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                vertical-align: middle;
            }
            button[name="btn_increase"] {
                width: 25px;
                height: 25px;
                font-size: 20px;  /* Điều chỉnh kích thước font lên 30px */
                line-height: 25px;
                color: white;
                background-color: gray;
                border: none;
                border-radius: 50%;
                text-align: center;
                padding: 0;
                margin: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                vertical-align: middle;
            }
            .alert {
                padding: 20px;
                background-color: #f44336;
                color: white;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 99999; /* Ensure it is above all other elements */
                width: 80%; /* Optional: Adjust width as needed */
                max-width: 500px; /* Optional: Set a max-width */
                text-align: center;
            }

            .closebtn {
                margin-left: 15px;
                color: white;
                font-weight: bold;
                float: right;
                font-size: 22px;
                line-height: 20px;
                cursor: pointer;
                transition: 0.3s;
            }

            .closebtn:hover {
                color: black;
            }
        </style>
    </head>
    <body>


    <?php
        include("Connection.php");
        session_start();

        // Get the MaGioHang for the current user
        $sql_magiohang = "SELECT MaGioHang FROM GIOHANG WHERE MaTaiKhoan = :mataikhoan";
        $stmt_magiohang = $pdo->prepare($sql_magiohang);
        $stmt_magiohang->execute(['mataikhoan' => $_SESSION["mataikhoan"]]);
        $result = $stmt_magiohang->fetch(PDO::FETCH_ASSOC);
        $magiohang = $result['MaGioHang'];

        // Load cart details
        $sql = "SELECT CHITIETGIOHANG.MASANPHAM, SANPHAM.TENSANPHAM, SANPHAM.HINHANH, SANPHAM.GIABAN, CHITIETGIOHANG.SOLUONG, CHITIETGIOHANG.THANHTIEN, SANPHAM.SOLUONG AS SANPHAM_SOLUONG 
                FROM CHITIETGIOHANG 
                JOIN SANPHAM ON CHITIETGIOHANG.MASANPHAM = SANPHAM.MASANPHAM 
                WHERE CHITIETGIOHANG.MAGIOHANG = :magiohang";
        $st1 = $pdo->prepare($sql);
        $st1->execute(['magiohang' => $magiohang]);
        $chitietgiohang = $st1->fetchAll(PDO::FETCH_OBJ);

        // Delete items with zero quantity
        foreach ($chitietgiohang as $row) {
            if ($row->SANPHAM_SOLUONG == 0) {
                $sql_delete = "DELETE FROM CHITIETGIOHANG WHERE MaGioHang = :magiohang AND MaSanPham = :masp";
                $stmt_delete = $pdo->prepare($sql_delete);
                $stmt_delete->execute(['magiohang' => $magiohang, 'masp' => $row->MASANPHAM]);
            }
        }

        // Reload cart details after deletion
        $st1->execute(['magiohang' => $magiohang]);
        $chitietgiohang = $st1->fetchAll(PDO::FETCH_OBJ);

        if (isset($_POST["btn_increase"]))
        {
            $masp = $_POST["masp"];
            $sl = $_POST["sl"];
            $sl += 1;

            $sql_get_stock = "SELECT SoLuong FROM SANPHAM WHERE MaSanPham = :masp";
            $stmt_get_stock = $pdo->prepare($sql_get_stock);
            $stmt_get_stock->execute(['masp' => $masp]);
            $product_stock = $stmt_get_stock->fetch(PDO::FETCH_ASSOC)['SoLuong'];

            $sql_get_giaban = "SELECT GiaBan FROM SANPHAM WHERE MASANPHAM = :masp";
            $st_get_giaban = $pdo->prepare($sql_get_giaban);
            $st_get_giaban->execute([
                'masp' => $masp
            ]);
            $giaban_product = $st_get_giaban->fetch(PDO::FETCH_ASSOC)["GiaBan"];

            if ($sl <= $product_stock)
            {
                $sql_update_CTGH = "UPDATE CHITIETGIOHANG SET SOLUONG = :soluong, THANHTIEN = :thanhtien WHERE MAGIOHANG = :magiohang AND MASANPHAM = :masp";
                $st_updateCTGH = $pdo->prepare($sql_update_CTGH);
                $st_updateCTGH->execute([
                    'soluong' => $sl,
                    'thanhtien' => $sl * $giaban_product,
                    'magiohang' => $magiohang,
                    'masp' => $masp
                ]);
            }
            else if ($sl > $product_stock)
            {
                $sql_update_CTGH = "UPDATE CHITIETGIOHANG SET SOLUONG = :soluong, THANHTIEN = :thanhtien WHERE MAGIOHANG = :magiohang AND MASANPHAM = :masp";
                $st_updateCTGH = $pdo->prepare($sql_update_CTGH);
                $st_updateCTGH->execute([
                    'soluong' => 1,
                    'thanhtien' => 1 * $giaban_product,
                    'magiohang' => $magiohang,
                    'masp' => $masp
                ]);
            }
            // Load lại thông tin chi tiết giỏ hàng sau khi cập nhật
            $st1->execute(['magiohang' => $magiohang]);
            $chitietgiohang = $st1->fetchAll(PDO::FETCH_OBJ);
        }
        if (isset($_POST["btn_decrease"]))
        {
            $masp = $_POST["masp"];
            $sl = $_POST["sl"];
            $sl -= 1;

            
            $sql_get_stock = "SELECT SoLuong FROM SANPHAM WHERE MaSanPham = :masp";
            $stmt_get_stock = $pdo->prepare($sql_get_stock);
            $stmt_get_stock->execute(['masp' => $masp]);
            $product_stock = $stmt_get_stock->fetch(PDO::FETCH_ASSOC)['SoLuong'];

            $sql_get_giaban = "SELECT GiaBan FROM SANPHAM WHERE MASANPHAM = :masp";
            $st_get_giaban = $pdo->prepare($sql_get_giaban);
            $st_get_giaban->execute([
                'masp' => $masp
            ]);
            $giaban_product = $st_get_giaban->fetch(PDO::FETCH_ASSOC)["GiaBan"];

            if ($sl > 0)
            {
                if ($sl > $product_stock)
                {
                    $sql_update_CTGH = "UPDATE CHITIETGIOHANG SET SOLUONG = :soluong, THANHTIEN = :thanhtien WHERE MAGIOHANG = :magiohang AND MASANPHAM = :masp";
                    $st_updateCTGH = $pdo->prepare($sql_update_CTGH);
                    $st_updateCTGH->execute([
                        'soluong' => 1,
                        'thanhtien' => 1 * $giaban_product,
                        'magiohang' => $magiohang,
                        'masp' => $masp
                    ]);
                }
                else
                {
                    $sql_update_CTGH = "UPDATE CHITIETGIOHANG SET SOLUONG = :soluong, THANHTIEN = :thanhtien WHERE MAGIOHANG = :magiohang AND MASANPHAM = :masp";
                    $st_updateCTGH = $pdo->prepare($sql_update_CTGH);
                    $st_updateCTGH->execute([
                        'soluong' => $sl,
                        'thanhtien' => $sl * $giaban_product,
                        'magiohang' => $magiohang,
                        'masp' => $masp
                    ]);
                }
            }
            // Load lại thông tin chi tiết giỏ hàng sau khi cập nhật
            $st1->execute(['magiohang' => $magiohang]);
            $chitietgiohang = $st1->fetchAll(PDO::FETCH_OBJ);
        }

        if (isset($_GET["DeleteItem"]))
        {
            $masp = (int)$_GET["DeleteItem"];

            $sql_deleteitem = "DELETE FROM CHITIETGIOHANG WHERE MASANPHAM = :masp";
            $st_deleteitem = $pdo->prepare($sql_deleteitem);
            $st_deleteitem->execute([
                'masp' => $masp
            ]);
            // Load lại thông tin chi tiết giỏ hàng sau khi cập nhật
            $st1->execute(['magiohang' => $magiohang]);
            $chitietgiohang = $st1->fetchAll(PDO::FETCH_OBJ);
        }
        if (isset($_GET["DeleteAll"]))
        {
            $sql_deleteAllitem = "DELETE FROM CHITIETGIOHANG WHERE MAGIOHANG = :magiohang";
            $st_deleteAllitem = $pdo->prepare($sql_deleteAllitem);
            $st_deleteAllitem->execute([
                'magiohang' => $magiohang
            ]);
            // Load lại thông tin chi tiết giỏ hàng sau khi cập nhật
            $st1->execute(['magiohang' => $magiohang]);
            $chitietgiohang = $st1->fetchAll(PDO::FETCH_OBJ);
        }
        if (isset($_GET["MuaHang"]))
        {
            foreach($chitietgiohang as $row)
            {
                $sql_get_stock = "SELECT SoLuong FROM SANPHAM WHERE MaSanPham = :masp";
                $stmt_get_stock = $pdo->prepare($sql_get_stock);
                $stmt_get_stock->execute(['masp' => $row->MASANPHAM]);
                $product_stock = $stmt_get_stock->fetch(PDO::FETCH_ASSOC)['SoLuong'];
                if ($row->SOLUONG > $product_stock)
                {
                ?>
                    <div class="alert">
                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                        <p style="font-size: 25px;">Thông báo</p>
                        <p style="font-size: 18px; color:white">Số lượng vượt quá số lượng sản phẩm có sẵn.</p>
                    </div>
                <?php
                    break;
                }
                else
                {
                    header("Location: CheckOut.php");
                    exit();
                }
            }
        }
        function showTongTien($chitietgiohang)
        {
            $total_price = 0;
            foreach($chitietgiohang as $row) 
            {
                $total_price += $row->THANHTIEN; 
            }
            return number_format($total_price, 0, ',', '.');
        }
    ?>


        <div id="wrapper">
            <!---->
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
                                        <input type="search" name="txt_search" placeholder="Tìm kiếm..." class="form-control" style="width: 300px; border-radius: 10px; font-size: 13px;">
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
                            <li>
                                <a href="index.php">Trang chủ</a>
                            </li>
                            <li>
                                <a href="ProductList.php">Laptop</a>
                            </li>
                            <li>
                                <a href="LienHe.php">Liên hệ</a>
                            </li>
                            <li>
                                <a href="GioiThieu.php">Giới thiệu</a>
                            </li>
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
                        <!--end Shopping-->
                    </nav>
                </div>
            </div>
            <!--ENDMENUNAV-->
            
            <div id="maincontent">
                <div class="container">
                    <div class="col-md-12 bor">
                        <section class="box-main1">
                            <h3 class="title-main" style="text-align: center;"><a href="javascript:void(0)"> Giỏ hàng </a> </h3>
                            <?php
                                if (count($chitietgiohang) > 0)
                                {
                            ?>
                                    <table class="table">
                                        <tr>
                                            <td style="font-size: 16px;">Sản Phẩm</td>
                                            <td style="font-size: 16px;">Đơn Giá</td>
                                            <td style="font-size: 16px;">Số Lượng</td>
                                            <td style="font-size: 16px;">Số Tiền</td>
                                            <td style="font-size: 16px;">Thao Tác</td>
                                        </tr>
                                        <?php
                                            foreach($chitietgiohang as $row)
                                            {
                                        ?>
                                                <tr>
                                                    <td style="word-break: break-all; vertical-align: middle;">
                                                        <a style="font-size: 16px;" href="ShowDetail.php?id=<?php echo $row->MASANPHAM ?>">
                                                            <img src="../images/<?php echo $row->HINHANH ?>" width="60" height="60">
                                                            <?php echo $row->TENSANPHAM ?>
                                                        </a>
                                                    </td>
                                                    <td style="font-size: 16px; vertical-align: middle;"><?php echo number_format($row->GIABAN, 0, ',', '.') ?>đ</td>
                                                    <td style="font-size: 16px; vertical-align: middle;">
                                                        <form style="font-size: 16px;" method="post" action="ShowCart.php">
                                                            <input type="hidden" name="masp" value="<?php echo $row->MASANPHAM ?>">
                                                            <input type="hidden" name="sl" value="<?php echo $row->SOLUONG ?>">
                                                            <button name="btn_decrease" value="btn_decrease">-</button>
                                                            &nbsp;<?php echo $row->SOLUONG ?>&nbsp;
                                                            <button name="btn_increase" value="btn_increase">+</button>
                                                        </form>
                                                    </td>
                                                    <td style="font-size: 16px; vertical-align: middle;" class="price"><?php echo number_format($row->THANHTIEN, 0, ',', '.') ?>đ</td>
                                                    <td style="vertical-align: middle;"><a style="font-size: 16px;" href="ShowCart.php?DeleteItem=<?php echo $row->MASANPHAM ?>">Xóa</a></td>
                                                </tr>
                                        <?php
                                            }
                                        ?>
                                        <tr>
                                            <td colspan="5">
                                                <a style="font-size: 16px;" href="ShowCart.php?DeleteAll=1">Xóa tất cả</a>
                                            </td>
                                        </tr>
                                    </table>
                            <?php
                                }
                                else
                                {
                            ?>
                                    <h4 style="height: 500px; text-align: center; margin-top: 25px; color: red; font-weight: bold;">Giỏ hàng của bạn đang rỗng</h4>
                            <?php
                                }
                            ?>
                        </section>
                    </div>
                </div>
                <?php
                    if (count($chitietgiohang) > 0)
                    {
                ?>
                        <div class="container" style="margin-top: 20px">
                            <div class="col-md-7">
                                
                            </div>
                            <div class="col-md-5 bor">
                                <div style="width: 100%; margin-bottom: 10px; margin-top: 10px;">
                                    <p style="display: inline-block; font-size: 16px; width: 55%;">
                                        Tổng thanh toán (<?php echo count($chitietgiohang) ?> sản phẩm): 
                                    </p>
                                    <p style="display: inline-block; font-size: 20px; text-align: right; width: 40%;" class="price">
                                            <?php echo showTongTien($chitietgiohang); ?>đ
                                    </p>
                                </div>
                                <a href="ShowCart.php?MuaHang=1">
                                    <button style="width: 100%;" class="btn btn-danger">Mua Hàng</button>
                                </a>
                            </div>
                        </div>
                <?php
                    }
                ?>
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
                                        
                                        <p><i class="fa fa-home" style="font-size: 16px;padding-right: 5px;"></i> Ngách 56/34 số nhà 22 phường đức thắng </p>
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
    </div>
            </div>      
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