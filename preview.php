<?php
// 获取要预览的图片路径
$imagePath = isset($_GET['file']) ? $_GET['file'] : '';

// 安全检查：确保文件路径不包含目录遍历攻击
if (strpos($imagePath, '..') !== false || strpos($imagePath, "\0") !== false) {
    die('非法文件路径');
}

// 构建完整的文件路径
$fullPath = 'Data/' . $imagePath;

// 检查文件是否存在
if (!file_exists($fullPath)) {
    die('文件不存在');
}

// 检查是否是文件而不是目录
if (is_dir($fullPath)) {
    die('不能预览目录');
}

// 获取文件信息
$fileSize = filesize($fullPath);
$fileName = basename($fullPath);

// 获取文件扩展名
$fileExtension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

// 检查是否是图片文件
$imageExtensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp');
if (!in_array($fileExtension, $imageExtensions)) {
    die('不是支持的图片格式');
}

// 获取上级目录路径
$parentPath = dirname($imagePath);
if ($parentPath === '.') {
    $parentPath = '';
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>预览: <?php echo htmlspecialchars($fileName); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* 预览页面特定样式 */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .header h2 {
            color: #2d3748;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            margin: 0;
            flex-grow: 1;
        }
        
        .download-btn {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }
        
        .download-btn:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(72, 187, 120, 0.4);
        }
        
        .download-icon {
            margin-right: 10px;
            font-size: 18px;
        }
        
        .preview-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 30px;
        }
        
        .preview-image {
            max-width: 100%;
            max-height: 80vh;
            border: 2px solid rgba(226, 232, 240, 0.5);
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }
        
        .preview-image:hover {
            transform: scale(1.02);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
        }
        
        .file-info {
            margin-top: 25px;
            text-align: left;
            background: rgba(247, 250, 252, 0.8);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(226, 232, 240, 0.5);
        }
        
        .file-info h3 {
            margin-bottom: 15px;
            color: #2d3748;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .file-info p {
            margin-bottom: 10px;
            color: #4a5568;
            font-size: 0.95rem;
        }
        
        .file-info strong {
            color: #2d3748;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                padding: 15px;
            }
            
            .header h2 {
                font-size: 1.25rem;
                order: -1;
            }
            
            .preview-container {
                padding: 20px;
            }
            
            .file-info {
                padding: 15px;
            }
            
            .file-info h3 {
                font-size: 1.1rem;
            }
            
            .file-info p {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 480px) {
            .header {
                padding: 12px;
            }
            
            .header h2 {
                font-size: 1.1rem;
            }
            
            .download-btn {
                padding: 10px 16px;
                font-size: 0.9rem;
            }
            
            .preview-container {
                padding: 15px;
            }
            
            .file-info {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <link rel="shortcut icon" href="https://vip.123pan.cn/1832150722/yk6baz03t0n000d7w33gzr20dllunnpiDIYwDqeyDdUvDpxPAdDxDF==.png" type="image/x-icon" /> 
    <div class="container">
        <div class="header">
            <a href="index.php?path=<?php echo urlencode($parentPath); ?>" class="back-link">
                <span class="back-icon">⬅️</span>
                <span>返回文件夹</span>
            </a>
            <h2><?php echo htmlspecialchars($fileName); ?></h2>
            <a href="download.php?file=<?php echo urlencode($imagePath); ?>" class="download-btn">
                <span class="download-icon">⬇️</span>
                <span>下载</span>
            </a>
        </div>
        
        <div class="preview-container">
            <img src="Data/<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($fileName); ?>" class="preview-image">
            
            <div class="file-info">
                <h3>文件信息</h3>
                <p><strong>文件名:</strong> <?php echo htmlspecialchars($fileName); ?></p>
                <p><strong>文件大小:</strong> <?php echo formatFileSize($fileSize); ?></p>
                <p><strong>文件类型:</strong> <?php echo htmlspecialchars(strtoupper($fileExtension)); ?> 图片</p>
                <p><strong>文件路径:</strong> Data/<?php echo htmlspecialchars($imagePath); ?></p>
            </div>
        </div>
    </div>
    
    <?php
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
    ?>
    <script src="js/script.js"></script>
    <script>
        // 预览页面特定的交互效果
        document.addEventListener('DOMContentLoaded', function() {
            // 初始化动画
            initializePreviewAnimations();
            setupPreviewInteractions();
        });

        // 初始化预览页面动画
        function initializePreviewAnimations() {
            // 页面加载动画
            const container = document.querySelector('.container');
            if (container) {
                container.style.opacity = '0';
                container.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    container.style.transition = 'all 0.6s ease';
                    container.style.opacity = '1';
                    container.style.transform = 'translateY(0)';
                }, 100);
            }

            // 头部动画
            const header = document.querySelector('.header');
            if (header) {
                header.style.opacity = '0';
                header.style.transform = 'translateY(-20px)';
                
                setTimeout(() => {
                    header.style.transition = 'all 0.5s ease';
                    header.style.opacity = '1';
                    header.style.transform = 'translateY(0)';
                }, 200);
            }

            // 预览容器动画
            const previewContainer = document.querySelector('.preview-container');
            if (previewContainer) {
                previewContainer.style.opacity = '0';
                previewContainer.style.transform = 'scale(0.95)';
                
                setTimeout(() => {
                    previewContainer.style.transition = 'all 0.7s ease';
                    previewContainer.style.opacity = '1';
                    previewContainer.style.transform = 'scale(1)';
                }, 400);
            }

            // 图片加载动画
            const previewImage = document.querySelector('.preview-image');
            if (previewImage) {
                previewImage.addEventListener('load', function() {
                    this.style.opacity = '0';
                    this.style.transform = 'scale(0.9)';
                    
                    setTimeout(() => {
                        this.style.transition = 'all 0.8s ease';
                        this.style.opacity = '1';
                        this.style.transform = 'scale(1)';
                    }, 100);
                });
            }

            // 文件信息动画
            const fileInfo = document.querySelector('.file-info');
            if (fileInfo) {
                fileInfo.style.opacity = '0';
                fileInfo.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    fileInfo.style.transition = 'all 0.6s ease';
                    fileInfo.style.opacity = '1';
                    fileInfo.style.transform = 'translateY(0)';
                }, 600);
            }
        }

        // 设置预览页面交互
        function setupPreviewInteractions() {
            // 为所有链接添加波纹效果
            const links = document.querySelectorAll('a');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    createRipple(e, this);
                });
            });

            // 图片点击放大效果
            const previewImage = document.querySelector('.preview-image');
            if (previewImage) {
                previewImage.addEventListener('click', function() {
                    this.style.transition = 'all 0.3s ease';
                    this.style.transform = 'scale(1.05)';
                    
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 300);
                });
            }

            // 下载按钮悬停效果增强
            const downloadBtn = document.querySelector('.download-btn');
            if (downloadBtn) {
                downloadBtn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px) scale(1.05)';
                });
                
                downloadBtn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            }

            // 返回按钮悬停效果增强
            const backLink = document.querySelector('.back-link');
            if (backLink) {
                backLink.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                backLink.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            }

            // 键盘导航支持
            document.addEventListener('keydown', function(e) {
                // ESC键返回
                if (e.key === 'Escape') {
                    const backLink = document.querySelector('.back-link');
                    if (backLink) {
                        window.location.href = backLink.href;
                    }
                }
                
                // Enter键下载
                if (e.key === 'Enter' && e.ctrlKey) {
                    const downloadBtn = document.querySelector('.download-btn');
                    if (downloadBtn) {
                        window.location.href = downloadBtn.href;
                    }
                }
            });
        }
    </script>
</body>
</html>