<?php
include("Connection.php");

if(isset($_POST['ma_hoa_don'])) {
    $ma_hoa_don = $_POST['ma_hoa_don'];

    $sql = "UPDATE hoadon SET TrangThai = 'Đã xác nhận' WHERE MaHoaDon = :ma_hoa_don";
    $st = $pdo->prepare($sql);
    $st->bindParam(':ma_hoa_don', $ma_hoa_don);
    $st->execute();

    echo "success";
   
} else {
    echo "error";
}
?>
