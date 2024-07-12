<!DOCTYPE html>
<html>
   <head>
      <title>Trang chủ</title>
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
        body {
            font-family: 'Times New Roman', Times, serif;
        }
        .content {
            margin: 0 200px;
            
        }
        .content h3 {
            text-align: center;
            margin: 10px;
        }
        .content p, strong, .content ul li {
            margin: 10px 0;
            font-size: 14px;
        }
        .content ul {
            padding-left: 20px;
        }
    </style>
      <?php
         include("Connection.php");
         session_start();
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
                        if (isset($_SESSION["username"]))
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
         <div class="content" style="margin: 0 200px;">
            <h3 style="text-align: center; margin: 10px">GIỚI THIỆU</h3>
            <p>Chào mừng đến với <strong>MaxShop.vn</strong> – điểm đến hàng đầu cho mọi nhu cầu về laptop của bạn!</p>
            <p><strong>Về Chúng Tôi</strong></p>
            <p><strong>MaxShop.vn</strong> là nền tảng thương mại điện tử chuyên cung cấp các sản phẩm laptop chất lượng cao từ các thương hiệu nổi tiếng trên thế giới. Chúng tôi tự hào mang đến cho khách hàng những trải nghiệm mua sắm trực tuyến tiện lợi, an toàn và đáng tin cậy.</p>
            <p><strong>Sứ Mệnh Của Chúng Tôi</strong></p>
            <p>- Sứ mệnh của MaxShop.vn là cung cấp các sản phẩm laptop chất lượng với giá cả cạnh tranh, đồng thời mang đến dịch vụ chăm sóc khách hàng chu đáo và chuyên nghiệp. Chúng tôi cam kết:</p>
            <ul>
                <li><strong>+ Sản Phẩm Đa Dạng:</strong> Cung cấp một loạt các dòng laptop từ các thương hiệu nổi tiếng như Apple, Dell, HP, Lenovo, ASUS, Acer, và nhiều hơn nữa.</li>
                <li><strong>+ Giá Cả Hợp Lý:</strong> Mang đến giá cả cạnh tranh nhất trên thị trường, kèm theo các chương trình khuyến mãi và giảm giá hấp dẫn.</li>
                <li><strong>+ Giao Hàng Nhanh Chóng:</strong> Đảm bảo giao hàng nhanh chóng và an toàn đến tay khách hàng.</li>
                <li><strong>+ Hỗ Trợ Khách Hàng:</strong> Đội ngũ hỗ trợ khách hàng nhiệt tình, sẵn sàng giải đáp mọi thắc mắc và hỗ trợ kỹ thuật, bảo hành sản phẩm.</li>
            </ul>
            <p><strong>Tại Sao Chọn MaxShop.vn?</strong></p>
            <ul>
                <li><strong>+ Dễ Dàng Tìm Kiếm và Lựa Chọn:</strong> Với công cụ tìm kiếm và lọc sản phẩm thông minh, bạn có thể dễ dàng tìm thấy chiếc laptop phù hợp nhất với nhu cầu của mình.</li>
                <li><strong>+ So Sánh Sản Phẩm:</strong> Tính năng so sánh giúp bạn dễ dàng đánh giá và lựa chọn sản phẩm tốt nhất.</li>
                <li><strong>+ Đánh Giá và Nhận Xét:</strong> Xem đánh giá từ những khách hàng khác để có quyết định mua sắm thông minh.</li>
                <li><strong>+ Thanh Toán Linh Hoạt:</strong> Hỗ trợ nhiều phương thức thanh toán an toàn và tiện lợi.</li>
            </ul>
            <p><strong>Cam Kết Của Chúng Tôi</strong></p>
            <p>- Chúng tôi cam kết không ngừng cải tiến và hoàn thiện dịch vụ, mang đến cho bạn những trải nghiệm mua sắm trực tuyến tuyệt vời nhất. Với MaxShop.vn, bạn hoàn toàn yên tâm về chất lượng sản phẩm và dịch vụ.</p>
            <p><strong>MaxShop.vn</strong> – Điểm đến tin cậy cho mọi nhu cầu về laptop của bạn!</p>
            <p>- Hãy trải nghiệm ngay hôm nay và khám phá thế giới laptop đa dạng, chất lượng cùng chúng tôi.</p>
        </div>

         <div id="maincontent">
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
                              quis nostrud exercitation ullamco 
                           </p>
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