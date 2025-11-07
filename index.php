<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>星芒下载站</title>
    <link rel="stylesheet" href="style.css">
    <meta name="keywords" content="星芒下载站">
    <link rel="shortcut icon" href="https://vip.123pan.cn/1832150722/yk6baz03t0n000d7w33gzr20dllunnpiDIYwDqeyDdUvDpxPAdDxDF==.png" type="image/x-icon" /> 
</head>
<body>
    <div class="container">
        <h1>星芒下载站</h1>
        <div class="breadcrumb">
            <a href="index.php" class="home-link">🏠 首页</a>
        </div>
        <div id="file-list">
            <?php
            // 获取当前路径参数
            $currentPath = isset($_GET['path']) ? $_GET['path'] : '';
            
            function listFiles($dir, $relativePath = '') {
                $items = scandir($dir);
                foreach ($items as $item) {
                    if ($item === '.' || $item === '..') {
                        continue;
                    }
                    
                    $fullPath = $dir . '/' . $item;
                    $displayPath = $relativePath ? $relativePath . '/' . $item : $item;
                    $isDir = is_dir($fullPath);
                    
                    echo '<div class="file-item">';
                    if ($isDir) {
                        echo '<a href="index.php?path=' . urlencode($displayPath) . '" class="folder-link">';
                        echo '<span class="folder-icon">📁</span>';
                        echo '<span class="folder-name">' . htmlspecialchars($item) . '</span>';
                        echo '</a>';
                    } else {
                        // 获取文件扩展名
                        $fileExtension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                        $imageExtensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp');
                        
                        if (in_array($fileExtension, $imageExtensions)) {
                            // 如果是图片文件，使用预览图标和预览链接
                            echo '<span class="file-icon">🖼️</span>';
                            echo '<a href="preview.php?file=' . urlencode($displayPath) . '" class="file-link">' . htmlspecialchars($item) . '</a>';
                        } else {
                            // 非图片文件，使用默认图标和下载链接
                            echo '<span class="file-icon">📄</span>';
                            echo '<a href="download.php?file=' . urlencode($displayPath) . '" class="file-link">' . htmlspecialchars($item) . '</a>';
                        }
                        echo '<span class="file-size">' . formatFileSize(filesize($fullPath)) . '</span>';
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
            
            function generateBreadcrumb($path) {
                $breadcrumb = '<a href="index.php" class="home-link">🏠 首页</a>';
                
                if (!empty($path)) {
                    $parts = explode('/', $path);
                    $currentPath = '';
                    
                    foreach ($parts as $i => $part) {
                        $currentPath .= ($i > 0 ? '/' : '') . $part;
                        $breadcrumb .= ' / <a href="index.php?path=' . urlencode($currentPath) . '">' . htmlspecialchars($part) . '</a>';
                    }
                }
                
                return $breadcrumb;
            }
            
            // 更新面包屑导航
            echo '<div class="breadcrumb">' . generateBreadcrumb($currentPath) . '</div>';
            
            // 显示当前目录下的内容
            $targetDir = 'Data' . (!empty($currentPath) ? '/' . $currentPath : '');
            
            if (is_dir($targetDir)) {
                // 如果不是根目录，显示返回上级目录链接
                if (!empty($currentPath)) {
                    $parentPath = dirname($currentPath);
                    if ($parentPath === '.') {
                        $parentPath = '';
                    }
                    echo '<div class="file-item">';
                    echo '<a href="index.php?path=' . urlencode($parentPath) . '" class="back-link">';
                    echo '<span class="back-icon">⬆️</span>';
                    echo '<span>返回上级目录</span>';
                    echo '</a>';
                    echo '</div>';
                }
                
                listFiles($targetDir, $currentPath);
            } else {
                echo '<p>目录不存在</p>';
            }
            ?>
        </div>
    </div>
    <script src="script.js"></script>
    <footer class="footer">
        <div class="footer-info">
            <span>友情链接：</span>
            <a href="https://www.starlumina.com/" target="_blank">星芒起始页</a>
            <span>|</span>
            <a href="https://tool.starlumina.com/" target="_blank">星芒工具箱</a>
            <span>|</span>
            <a href="https://blog.starlumina.com/" target="_blank">星芒博客</a>
            <span>|</span>
            <a href="https://app.starlumina.com/" target="_blank">星芒集盒</a>
        </div>
        <div class="footer-info">
            <a href="https://beian.miit.gov.cn/" target="_blank">蜀ICP备2024095899号-3</a>
            <img class="logos" src="https://ico.starlumina.com/备案图标.png" width="15" height="15">
            <a href="https://beian.mps.gov.cn/#/query/webSearch?code=51019002007728" target="_blank">川公网安备51019002007728号</a>
        </div>
        <div class="copyright">© <span id="current-year"></span> 星芒工具箱 版权所有</div>
    </footer>
</body>
</html>

