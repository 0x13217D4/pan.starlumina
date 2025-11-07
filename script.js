document.addEventListener('DOMContentLoaded', function() {
    // 页面加载完成后的初始化逻辑
    initializeAnimations();
    setupInteractiveElements();
    setupFileInteractions();
    updateCurrentYear();
});

// 初始化动画效果
function initializeAnimations() {
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

    // 文件列表项渐入动画
    const fileItems = document.querySelectorAll('.file-item');
    fileItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.4s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, 200 + (index * 50));
    });

    // 页脚渐入动画
    const footer = document.querySelector('.footer');
    if (footer) {
        footer.style.opacity = '0';
        footer.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            footer.style.transition = 'all 0.8s ease';
            footer.style.opacity = '1';
            footer.style.transform = 'translateY(0)';
        }, 500);
    }
}

// 设置交互元素
function setupInteractiveElements() {
    // 为所有链接添加点击波纹效果
    const links = document.querySelectorAll('a');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            createRipple(e, this);
        });
    });

    // 为返回链接添加加载状态
    const backLink = document.querySelector('.back-link');
    if (backLink) {
        backLink.addEventListener('click', function() {
            const fileList = document.getElementById('file-list');
            if (fileList) {
                fileList.style.transition = 'opacity 0.3s ease';
                fileList.style.opacity = '0.5';
            }
        });
    }

    // 面包屑导航悬停效果
    const breadcrumbLinks = document.querySelectorAll('.breadcrumb a');
    breadcrumbLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

// 设置文件交互
function setupFileInteractions() {
    // 文件项悬停效果增强
    const fileItems = document.querySelectorAll('.file-item');
    fileItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.01)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // 文件链接点击效果
    const fileLinks = document.querySelectorAll('.file-link');
    fileLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // 添加点击反馈
            this.style.transform = 'translateX(3px) scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'translateX(0) scale(1)';
            }, 150);
        });
    });
}

// 创建波纹效果
function createRipple(event, element) {
    const ripple = document.createElement('span');
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;

    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    ripple.classList.add('ripple');

    // 添加波纹样式
    ripple.style.position = 'absolute';
    ripple.style.borderRadius = '50%';
    ripple.style.background = 'rgba(255, 255, 255, 0.6)';
    ripple.style.transform = 'scale(0)';
    ripple.style.animation = 'ripple-animation 0.6s linear';
    ripple.style.pointerEvents = 'none';

    element.style.position = 'relative';
    element.style.overflow = 'hidden';
    element.appendChild(ripple);

    setTimeout(() => {
        ripple.remove();
    }, 600);
}

// 更新当前年份
function updateCurrentYear() {
    const currentYearElement = document.getElementById('current-year');
    if (currentYearElement) {
        currentYearElement.textContent = new Date().getFullYear();
    }
}

// 添加页面滚动效果
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const parallax = document.querySelector('body');
    if (parallax) {
        parallax.style.backgroundPosition = `center ${scrolled * 0.5}px`;
    }
});

// 添加键盘导航支持
document.addEventListener('keydown', function(e) {
    // ESC键返回首页
    if (e.key === 'Escape') {
        const homeLink = document.querySelector('.home-link');
        if (homeLink) {
            window.location.href = homeLink.href;
        }
    }
    
    // 方向键导航
    const fileItems = Array.from(document.querySelectorAll('.file-item'));
    const currentFocus = document.activeElement;
    const currentIndex = fileItems.findIndex(item => item.contains(currentFocus));
    
    if (e.key === 'ArrowDown' && currentIndex < fileItems.length - 1) {
        e.preventDefault();
        const nextLink = fileItems[currentIndex + 1].querySelector('a');
        if (nextLink) nextLink.focus();
    } else if (e.key === 'ArrowUp' && currentIndex > 0) {
        e.preventDefault();
        const prevLink = fileItems[currentIndex - 1].querySelector('a');
        if (prevLink) prevLink.focus();
    }
});