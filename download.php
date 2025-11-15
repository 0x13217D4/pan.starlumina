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

// 检查是否支持Range请求
$range = isset($_SERVER['HTTP_RANGE']) ? $_SERVER['HTTP_RANGE'] : null;

if ($range) {
    // 解析Range头
    if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
        $start = intval($matches[1]);
        $end = $matches[2] !== '' ? intval($matches[2]) : $fileSize - 1;
        
        // 验证范围
        if ($start >= $fileSize || $end >= $fileSize || $start > $end) {
            header('HTTP/1.1 416 Requested Range Not Satisfiable');
            header('Content-Range: bytes */' . $fileSize);
            exit;
        }
        
        $contentLength = $end - $start + 1;
        
        // 设置部分内容的HTTP头
        header('HTTP/1.1 206 Partial Content');
        header('Content-Range: bytes ' . $start . '-' . $end . '/' . $fileSize);
        header('Content-Length: ' . $contentLength);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Accept-Ranges: bytes');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        // 清除输出缓冲
        ob_clean();
        flush();
        
        // 打开文件并定位到指定位置
        $file = fopen($fullPath, 'rb');
        if ($file) {
            fseek($file, $start);
            
            // 分块读取和输出
            $bufferSize = 8192; // 8KB缓冲区
            $bytesSent = 0;
            
            while ($bytesSent < $contentLength && !feof($file)) {
                $chunkSize = min($bufferSize, $contentLength - $bytesSent);
                $chunk = fread($file, $chunkSize);
                
                if ($chunk !== false) {
                    echo $chunk;
                    $bytesSent += strlen($chunk);
                }
                
                // 刷新输出缓冲
                if ($bytesSent % ($bufferSize * 4) === 0) {
                    flush();
                }
            }
            
            fclose($file);
        }
        exit;
    } else {
        // 无效的Range格式
        header('HTTP/1.1 400 Bad Request');
        exit;
    }
} else {
    // 普通下载（不支持Range请求）
    header('HTTP/1.1 200 OK');
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Content-Length: ' . $fileSize);
    header('Accept-Ranges: bytes');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    
    // 清除输出缓冲
    ob_clean();
    flush();
    
    // 分块读取和输出
    $bufferSize = 8192; // 8KB缓冲区
    $bytesSent = 0;
    
    $file = fopen($fullPath, 'rb');
    if ($file) {
        while (!feof($file)) {
            $chunk = fread($file, $bufferSize);
            
            if ($chunk !== false) {
                echo $chunk;
                $bytesSent += strlen($chunk);
            }
            
            // 刷新输出缓冲
            flush();
        }
        
        fclose($file);
    }
    exit;
}
?>