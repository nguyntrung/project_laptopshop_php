<!DOCTYPE html>
<html>
   <head>
      <title>Sản phẩm</title>
      <meta charset="utf-8">
      <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
      <script src="js/jquery-3.2.1.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <!---->
      <link rel="stylesheet" type="text/css" href="css/slick.css"/>
      <link rel="stylesheet" type="text/css" href="css/slick-theme.css"/>
      <!--slide-->
      <link rel="stylesheet" type="text/css" href="css/style.css">
      <style>
         .pagination>.active>span, .pagination>.active>span:hover {
         background-color: #333;
         border-color: #333;
         color:#fff;
         }
         .pagination>li>a {
         color:#000;
         }
         .pagination{
         /* padding-top: 100px;
         padding-bottom: 15px; */
         padding-left: 10%;
         }
      </style>
   </head>
   <body>
      <?php
         include("Connection.php");
         session_start();
         ?>
      <div id="wrapper">
         <!-- HEADER -->
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
         <!-- END HEADER -->
         <!-- MENUNAV -->
         <div id="menunav">
            <div class="container">
               <nav>
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
                  <ul class="pull-right" id="main-shopping">
                     <li class="account-menu">
                        <?php
                           if(isset($_SESSION['username'])) {
                               echo '<a href="#"><i class="fa fa-user"></i><span style="color: white; font-weight: bold; margin-left: 8px">' . $_SESSION['tenkhachhang'] . '</span></a>';
                               echo '<div class="account-menu-content">';
                               echo '<p><a href="CustomerInfo.php">Thông Tin Khách Hàng</a></p>';
                               echo '<p><a href="Logout.php">Đăng Xuất</a></p>';
                               echo '</div>';
                           } else {
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
         <!-- ENDMENUNAV -->
         <div id="maincontent">
            <div class="container">
               <!-- Sidebar -->
               <div class="col-md-3">
                  <div class="box-left box-menu">
                     <h3 class="box-title"><i class="fa fa-list"></i>  Danh mục</h3>
                     <ul>
                        <?php
                           $sql = "select * from hang";
                           $hang = $pdo->query($sql); 
                           foreach($hang as $h) 
                           { 
                           ?>
                        <a href="ProductList.php?mh=<?php echo $h["MaHang"];?>">
                           <li class="list-group-item bg-light" style="cursor: pointer; font-weight: bold;"><?php echo $h["TenHang"];?></li>
                        </a>
                        <?php 
                           } 
                           ?>
                     </ul>
                  </div>
               </div>
               <!-- End Sidebar -->
               <!-- Main Content -->
               <div class="col-md-9 bor">
                  <?php
                     if (isset($_GET["mh"])) 
                     {
                         include("CategoryProduct.php");
                     }
                     else if (isset($_POST["txt_search"]))
                     {
                         include("SearchProduct.php");
                     }
                     else 
                     {
                         include("AllProduct.php");
                     }  
                     ?>
                  <div class="col-md-12" style="margin-left: 240px">
                     <?php if (isset($phantrang)) echo $phantrang; ?>
                  </div>
               </div>
               <!-- End Main Content -->
            </div>
         </div>
         <!-- Footer Section -->
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
         <!-- End Footer Section -->
         <!-- Footer Section Button -->
         <section id="footer-button">
            <class="container-pluid">
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
                        <p><i class="fa fa-home" style="font-size: 16px;padding-right: 5px;"></i> Ngách 56/34 số nhà 22 phường đức thắng </p>
                        <p><i class="sp-ic fa fa-mobile" style="font-size: 22px;padding-right: 5px;"></i> 012345678</p>
                        <p><i class="sp-ic fa fa-envelope" style="font-size: 13px;padding-right: 5px;"></i> support@gmail.com</p>
                     </li>
                  </ul>
               </div>
            </div>
      </div>
      </section>
      <!-- End Footer Button Section -->
      <!-- Footer Bottom Section -->
      <section id="ft-bottom">
         <p class="text-center">Copyright © 2024</p>
      </section>
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
       });
   });

   $(function() {
       $(".account-menu").hover(function(){
           $(this).find(".account-menu-content").show();
       }, function(){
           $(this).find(".account-menu-content").hide();
       });
   });
</script>