<?php
ob_start();
include('includes/header.php');
?>
<div>
    <?php
    include("Connection.php");

    $sql = "select * from hang";
    $st = $pdo->prepare($sql);
    $st->execute();

    if($st->rowCount() > 0) {
        $hangsp = $st->fetchAll(PDO::FETCH_OBJ);
    }

    if(isset($_POST["delete"])) {
        $mahang = $_POST["mahang"];
        $sqldel = "DELETE FROM hang WHERE MaHang =  ".$mahang;
        $stdel = $pdo->prepare($sqldel);
        $stdel->execute();

        header("Location: ListBrands.php");
        exit();
    }
    ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách thương hiệu</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên hãng</th>
                            <th>Logo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Tên hãng</th>
                            <th>Logo</th>
                            <th>Thao tác</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach($hangsp as $hsp) { ?>
                            <tr style="line-height: 80px">
                                <td><?php echo $hsp->MaHang?></td>
                                <td><?php echo $hsp->TenHang?></td>
                                <td>
                                    <img src="../images/<?php echo $hsp->Logo?>" alt="<?php echo $hsp->Logo?>" style="height: 50px;">
                                </td>
                                <td>
                                    <a href="UpdateBrands.php?id=<?php echo $hsp->MaHang?>">Chỉnh sửa</a>
                                    <strong>|</strong>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-mahang="<?php echo $hsp->MaHang; ?>">Xóa</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Xác nhận xóa</h5>
                <button class="btnThaoTac" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                Bạn có muốn xóa hãng này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" action="ListBrands.php" method="post">
                    <input type="hidden" name="mahang" id="mahangToDelete">
                    <button type="submit" class="btn btn-danger" name="delete">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>

<script>
   document.addEventListener('DOMContentLoaded', (event) => {
      var deleteModal = document.getElementById('confirmDeleteModal');
      deleteModal.addEventListener('show.bs.modal', function (event) {
         var button = event.relatedTarget;
         var mahang = button.getAttribute('data-mahang');
         var input = deleteModal.querySelector('#mahangToDelete');
         input.value = mahang;
      });
   });
</script>
