<?php
// 获取要浏览的文件夹路径
$folderPath = isset($_GET['path']) ? $_GET['path'] : '';

// 安全检查：确保路径不包含目录遍历攻击
if (strpos($folderPath, '..') !== false || strpos($folderPath, "\0") !== false) {
    die('非法文件夹路径');
}

// 构建完整的文件夹路径
$fullPath = 'Data/' . $folderPath;

// 检查文件夹是否存在
if (!is_dir($fullPath)) {
    die('文件夹不存在');
}

function listFiles($dir, $relativePath) {
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $fullItemPath = $dir . '/' . $item;
        $displayPath = $relativePath ? $relativePath . '/' . $item : $item;
        $isDir = is_dir($fullItemPath);
        
        echo '<div class="file-item">';
        if ($isDir) {
            echo '<span class="folder-toggle" data-path="' . htmlspecialchars($displayPath) . '">📁</span>';
            echo '<span class="folder-name">' . htmlspecialchars($item) . '</span>';
            echo '<div class="folder-content" style="display: none;"></div>';
        } else {
            echo '<span class="file-icon">📄</span>';
            echo '<a href="download.php?file=' . urlencode($displayPath) . '" class="file-link">' . htmlspecialchars($item) . '</a>';
            echo '<span class="file-size">' . formatFileSize(filesize($fullItemPath)) . '</span>';
        }
        echo '</div>';
    }
}

function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        return $bytes . ' bytes';
    } elseif ($bytes == 1) {
        return '1 byte';
    } else {
        return '0 bytes';
    }
}

// 显示文件夹内容
listFiles($fullPath, $folderPath);
?>