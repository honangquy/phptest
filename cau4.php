<?php
// Câu 4 - CRUD đơn giản (file: cau4.php)
require_once __DIR__ . '/db.php';

$pdo = getPDO();


$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $hoten = $_POST['hoten'] ?? '';
    $ngaysinh = $_POST['ngaysinh'] ?? null;
    $luong = $_POST['luong'] ?? 0;
    $phongban = $_POST['phongban'] ?? '';

    $hinhanhName = null;
    if (!empty($_FILES['hinhanh']['name'])) {
        $tmp = $_FILES['hinhanh']['tmp_name'];
        $name = basename($_FILES['hinhanh']['name']);
        $target = $uploadDir . '/' . $name;
        $i = 1;
        while (file_exists($target)) {
            $target = $uploadDir . '/' . ($i++ . '_' . $name);
        }
        if (move_uploaded_file($tmp, $target)) {
            $hinhanhName = basename($target);
        }
    }

    $sql = 'INSERT INTO nhanvien (hoten, ngaysinh, luong, phongban, hinhanh) VALUES (:hoten, :ngaysinh, :luong, :phongban, :hinhanh)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':hoten' => $hoten,
        ':ngaysinh' => $ngaysinh ?: null,
        ':luong' => $luong,
        ':phongban' => $phongban,
        ':hinhanh' => $hinhanhName,
    ]);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // optionally delete image file
    $stmt = $pdo->prepare('SELECT hinhanh FROM nhanvien WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
    if ($row && !empty($row['hinhanh'])) {
        $f = $uploadDir . '/' . $row['hinhanh'];
        if (file_exists($f)) @unlink($f);
    }
    $stmt = $pdo->prepare('DELETE FROM nhanvien WHERE id = :id');
    $stmt->execute([':id' => $id]);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)$_POST['id'];
    $hoten = $_POST['hoten'] ?? '';
    $ngaysinh = $_POST['ngaysinh'] ?? null;
    $luong = $_POST['luong'] ?? 0;
    $phongban = $_POST['phongban'] ?? '';
    $hinhanhName = null;
    if (!empty($_FILES['hinhanh']['name'])) {
        $tmp = $_FILES['hinhanh']['tmp_name'];
        $name = basename($_FILES['hinhanh']['name']);
        $target = $uploadDir . '/' . $name;
        $i = 1;
        while (file_exists($target)) {
            $target = $uploadDir . '/' . ($i++ . '_' . $name);
        }
        if (move_uploaded_file($tmp, $target)) {
            $hinhanhName = basename($target);
        }
    }

    if ($hinhanhName) {
        $sql = 'UPDATE nhanvien SET hoten=:hoten, ngaysinh=:ngaysinh, luong=:luong, phongban=:phongban, hinhanh=:hinhanh WHERE id=:id';
        $params = [':hoten'=>$hoten, ':ngaysinh'=>$ngaysinh ?: null, ':luong'=>$luong, ':phongban'=>$phongban, ':hinhanh'=>$hinhanhName, ':id'=>$id];
    } else {
        $sql = 'UPDATE nhanvien SET hoten=:hoten, ngaysinh=:ngaysinh, luong=:luong, phongban=:phongban WHERE id=:id';
        $params = [':hoten'=>$hoten, ':ngaysinh'=>$ngaysinh ?: null, ':luong'=>$luong, ':phongban'=>$phongban, ':id'=>$id];
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$perPage = 5;
$page = max(1, (int)($_GET['page'] ?? 1));
$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'id';
$allowedSort = ['id','hoten','ngaysinh','luong','phongban'];
if (!in_array($sort, $allowedSort)) $sort = 'id';

$where = '';
$params = [];
if ($search !== '') {
    $where = 'WHERE hoten LIKE :s1 OR phongban LIKE :s2';
    $params[':s1'] = "%$search%";
    $params[':s2'] = "%$search%";
}

$countSql = "SELECT COUNT(*) FROM nhanvien $where";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();
$totalPages = (int)ceil($total / $perPage);
$offset = ($page - 1) * $perPage;

$listSql = "SELECT * FROM nhanvien $where ORDER BY $sort LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($listSql);
foreach ($params as $k=>$v) {
    $stmt->bindValue($k, $v, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();

function h($s){ return htmlspecialchars($s); }

?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Câu 4 - CRUD Nhân viên</title>
    <style>table{border-collapse:collapse}td,th{border:1px solid #ccc;padding:6px}form.inline{display:inline}</style>
</head>
<body>
    <h2>Câu 4 - Quản lý nhân viên (CRUD)</h2>

    <h3>Thêm nhân viên</h3>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create">
        Họ tên: <input name="hoten" required> &nbsp;
        Ngày sinh: <input name="ngaysinh" type="date"> &nbsp;
        Lương: <input name="luong" type="number" step="0.01"> &nbsp;
        Phòng ban: <input name="phongban"> &nbsp;
        Hình: <input type="file" name="hinhanh"> &nbsp;
        <button type="submit">Thêm</button>
    </form>

    <h3>Danh sách nhân viên</h3>
    <form method="get" style="margin-bottom:8px">
        Tìm: <input name="search" value="<?php echo h($search); ?>"> 
        Sắp xếp theo: <select name="sort">
            <?php foreach ($allowedSort as $s): ?>
                <option value="<?php echo $s; ?>" <?php if ($sort===$s) echo 'selected'; ?>><?php echo $s; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Áp dụng</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Ngày sinh</th>
                <th>Lương</th>
                <th>Phòng ban</th>
                <th>Hình</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($rows) === 0): ?>
                <tr><td colspan="7">Không có bản ghi</td></tr>
            <?php else: ?>
                <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?php echo $r['id']; ?></td>
                    <td><?php echo h($r['hoten']); ?></td>
                    <td><?php echo h($r['ngaysinh']); ?></td>
                    <td><?php echo number_format($r['luong'],0,',','.'); ?></td>
                    <td><?php echo h($r['phongban']); ?></td>
                    <td><?php if ($r['hinhanh'] && file_exists($uploadDir.'/'.$r['hinhanh'])): ?><img src="uploads/<?php echo rawurlencode($r['hinhanh']); ?>" style="max-height:60px"><?php endif; ?></td>
                    <td>
                        <form method="get" class="inline">
                            <input type="hidden" name="edit" value="<?php echo $r['id']; ?>">
                            <button type="submit">Sửa</button>
                        </form>
                        <form method="get" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                            <input type="hidden" name="delete" value="<?php echo $r['id']; ?>">
                            <button type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <p>Trang: 
    <?php for ($p=1;$p<=$totalPages;$p++): ?>
        <a href="?page=<?php echo $p; ?>&search=<?php echo rawurlencode($search); ?>&sort=<?php echo rawurlencode($sort); ?>"><?php echo $p; ?></a> 
    <?php endfor; ?>
    </p>

    <?php if (isset($_GET['edit'])):
        $eid = (int)$_GET['edit'];
        $stmt = $pdo->prepare('SELECT * FROM nhanvien WHERE id=:id');
        $stmt->execute([':id'=>$eid]);
        $er = $stmt->fetch();
        if ($er):
    ?>
    <h3>Sửa nhân viên (ID <?php echo $er['id']; ?>)</h3>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?php echo $er['id']; ?>">
        Họ tên: <input name="hoten" value="<?php echo h($er['hoten']); ?>"> &nbsp;
        Ngày sinh: <input name="ngaysinh" type="date" value="<?php echo h($er['ngaysinh']); ?>"> &nbsp;
        Lương: <input name="luong" type="number" step="0.01" value="<?php echo h($er['luong']); ?>"> &nbsp;
        Phòng ban: <input name="phongban" value="<?php echo h($er['phongban']); ?>"> &nbsp;
        Hình mới: <input type="file" name="hinhanh"> &nbsp;
        <button type="submit">Cập nhật</button>
    </form>
    <?php endif; endif; ?>

</body>
</html>
