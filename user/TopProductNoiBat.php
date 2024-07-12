<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
        include("Connection.php");

        $sql = "SELECT * FROM sanpham ORDER BY GiaBan DESC LIMIT 8";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $sps = $stmt->fetchAll(PDO::FETCH_OBJ);

        function getSoLuongDaBanProduct($pdo, $masp) 
        {
            $sqldaban = "SELECT SUM(chitiethoadon.soluong) AS SoLuongDaBan " .
                        "FROM sanpham INNER JOIN chitiethoadon ON sanpham.MaSanPham = chitiethoadon.masanpham " .
                        "WHERE sanpham.MaSanPham = :masp";
            $stmt = $pdo->prepare($sqldaban);
            $stmt->execute(['masp' => $masp]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['SoLuongDaBan'] ?? 0;
        }
    ?>
</head>
<body>
    <section class="box-main1">
        <h3 class="title-main" style="text-align: center;"><a href="javascript:void(0)"> Sản phẩm nổi bật </a> </h3>
        <?php
            if (!empty($sps))
            {
                foreach($sps as $sp)
                {
        ?>
                    <div class="showitem">
                        <div class="col-md-3 item-product bor" style="height: 300px; margin: 5px; width: 23.8%">
                            <a href="ShowDetail.php?id=<?php echo $sp->MaSanPham ?>">
                                <img src="../images/<?php echo htmlspecialchars($sp->HinhAnh) ?>" class="" width="100%" height="180">
                            
                                <div class="info-item">
                                    <p><?php echo htmlspecialchars($sp->TenSanPham) ?></p> <br>
                                    <b class="price"><?php echo number_format($sp->GiaBan, 0, ',', '.') ?>đ</b><br>
                                    <span style="margin-top: 50px;">Đã bán <?php echo getSoLuongDaBanProduct($pdo, $sp->MaSanPham); ?></span>
                                </div>
                            </a>
                        </div>
                    </div>
        <?php
                }
            }
        ?>
    </section>
</body>
</html>