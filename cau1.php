<?php
// Câu 1 - PHP cơ bản (cho phép nhập 2 số từ bàn phím)

function kiemTraChanLe($n) {
    return ($n % 2 === 0) ? 'Chẵn' : 'Lẻ';
}

$errors = [];
$Sa = null;
$Sb = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy và validate input
    $Sa = isset($_POST['Sa']) ? trim($_POST['Sa']) : null;
    $Sb = isset($_POST['Sb']) ? trim($_POST['Sb']) : null;

    if ($Sa === null || $Sa === '') {
        $errors[] = 'Vui lòng nhập Sa.';
    } elseif (!is_numeric($Sa) || intval($Sa) != $Sa) {
        $errors[] = 'Sa phải là số nguyên.';
    } else {
        $Sa = (int)$Sa;
    }

    if ($Sb === null || $Sb === '') {
        $errors[] = 'Vui lòng nhập Sb.';
    } elseif (!is_numeric($Sb) || intval($Sb) != $Sb) {
        $errors[] = 'Sb phải là số nguyên.';
    } else {
        $Sb = (int)$Sb;
    }

    if (empty($errors)) {
        $tong = $Sa + $Sb;
        $hieu = $Sa - $Sb;
        $tich = $Sa * $Sb;
        $thuong = $Sb !== 0 ? $Sa / $Sb : 'Không xác định (chia cho 0)';
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Câu 1 - PHP cơ bản</title>
</head>
<body>
    <h2>Câu 1 - PHP cơ bản (Nhập 2 số)</h2>

    <?php if (!empty($errors)): ?>
        <div style="color:red"><ul>
        <?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?>
        </ul></div>
    <?php endif; ?>

    <form method="post">
        Sa: <input type="number" name="Sa" value="<?php echo isset($Sa) ? htmlspecialchars($Sa) : ''; ?>" required> &nbsp;
        Sb: <input type="number" name="Sb" value="<?php echo isset($Sb) ? htmlspecialchars($Sb) : ''; ?>" required> &nbsp;
        <button type="submit">Tính</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)): ?>
        <h3>Kết quả</h3>
        <ul>
            <li>Sa = <?php echo $Sa; ?>, Sb = <?php echo $Sb; ?></li>
            <li>Tổng: <?php echo $tong; ?></li>
            <li>Hiệu: <?php echo $hieu; ?></li>
            <li>Tích: <?php echo $tich; ?></li>
            <li>Thương: <?php echo $thuong; ?></li>
        </ul>

        <p>Kiểm tra chẵn lẻ:</p>
        <ul>
            <li>Sa: <?php echo kiemTraChanLe($Sa); ?></li>
            <li>Sb: <?php echo kiemTraChanLe($Sb); ?></li>
        </ul>
    <?php endif; ?>
</body>
</html>
