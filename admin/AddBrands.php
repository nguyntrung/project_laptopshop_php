<?php
   ob_start();
   include('includes/header.php');
   ?>
<div>
   <?php
      include("Connection.php");
      
      if($_SERVER['REQUEST_METHOD'] === 'POST') {
         $tenHang = $_POST['tenHang'];
         $logo = $_FILES['logo']['name'];

         $target_dir = "../images/";
         $target_file = $target_dir . basename($_FILES["logo"]["name"]);
         move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);

         $sqlInsert = "INSERT INTO hang (TenHang, Logo) VALUES (:tenHang, :logo)";
         $stmt = $pdo->prepare($sqlInsert);
         $stmt->execute([':tenHang' => $tenHang, ':logo' => $logo]); 

         header("Location: ListBrands.php");
         exit();
         ?>

         <div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 400px; margin: 0 auto">
            Thêm thương hiệu thành công
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="background:none; border: none; float: right"><i class="fa-solid fa-xmark"></i></button>
         </div>
         
<?php
      }
      ?>
   <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
         <div class="row">
            <div class="col-lg-12">
               <div class="p-5">
                  <div class="text-center">
                     <h1 class="h4 text-gray-900 mb-4">THÊM THƯƠNG HIỆU MỚI</h1>
                  </div>
                  <form class="product" method="POST" enctype="multipart/form-data">
                  <div class="form-group">
                     <input type="text" class="form-control form-control-product" name="tenHang" placeholder="Tên Thương Hiệu" required>
                  </div>
                  <div class="form-group">
                     <input type="file" class="form-control form-control-product" name="logo" placeholder="Logo" style="padding: 10px; height: 50px" required>
                  </div>
                  <button type="submit" class="btn btn-primary btn-user btn-block">Hoàn Thành</button>
               </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php
   include('includes/footer.php');
   ?>