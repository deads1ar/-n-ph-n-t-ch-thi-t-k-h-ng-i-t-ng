<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idlsp = $_POST['idlsp'];
    $ten = $_POST['ten'];
    $mota = $_POST['mota'];
    $giaban = $_POST['giaban'];
    $giabankm = $_POST['giabankm'];
    $image_source = $_POST['image_source'];

    // Generate new IDSP
    $stmt = $pdo->query("SELECT MAX(IDSP) as max_id FROM sp");
    $max_id = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'];
    $new_id_num = $max_id ? (int)substr($max_id, 2) + 1 : 1;
    $idsp = "SP" . sprintf("%03d", $new_id_num);

    // Validate category
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM loaisp WHERE IDLSP = ?");
    $stmt->execute([$idlsp]);
    if ($stmt->fetchColumn() == 0) {
        die("Invalid category ID.");
    }

    // Handle image
    $image_url = '';
    if ($image_source === 'file' && !empty($_FILES["image"]["name"])) {
        $target_dir = "img/shop/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            die("Error: Failed to upload image.");
        }
        $image_url = $target_file;
    } elseif ($image_source === 'url' && !empty($_POST['image_url'])) {
        $image_url = $_POST['image_url'];
    } else {
        die("Error: No image provided.");
    }

    // Insert new product
    $stmt = $pdo->prepare("INSERT INTO sp (IDSP, TEN, MOTA, GIABAN, GIABANKM, URL, IDLSP) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$idsp, $ten, $mota, $giaban, $giabankm, $image_url, $idlsp]);

    header("Location: Qlsp.php");
    exit;
}

// Fetch categories
$result = $pdo->query("SELECT * FROM loaisp");
$categories = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
<section class="shop spad">
    <div class="container">
        <h2>Thêm sản phẩm</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Phân loại</label>
                <select name="idlsp" class="form-control" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['IDLSP']; ?>"><?php echo $category['TENLOAI']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tên sản phẩm</label>
                <input type="text" name="ten" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Mô tả</label>
                <textarea name="mota" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>Giá bán</label>
                <input type="number" name="giaban" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Giá bán khuyến mãi</label>
                <input type="number" name="giabankm" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Hình ảnh</label>
                <div>
                    <input type="radio" name="image_source" value="file" checked onchange="toggleImageInput()"> Tải file
                    <input type="radio" name="image_source" value="url" onchange="toggleImageInput()"> Nhập URL
                </div>
                <div id="file-input" style="display: block;">
                    <input type="file" name="image" id="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                </div>
                <div id="url-input" style="display: none;">
                    <input type="url" name="image_url" id="image_url" class="form-control" placeholder="Nhập URL ảnh">
                </div>
                <img id="image-preview" style="max-width: 200px; margin-top: 10px; display: none;" />
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="Qlsp.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</section>

<script src="js/jquery-3.3.1.min.js"></script>
<script>
    function toggleImageInput() {
        const fileInput = document.getElementById('file-input');
        const urlInput = document.getElementById('url-input');
        const imagePreview = document.getElementById('image-preview');
        if (document.querySelector('input[name="image_source"]:checked').value === 'file') {
            fileInput.style.display = 'block';
            urlInput.style.display = 'none';
            imagePreview.src = '';
            imagePreview.style.display = 'none';
        } else {
            fileInput.style.display = 'none';
            urlInput.style.display = 'block';
            const url = document.getElementById('image_url').value;
            if (url) {
                imagePreview.src = url;
                imagePreview.style.display = 'block';
            }
        }
    }

    function previewImage(event) {
        const imagePreview = document.getElementById('image-preview');
        const reader = new FileReader();
        reader.onload = function() {
            imagePreview.src = reader.result;
            imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    document.getElementById('image_url').addEventListener('input', function() {
        const imagePreview = document.getElementById('image-preview');
        imagePreview.src = this.value;
        imagePreview.style.display = this.value ? 'block' : 'none';
    });
</script>
</body>
</html>
