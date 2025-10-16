<?php
// Câu 2 - OOP
class SanPham {
    public $masp;
    public $ten;
    public $gia;
    public $soLuong;

    public function __construct($masp, $ten, $gia, $soLuong) {
        $this->masp = $masp;
        $this->ten = $ten;
        $this->gia = $gia;
        $this->soLuong = $soLuong;
    }

    public function tinhTongTien() {
        return $this->gia * $this->soLuong;
    }
}

$sp1 = new SanPham('SP001', 'Áo thun', 150000, 2);
$sp2 = new SanPham('SP002', 'Quần jean', 350000, 1);

?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Câu 2 - OOP</title>
    <style>table{border-collapse:collapse;}td,th{border:1px solid #ccc;padding:8px}</style>
</head>
<body>
    <h2>Câu 2 - Lập trình hướng đối tượng</h2>
    <table>
        <thead>
            <tr>
                <th>MaSP</th>
                <th>Tên</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ([$sp1, $sp2] as $p): ?>
            <tr>
                <td><?php echo htmlspecialchars($p->masp); ?></td>
                <td><?php echo htmlspecialchars($p->ten); ?></td>
                <td><?php echo number_format($p->gia, 0, ',', '.'); ?> VND</td>
                <td><?php echo $p->soLuong; ?></td>
                <td><?php echo number_format($p->tinhTongTien(), 0, ',', '.'); ?> VND</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
