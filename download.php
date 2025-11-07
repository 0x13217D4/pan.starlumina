<?php
// 获取要下载的文件路径
$filePath = isset($_GET['file']) ? $_GET['file'] : '';

// 安全检查：确保文件路径不包含目录遍历攻击
if (strpos($filePath, '..') !== false || strpos($filePath, "\0") !== false) {
    die('非法文件路径');
}

// 构建完整的文件路径
$fullPath = 'Data/' . $filePath;

// 检查文件是否存在
if (!file_exists($fullPath)) {
    die('文件不存在');
}

// 检查是否是文件而不是目录
if (is_dir($fullPath)) {
    die('不能下载目录');
}

// 获取文件信息
$fileSize = filesize($fullPath);
$fileName = basename($fullPath);

// 设置HTTP头信息
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . $fileSize);

// 清除输出缓冲
ob_clean();
flush();

// 输出文件内容
readfile($fullPath);
exit;
?>