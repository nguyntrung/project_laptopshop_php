<?php
ob_start();
include('includes/header.php');
?>
<div>
   <?php
      include("Connection.php");

      $hang = null;
      if(isset($_GET['id'])) {
         $mahang = $_GET['id'];
         $sql = 'SELECT * FROM hang WHERE MaHang = :maHang';
         $st = $pdo->prepare($sql);
         $st->execute([':maHang' => $mahang]);
         $hang = $st->fetch(PDO::FETCH_OBJ);
      }

      if($_SERVER['REQUEST_METHOD'] === 'POST') {
         $tenHang = $_POST['tenHang'];
         $logo = $_FILES['logo']['name'];

         // Upload file
         $target_dir = "../images/";
         $target_file = $target_dir . basename($_FILES["logo"]["name"]);
         move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);

         // Update hang
         $sqlUpdate = "UPDATE hang SET TenHang = :tenHang, Logo = :logo WHERE MaHang = :maHang";
         $stmt = $pdo->prepare($sqlUpdate);
         $stmt->execute([':tenHang' => $tenHang, ':logo' => $logo, ':maHang' => $mahang]);

         header("Location: ListBrands.php");
         exit();
      }
   ?>
   <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
         <div class="row">
            <div class="col-lg-12">
               <div class="p-5">
                  <div class="text-center">
                     <h1 class="h4 text-gray-900 mb-4">CHỈNH SỬA HÃNG</h1>
                  </div>
                  <form class="product" method="POST" enctype="multipart/form-data">
                     <div class="form-group">
                        <input type="text" class="form-control form-control-product" name="tenHang" value="<?php echo $hang->TenHang ?? '' ?>" placeholder="Tên Thương Hiệu" required>
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
ob_end_flush();
?>
