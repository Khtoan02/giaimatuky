/**
 * Notebook Security & Navigation Scripts
 */
(function() {
    'use strict';

    const alertBox = document.getElementById('notebook-security-alert');
    const mainView = document.getElementById('notebook-main-view');
    const sidebar = document.getElementById('notebook-sidebar');

    if (!alertBox || !mainView || !sidebar) {
        return; // Exit if elements don't exist
    }

    function toggleSidebar() {
        sidebar.classList.toggle('open');
    }

    // Make toggleSidebar globally available
    window.toggleSidebar = toggleSidebar;

    function handleSecurityBreach(reason) {
        // Hiệu ứng "Netflix Blackout": Phủ đen ngay lập tức
        mainView.style.opacity = '0';
        alertBox.style.display = 'flex';
        
        // Trên mobile, thêm class để che phủ hoàn toàn
        if (/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) {
            mainView.classList.add('mobile-protected');
            // Thêm blur effect mạnh hơn
            mainView.classList.add('notebook-blur-effect');
        }
        
        // Xóa clipboard
        try {
            navigator.clipboard.writeText('Protected Content');
        } catch(e) {
            // Ignore errors
        }

        // Đóng sidebar trên mobile
        sidebar.classList.remove('open');
        
        // Log để debug (có thể xóa sau)
        console.warn('Security breach detected:', reason);
    }

    function hideAlert() {
        alertBox.style.display = 'none';
        mainView.style.opacity = '1';
        mainView.classList.remove('mobile-protected');
        mainView.classList.remove('notebook-blur-effect');
    }

    // Make hideAlert globally available
    window.hideAlert = hideAlert;

    // --- BẢO MẬT: CHẶN CHUỘT PHẢI & LONG PRESS (MOBILE) ---
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // --- BẢO MẬT: PHÍM TẮT ---
    // Theo dõi trạng thái phím để chặn ngay khi:
    // - Mac: CMD+Shift
    // - Windows: Windows+Shift HOẶC Ctrl+Shift
    let keyState = {
        meta: false,  // CMD trên Mac, Windows key trên Windows
        ctrl: false,  // Ctrl key (chỉ chặn trên Windows)
        shift: false
    };

    function handleKeyDown(e) {
        // 1. Phím PrintScreen
        if (e.key === 'PrintScreen' || e.keyCode === 44) {
            e.preventDefault();
            e.stopPropagation();
            handleSecurityBreach('PrintScreen');
            return false;
        }

        // 2. Cập nhật trạng thái phím
        const isMeta = e.metaKey || e.key === 'Meta' || e.keyCode === 91 || e.keyCode === 93;
        const isCtrl = e.ctrlKey || e.keyCode === 17;
        const isShift = e.shiftKey || e.keyCode === 16;

        // Cập nhật state
        if (isMeta) keyState.meta = true;
        if (isCtrl) keyState.ctrl = true;
        if (isShift) keyState.shift = true;

        // Phát hiện OS
        const isMac = /Mac|iPhone|iPad|iPod/i.test(navigator.platform) || /Mac/i.test(navigator.userAgent);

        // CHẶN NGAY:
        // - Mac: CMD+Shift
        // - Windows: Windows+Shift HOẶC Ctrl+Shift
        if (isMac) {
            // Mac: chỉ chặn CMD+Shift
            if (keyState.meta && keyState.shift) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                handleSecurityBreach('Cmd+Shift - Blocked Immediately');
                return false;
            }
        } else {
            // Windows: chặn cả Windows+Shift và Ctrl+Shift
            if ((keyState.meta && keyState.shift) || (keyState.ctrl && keyState.shift)) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                const modifier = keyState.meta ? 'Windows' : 'Ctrl';
                handleSecurityBreach(modifier + '+Shift - Blocked Immediately');
                return false;
            }
        }

        // 3. Chặn F12 / DevTools
        const key = e.key;
        if (e.key === 'F12' || e.keyCode === 123 ||
           (isCtrl && isShift && ['I','J','C'].includes(key.toUpperCase())) ||
           (isMeta && e.altKey && ['I','J','C'].includes(key.toUpperCase()))
        ) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    }

    // Sử dụng capture phase (true) để bắt sớm nhất có thể
    document.addEventListener('keydown', handleKeyDown, true);
    window.addEventListener('keydown', handleKeyDown, true);

    // Keyup để reset state và dự phòng
    function handleKeyUp(e) {
        // Reset state khi thả phím
        if (!e.metaKey && e.keyCode !== 91 && e.keyCode !== 93) {
            keyState.meta = false;
        }
        if (!e.ctrlKey && e.keyCode !== 17) {
            keyState.ctrl = false;
        }
        if (!e.shiftKey && e.keyCode !== 16) {
            keyState.shift = false;
        }

        // Dự phòng cho PrintScreen
        if (e.key === 'PrintScreen' || e.keyCode === 44) {
            handleSecurityBreach('PrintScreen KeyUp');
        }

        // Dự phòng: Kiểm tra lại khi thả phím
        const isMeta = e.metaKey || e.key === 'Meta' || e.keyCode === 91 || e.keyCode === 93;
        const isCtrl = e.ctrlKey || e.keyCode === 17;
        const isShift = e.shiftKey || e.keyCode === 16;
        const isMac = /Mac|iPhone|iPad|iPod/i.test(navigator.platform) || /Mac/i.test(navigator.userAgent);
        
        if (isMac) {
            // Mac: chỉ kiểm tra CMD+Shift
            if (isMeta && isShift) {
                handleSecurityBreach('Cmd+Shift KeyUp - Still Blocked');
            }
        } else {
            // Windows: kiểm tra cả Windows+Shift và Ctrl+Shift
            if ((isMeta && isShift) || (isCtrl && isShift)) {
                const modifier = isMeta ? 'Windows' : 'Ctrl';
                handleSecurityBreach(modifier + '+Shift KeyUp - Still Blocked');
            }
        }
    }

    document.addEventListener('keyup', handleKeyUp, true);
    window.addEventListener('keyup', handleKeyUp, true);

    // --- BẢO MẬT: MẤT TIÊU ĐIỂM (Blur) ---
    window.addEventListener('blur', function() {
        document.title = "⚠️ BẢO MẬT";
        mainView.classList.add('notebook-blur-effect');
        // Trên mobile, blur có thể là dấu hiệu chụp màn hình
        if (/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) {
            handleSecurityBreach('Mobile Blur - Possible Screenshot');
        }
    });

    window.addEventListener('focus', function() {
        document.title = "Wiki Notebook - Tài liệu nội bộ";
        mainView.classList.remove('notebook-blur-effect');
    });

    // --- BẢO MẬT MOBILE: Page Visibility API ---
    // Phát hiện khi app/tab bị ẩn (có thể do chụp màn hình)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Trang bị ẩn - có thể là chụp màn hình trên mobile
            if (/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) {
                handleSecurityBreach('Page Hidden - Possible Screenshot');
            } else {
                document.title = "⚠️ BẢO MẬT";
                mainView.classList.add('notebook-blur-effect');
            }
        } else {
            // Trang hiển thị lại
            document.title = "Wiki Notebook - Tài liệu nội bộ";
            mainView.classList.remove('notebook-blur-effect');
        }
    });

    // --- BẢO MẬT MOBILE: Phát hiện thay đổi kích thước đột ngột ---
    let lastResizeTime = Date.now();
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        const now = Date.now();
        const timeSinceLastResize = now - lastResizeTime;
        
        // Nếu resize quá nhanh (< 100ms) có thể là dấu hiệu đáng ngờ
        if (timeSinceLastResize < 100 && /iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) {
            handleSecurityBreach('Suspicious Resize - Possible Screenshot');
        }
        
        lastResizeTime = now;
        resizeTimeout = setTimeout(function() {
            // Reset sau 1 giây
        }, 1000);
    });

    // --- BẢO MẬT MOBILE: Phát hiện touch events đáng ngờ ---
    let touchStartTime = 0;
    let touchStartY = 0;
    document.addEventListener('touchstart', function(e) {
        touchStartTime = Date.now();
        touchStartY = e.touches[0].clientY;
    }, { passive: true });

    document.addEventListener('touchend', function(e) {
        const touchDuration = Date.now() - touchStartTime;
        const touchEndY = e.changedTouches[0].clientY;
        const touchDistance = Math.abs(touchEndY - touchStartY);
        
        // Phát hiện long press (> 1s) có thể là dấu hiệu chụp màn hình
        if (touchDuration > 1000 && touchDistance < 10) {
            if (/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) {
                handleSecurityBreach('Long Touch - Possible Screenshot Attempt');
            }
        }
    }, { passive: true });

    // --- BẢO MẬT MOBILE: Phát hiện khi app chuyển sang background ---
    // iOS specific
    document.addEventListener('pagehide', function() {
        if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
            handleSecurityBreach('Page Hide - Possible Screenshot');
        }
    });

    // Android specific - khi app bị minimize
    window.addEventListener('beforeunload', function() {
        if (/Android/i.test(navigator.userAgent)) {
            // Không thể chặn nhưng có thể cảnh báo
            document.title = "⚠️ BẢO MẬT";
        }
    });

    // --- BẢO MẬT MOBILE: Theo dõi thay đổi focus liên tục ---
    let focusChangeCount = 0;
    let focusChangeTimer;
    window.addEventListener('blur', function() {
        focusChangeCount++;
        clearTimeout(focusChangeTimer);
        focusChangeTimer = setTimeout(function() {
            // Nếu mất focus nhiều lần trong thời gian ngắn
            if (focusChangeCount > 2 && /iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) {
                handleSecurityBreach('Multiple Focus Changes - Suspicious');
            }
            focusChangeCount = 0;
        }, 2000);
    });

    // --- BẢO MẬT MOBILE: Theo dõi liên tục trên mobile ---
    // Kiểm tra định kỳ trên mobile để phát hiện các thay đổi đáng ngờ
    if (/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) {
        let lastVisibilityState = !document.hidden;
        let lastFocusState = document.hasFocus();
        
        setInterval(function() {
            const currentVisibility = !document.hidden;
            const currentFocus = document.hasFocus();
            
            // Nếu visibility hoặc focus thay đổi đột ngột
            if (lastVisibilityState !== currentVisibility || lastFocusState !== currentFocus) {
                // Chỉ cảnh báo nếu thay đổi từ visible -> hidden hoặc focus -> blur
                if ((lastVisibilityState && !currentVisibility) || 
                    (lastFocusState && !currentFocus)) {
                    // Delay nhỏ để tránh false positive khi chuyển tab bình thường
                    setTimeout(function() {
                        if (document.hidden || !document.hasFocus()) {
                            handleSecurityBreach('Mobile State Change Detected');
                        }
                    }, 200);
                }
                
                lastVisibilityState = currentVisibility;
                lastFocusState = currentFocus;
            }
        }, 500); // Kiểm tra mỗi 500ms
    }

    // Load content function
    function loadContent(id) {
        // Code xử lý chuyển bài...
        if (window.innerWidth < 768) {
            sidebar.classList.remove('open');
        }
    }

    // Make loadContent globally available
    window.loadContent = loadContent;
})();

