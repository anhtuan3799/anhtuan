<?php
header('Content-Type: application/json');

$filePath = __DIR__ . '/key.txt';

if (!file_exists($filePath)) {
    echo json_encode(['status' => 'error', 'message' => 'File key.txt không tồn tại']);
    exit;
}

// Lấy key từ POST hoặc GET
$key = '';
if (isset($_POST['key'])) {
    $key = trim($_POST['key']);
} elseif (isset($_GET['key'])) {
    $key = trim($_GET['key']);
}

if (empty($key)) {
    echo json_encode(['status' => 'error', 'message' => 'Không có key truyền vào']);
    exit;
}

// Đọc toàn bộ nội dung file thành chuỗi
$content = file_get_contents($filePath);
if ($content === false) {
    echo json_encode(['status' => 'error', 'message' => 'Không thể đọc file key.txt']);
    exit;
}

// Tách chuỗi thành mảng keys, loại bỏ khoảng trắng thừa
$keys = array_map('trim', explode(',', $content));

// Kiểm tra key có tồn tại không
if (!in_array($key, $keys)) {
    echo json_encode(['status' => 'error', 'message' => 'Key không tồn tại trong file']);
    exit;
}

// Lọc bỏ key ra khỏi mảng
$filteredKeys = array_filter($keys, function($k) use ($key) {
    return $k !== $key;
});

// Nối lại mảng thành chuỗi, cách nhau dấu phẩy
$newContent = implode(',', $filteredKeys);

// Ghi lại file
$result = file_put_contents($filePath, $newContent);

if ($result === false) {
    echo json_encode(['status' => 'error', 'message' => 'Không thể ghi file key.txt']);
    exit;
}

echo json_encode(['status' => 'ok', 'message' => 'Xóa key thành công']);
exit;
?>
