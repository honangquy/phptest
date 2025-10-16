<?php
// Câu 3 - MySQL + PDO
require_once __DIR__ . '/db.php';

$pdo = getPDO();

try {
    $stmt = $pdo->query('SELECT * FROM nhanvien');
    $rows = $stmt->fetchAll();
} catch (Exception $e) {
    die('Lỗi truy vấn: ' . $e->getMessage());
}

?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Câu 3 - MySQL + PDO</title>
    <style>table{border-collapse:collapse}td,th{border:1px solid #ccc;padding:6px}</style>
</head>
<body>
    <h2>Câu 3 - Danh sách nhân viên</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Ngày sinh</th>
                <th>Lương</th>
                <th>Phòng ban</th>
                <th>Hình ảnh</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($rows) === 0): ?>
            <tr><td colspan="6">Không có bản ghi.</td></tr>
        <?php else: ?>
            <?php foreach ($rows as $r): ?>
            <tr>
                <td><?php echo $r['id']; ?></td>
                <td><?php echo htmlspecialchars($r['hoten']); ?></td>
                <td><?php echo $r['ngaysinh']; ?></td>
                <td><?php echo number_format($r['luong'], 0, ',', '.'); ?></td>
                <td><?php echo htmlspecialchars($r['phongban']); ?></td>
                <td>
                    <?php if (!empty($r['hinhanh']) && file_exists(__DIR__ . '/uploads/' . $r['hinhanh'])): ?>
                        <img src="uploads/<?php echo rawurlencode($r['hinhanh']); ?>" alt="" style="max-height:80px">
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
