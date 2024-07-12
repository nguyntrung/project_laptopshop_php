<!DOCTYPE html>
<html>
    <head>
        <title>Chi tiết sản phẩm</title>
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
        
    </head>
    <body>


<?php
    include("Connection.php");
    session_start();

    $masp = null;
    if (isset($_GET["id"])) {
        $masp = (int)$_GET["id"];
    }

    if ($masp !== null) {
        $sql = "SELECT * FROM SANPHAM WHERE MaSanPham = :masp";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['masp' => $masp]);
        $sp = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Xử lý khi không có sản phẩm nào được chọn
        echo "Không có sản phẩm nào được chọn.";
        exit;
    }

    function getSoLuongDaBanProduct($pdo, $masp) 
    {
        $sqldaban = "SELECT SUM(chitiethoadon.soluong) AS SoLuongDaBan 
                    FROM sanpham 
                    INNER JOIN chitiethoadon ON sanpham.MaSanPham = chitiethoadon.masanpham 
                    WHERE sanpham.MaSanPham = :masp";
        $stmt = $pdo->prepare($sqldaban);
        $stmt->execute(['masp' => $masp]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['SoLuongDaBan'] ?? 0;
    }

    if (isset($_POST["btn_addcart"])) {
        $sql_magiohang = "SELECT MaGioHang FROM GIOHANG WHERE MaTaiKhoan = :mataikhoan";
        $stmt_magiohang = $pdo->prepare($sql_magiohang);
        $stmt_magiohang->execute(['mataikhoan' => $_SESSION["mataikhoan"]]);
        $result = $stmt_magiohang->fetch(PDO::FETCH_ASSOC);

        $magiohang = $result['MaGioHang'];
        $masp = $_POST["masp"];
        $dongia = $_POST["dongia"];
        $soluong = $_POST["soluong"];

        $sql_KTRATonTai = "SELECT * FROM CHITIETGIOHANG WHERE MAGIOHANG = :magiohang AND MASANPHAM = :masp";
        $st_KTRATonTai = $pdo->prepare($sql_KTRATonTai);
        $st_KTRATonTai->execute(['magiohang' => $magiohang, 'masp' => $masp]);

        if ($st_KTRATonTai->rowCount() > 0) 
        {
            // Lấy số lượng hiện tại trong giỏ hàng
            $current_cart_quantity = $st_KTRATonTai->fetch(PDO::FETCH_ASSOC)['SoLuong'];

            // Lấy số lượng hàng còn trong kho của sản phẩm
            $sql_get_stock = "SELECT SoLuong FROM SANPHAM WHERE MaSanPham = :masp";
            $stmt_get_stock = $pdo->prepare($sql_get_stock);
            $stmt_get_stock->execute(['masp' => $masp]);
            $product_stock = $stmt_get_stock->fetch(PDO::FETCH_ASSOC)['SoLuong'];

            // Tính toán số lượng mới sau khi thêm vào giỏ hàng
            $new_quantity = $current_cart_quantity + $soluong;

            if ($new_quantity <= $product_stock) {
                $sql_update_CTGH = "UPDATE CHITIETGIOHANG SET SOLUONG = :soluong, THANHTIEN = :thanhtien WHERE MAGIOHANG = :magiohang AND MASANPHAM = :masp";
                $st_updateCTGH = $pdo->prepare($sql_update_CTGH);
                $st_updateCTGH->execute([
                    'soluong' => $new_quantity,
                    'thanhtien' => $new_quantity * $dongia,
                    'magiohang' => $magiohang,
                    'masp' => $masp
                ]);
            } 
            else 
            {
            ?>
                <div class="alert">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <p style="font-size: 25px;">Thông báo</p>
                    <p style="font-size: 18px; color:white">Số lượng vượt quá số lượng sản phẩm có sẵn.</p>
                </div>
            <?php
            }
        } 
        else 
        {
            $sql_insert_CTGH = "INSERT INTO CHITIETGIOHANG (MaGioHang, MaSanPham, SoLuong, ThanhTien) VALUES (:magiohang, :masp, :soluong, :thanhtien)";
            $st_insertCTGH = $pdo->prepare($sql_insert_CTGH);
            $st_insertCTGH->execute([
                'magiohang' => $magiohang,
                'masp' => $masp,
                'soluong' => $soluong,
                'thanhtien' => $soluong * $dongia
            ]);
        }
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
                <?php
                    foreach($sp as $row)
                    {
                ?>
                        <div class="container bor" style="overflow: hidden; padding: 20px">
                            <div class="col-md-3">
                                    <img src="../images/<?php echo $row["HinhAnh"] ?>" style="width: 100%;">
                            </div>
                            <div class="col-md-9">
                                <h3 style="font-weight: bold;"><?php echo $row["TenSanPham"] ?></h3>
                                <p style="font-size: 14px; margin-bottom: 10px; margin-top: 10px;"><?php echo getSoLuongDaBanProduct($pdo, $row["MaSanPham"]); ?> <span style="font-size: 16px; color:#767676">Đã bán</span></p>
                                <p style="font-size: 16px; margin-bottom: 10px;">Số lượng <span style="font-size: 14px; color:#767676"><?php echo $row["SoLuong"] ?> sản phẩm có sẵn</span></p>
                                <b style="font-size: 25px; margin-bottom: 10px;" class="price"><?php  echo number_format($row["GiaBan"], 0, ',', '.') ?>đ</b><br>
                                
                                <?php
                                    if (isset($_SESSION["mataikhoan"]))
                                    {
                                        if ($row["SoLuong"] > 0)
                                        {
                                ?>
                                            <form method="post" action="ShowDetail.php?id=<?php echo $row["MaSanPham"] ?>">
                                                <input type="hidden" name="masp" value="<?php echo $row["MaSanPham"] ?>">
                                                <input type="hidden" name="dongia" value="<?php echo $row["GiaBan"] ?>">
                                                <input type="hidden" name="soluong" value="1">
                                                <button class="btn btn-danger" name="btn_addcart" value="btn_addcart" style="margin-top: 10px; margin-bottom: 10px; padding: 10px">THÊM VÀO GIỎ HÀNG</button>
                                            </form>
                                <?php
                                        }
                                        else
                                        {
                                ?>
                                            <button class="btn btn" style="margin-top: 10px; margin-bottom: 10px; padding: 10px; background-color: gray;" disabled>THÊM VÀO GIỎ HÀNG</button>
                                <?php
                                        }
                                ?>
                                        
                                <?php
                                    }
                                    else
                                    {
                                        if ($row["SoLuong"] > 0)
                                        {
                                    ?>
                                            <a href="Login.php">
                                                <button class="btn btn-danger" style="margin-top: 10px; margin-bottom: 10px; padding: 10px">
                                                    THÊM VÀO GIỎ HÀNG
                                                </button>
                                            </a>
                                    <?php
                                        }
                                        else
                                        {
                                    ?>
                                            <a href="Login.php" style="text-decoration: none; color: white">
                                                <button class="btn btn" style="margin-top: 10px; margin-bottom: 10px; padding: 10px; background-color: gray;">
                                                    THÊM VÀO GIỎ HÀNG
                                                </button>
                                            </a>
                                    <?php
                                        }
                                ?>
                                <?php
                                    }
                                    
                                ?>

                                <div style="line-height: 40px;">
                                    <p style="font-size: 17px;">
                                        ✔ Bảo hành chính hãng 12 tháng. 
                                    </p>
                                    <p style="font-size: 17px;">
                                        ✔ Hỗ trợ đổi mới trong 7 ngày. 
                                    </p>
                                    <p style="font-size: 17px;">
                                        ✔ Windows bản quyền tích hợp. 
                                    </p>
                                    <p style="font-size: 17px;">
                                        ✔ Miễn phí giao hàng toàn quốc.
                                    </p>
                                </div>
                            </div>
                        </div>
                    <div class="container" style="margin-top: 20px;">
                        <div class="col-md-9 bor" style="padding: 20px">
                            <h4>Thông tin sản phẩm</h4>
                            <h3 style="font-weight: bold; margin-top: 10px; margin-bottom: 10px;">Thông số kỹ thuật</h3>
                            <table style="border: 1px solid #F7F7F7; border-collapse: collapse; width: 100%; table-layout: fixed;">
                                <tr style="padding: 40px">
                                    <td style="font-weight: bold; background-color: #F7F7F7; font-size: 16px; width: 20%;">CPU</td>
                                    <td style="font-size: 16px; word-wrap: break-word; width: 80%; padding: 10px"><?php echo $row["CPU"] ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; background-color: #F7F7F7; font-size: 16px; width: 20%;">RAM</td>
                                    <td style="font-size: 16px; word-wrap: break-word; width: 80%; padding: 10px"><?php echo $row["Ram"] ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; background-color: #F7F7F7; font-size: 16px; width: 20%;">Ổ cứng</td>
                                    <td style="font-size: 16px; word-wrap: break-word; width: 80%; padding: 10px"><?php echo $row["OCung"] ?></td>
                                </tr>
                                <tr>
                                    <th style="font-weight: bold; background-color: #F7F7F7; font-size: 16px; width: 20%;">VGA</th>
                                    <td style="font-size: 16px; word-wrap: break-word; width: 80%; padding: 10px"><?php echo $row["VGA"] ?></td>
                                </tr>
                                <tr>
                                    <th style="font-weight: bold; background-color: #F7F7F7; font-size: 16px; width: 20%;">Màn hình</th>
                                    <td style="font-size: 16px; word-wrap: break-word; width: 80%; padding: 10px"><?php echo $row["ManHinh"] ?></td>
                                </tr>
                                <tr>
                                    <th style="font-weight: bold; background-color: #F7F7F7; font-size: 16px; width: 20%;">Hệ điều hành</th>
                                    <td style="font-size: 16px; word-wrap: break-word; width: 80%; padding: 10px"><?php echo $row["HeDieuHanh"] ?></td>
                                </tr>
                                <tr>
                                    <th style="font-weight: bold; background-color: #F7F7F7; font-size: 16px; width: 20%;">Pin</th>
                                    <td style="font-size: 16px; word-wrap: break-word; width: 80%; padding: 10px"><?php echo $row["Pin"] ?></td>
                                </tr>
                                <tr>
                                    <th style="font-weight: bold; background-color: #F7F7F7; font-size: 16px; width: 20%;">Trọng lượng</th>
                                    <td style="font-size: 16px; word-wrap: break-word; width: 80%; padding: 10px"><?php echo $row["TrongLuong"] ?></td>
                                </tr>
                            </table>
                            <h3 style="font-weight: bold; margin-top: 10px; margin-bottom: 10px;">Đánh giá chi tiết <?php echo $row["TenSanPham"] ?></h3>
                            <p style="font-size: 16px; word-wrap: break-word;"><?php echo $row["MoTa"] ?></p>
                        </div>
                <?php
                    }
                ?>
                        <div class="col-md-3 bor" style="word-wrap: break-word; padding: 20px;">
                            <h4>Sản phẩm tương tự</h4>
                            <?php
                                include("ShowProductSmiliar.php");
                            ?>
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