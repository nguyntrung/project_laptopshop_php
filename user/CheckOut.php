<!DOCTYPE html>
<html>
    <head>
        <title>Thanh toán</title>
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

        <?php
            include("Connection.php");
            session_start();
            
            $sql_magiohang = "SELECT MaGioHang FROM GIOHANG WHERE MaTaiKhoan = :mataikhoan";
            $stmt_magiohang = $pdo->prepare($sql_magiohang);
            $stmt_magiohang->execute(['mataikhoan' => $_SESSION["mataikhoan"]]);
            $result = $stmt_magiohang->fetch(PDO::FETCH_ASSOC);

            $magiohang = $result['MaGioHang'];

            $sql_load = "SELECT CHITIETGIOHANG.MASANPHAM, SANPHAM.TENSANPHAM, SANPHAM.HINHANH, SANPHAM.GIABAN, CHITIETGIOHANG.SOLUONG, CHITIETGIOHANG.THANHTIEN, SANPHAM.SOLUONG AS SANPHAM_SOLUONG 
            FROM CHITIETGIOHANG 
            JOIN SANPHAM ON CHITIETGIOHANG.MASANPHAM = SANPHAM.MASANPHAM 
            WHERE CHITIETGIOHANG.MAGIOHANG = :magiohang";
            $st_load = $pdo->prepare($sql_load);
            $st_load->execute(['magiohang' => $magiohang]);
            $chitietgiohang1 = $st_load->fetchAll(PDO::FETCH_OBJ);

            function showTongTien($chitietgiohang) 
            {
                $total_price = 0;
                foreach($chitietgiohang as $row) 
                {
                    $total_price += $row->THANHTIEN; 
                }
                return $total_price;
            }

            function updateSLTon($chitietgiohang, $pdo) {
                foreach($chitietgiohang as $item) {
                    $updateSLTon = "UPDATE SANPHAM SET SoLuong = SoLuong - :soluong WHERE MaSanPham = :masp";
                    $st_updateSL = $pdo->prepare($updateSLTon);
                    $st_updateSL->execute([
                        'soluong' => $item->SOLUONG,
                        'masp' => $item->MASANPHAM
                    ]);
                }
            }

            $tb = null;

            if (isset($_POST["btn_thanhtoan"])) 
            {
                $magiohang = $_POST["magiohang"];

                $sql = "SELECT CHITIETGIOHANG.MASANPHAM, SANPHAM.TENSANPHAM, SANPHAM.HINHANH, SANPHAM.GIABAN, CHITIETGIOHANG.SOLUONG, CHITIETGIOHANG.THANHTIEN, SANPHAM.SOLUONG AS SANPHAM_SOLUONG 
                FROM CHITIETGIOHANG 
                JOIN SANPHAM ON CHITIETGIOHANG.MASANPHAM = SANPHAM.MASANPHAM 
                WHERE CHITIETGIOHANG.MAGIOHANG = :magiohang";
                $st1 = $pdo->prepare($sql);
                $st1->execute(['magiohang' => $magiohang]);
                $chitietgiohang = $st1->fetchAll(PDO::FETCH_OBJ);

                $selected_option = $_POST["choices"];
                if ($selected_option == 1) 
                {
                    $selected_option = "Thanh toán khi nhận hàng";
                } 
                else 
                {
                    $selected_option = "Chuyển khoản ngân hàng";
                }

                $diachi = $_POST["addrs"];
                $sdt = $_POST["phone"];

                if (!preg_match('/^0\d{9}$/', $sdt)) 
                {
                    $tb = "Vui lòng kiểm tra lại SĐT";
                }
                else
                {
                    $sql_inserthd = "INSERT INTO HOADON (MaTaiKhoan, NgayMua, DiaChi, SDT, HinhThucThanhToan, TongTien, TrangThai) VALUES (:matk, NOW(), :diachi, :sdt, :hinhthuc, :tongtien, :trangthai)";
                    $st_inserthd = $pdo->prepare($sql_inserthd);
                    $st_inserthd->execute([
                        'matk' => $_SESSION["mataikhoan"],
                        'diachi' => $diachi,
                        'sdt' => $sdt,
                        'hinhthuc' => $selected_option,
                        'tongtien' => showTongTien($chitietgiohang) + 100000,
                        'trangthai' => 'Chưa xác nhận'
                    ]);
                    
                    $mahd = $pdo->lastInsertId();
    
                    foreach ($chitietgiohang as $row) 
                    {
                        $sql_insertcthd = "INSERT INTO chitiethoadon (MaHoaDon, MaSanPham, SoLuong, ThanhTien) VALUES (:mahd, :masp, :soluong, :thanhtien)";
                        $st_insertcthd = $pdo->prepare($sql_insertcthd);
                        $st_insertcthd->execute([
                            'mahd' => $mahd,
                            'masp' => $row->MASANPHAM,
                            'soluong' => $row->SOLUONG,
                            'thanhtien' => $row->THANHTIEN
                        ]);
                    }
    
                    updateSLTon($chitietgiohang, $pdo);
    
                    $sql_deleteAllitem = "DELETE FROM CHITIETGIOHANG WHERE MAGIOHANG = :magiohang";
                    $st_deleteAllitem = $pdo->prepare($sql_deleteAllitem);
                    $st_deleteAllitem->execute([
                        'magiohang' => $magiohang
                    ]);
                ?>
                    <div class="alert">
                        <p style="font-size: 25px;">Thông báo</p>
                        <p style="font-size: 18px; color:white">Đặt hàng thành công.</p>
                    </div>
                    <script>
                        setTimeout(function() {
                            window.location.href = "index.php";
                        }, 2000);
                    </script>
                <?php
                    exit();
                }
            }

            function showGioHangCheckOut($chitietgiohang1)
            {   
                foreach($chitietgiohang1 as $row)
                {
                    echo '<li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center" style="width: 100%">
                                <p style="font-size: 14px; display: inline-block; width: 65%; word-break: break-all;">'. $row->TENSANPHAM .'</p>
                                <p style="display: inline-block; width: 34%; text-align: right;"><span style="font-size: 14px;" class="badge text-secondary rounded-pill">'. number_format($row->THANHTIEN, 0, ',', '.') .'đ</span></p>
                            </div>
                            <p style="font-size: 13px; margin: 0;">Số lượng: '. $row->SOLUONG .' x Đơn giá: '. number_format($row->GIABAN, 0, ',', '.') .'đ</p>
                        </li>'; 
                }
            }
        ?>
    </head>
    <body>
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
            <div id="maincontent">
                <div class="container">
                <!--ENDMENUNAV-->
                    <div class="col-md-7 bor">
                        <section class="box-main1">
                            <h3 class="title-main" style="text-align: center;"><a href="javascript:void(0)"> Thanh toán </a> </h3>
                            <p style="font-size: 20px; margin-top: 20px; margin-bottom: 20px;">Địa chỉ đơn hàng</p>
                            <form method="post" action="CheckOut.php">
                                <input type="hidden" name="magiohang" value="<?php echo $magiohang ?>">
                                <label style="font-size: 16px; font-weight: normal;" for="addrs" style="margin-top: 20px;">Địa chỉ</label><br>
                                <input class="form-control" placeholder="Địa chỉ" type="text" name="addrs" id="addrs" required><br>
                                <label style="font-size: 16px; font-weight: normal;" for="phone" style="margin-top: 20px;">Số điện thoại</label><br>
                                <input class="form-control" placeholder="Số điện thoại" type="number" name="phone" id="phone" required>
                                <?php
                                    if (!empty($tb))
                                    {
                                    ?>
                                        <p style="color: red; font-weight: bold; font-size: 13px; margin-top: 5px;"><?php echo $tb ?></p>
                                    <?php
                                    }
                                ?>
                                <br>
                                <label style="font-size: 16px; font-weight: normal;" for="choices">Phương thức thanh toán</label><br>
                                <select style="width: 400px; height: 30px; font-size: 16px;" id="choices" name="choices" class="form-select" aria-label="Default select example">
                                    <option style="font-size: 16px;" selected value="1">Thanh toán khi nhận hàng</option>
                                    <option style="font-size: 16px;" value="2">Chuyển khoản ngân hàng</option>
                                </select>
                                <hr>
                                <button style="margin-top: 20px; margin-bottom: 20px; width: 100%;" class="btn btn-danger" name="btn_thanhtoan" value="btn_thanhtoan">Đặt Hàng</button>
                            </form>
                        </section>
                    </div>
                    <div class="col-md-5">
                        <p style="font-size: 20px; float: left;">Giỏ hàng</p>
                        <span style="float: right; width: 30px; text-align: center; border-radius: 20px; background-color: grey; color: white; font-weight: bold; font-size: 14px;"><?php echo count($chitietgiohang1) ?></span>
                        <ul style="clear: both;" class="list-group">
                            <?php showGioHangCheckOut($chitietgiohang1) ?>
                            <li style="font-size: 15px;" class="list-group-item d-flex justify-content-between align-items-center">
                                <p style="font-size: 15px; display: inline-block; width: 30%;">Tổng tiền hàng</p>
                                <p style="display: inline-block; width: 69%; text-align: right;"><span style="font-size: 15px;"><?php echo number_format(showTongTien($chitietgiohang1), 0, ',', '.') ?>đ</span></p>
                                <p style="font-size: 15px; display: inline-block; width: 30%;">Phí vận chuyển</p>
                                <p style="display: inline-block; width: 69%; text-align: right;"><span style="font-size: 15px;">100.000đ</span></p>
                                <p style="font-size: 15px; display: inline-block; width: 30%;">Tổng thanh toán</p>
                                <p style="display: inline-block; width: 69%; text-align: right;"><span style="font-size: 20px;" class="price"><?php echo number_format(showTongTien($chitietgiohang1) + 100000, 0, ',', '.') ?>đ</span></p>
                            </li>
                        </ul>
                    </div>
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