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
        
        // Xóa clipboard
        try {
            navigator.clipboard.writeText('Protected Content');
        } catch(e) {
            // Ignore errors
        }

        // Đóng sidebar trên mobile
        sidebar.classList.remove('open');
    }

    function hideAlert() {
        alertBox.style.display = 'none';
        mainView.style.opacity = '1';
    }

    // Make hideAlert globally available
    window.hideAlert = hideAlert;

    // --- BẢO MẬT: CHẶN CHUỘT PHẢI & LONG PRESS (MOBILE) ---
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // --- BẢO MẬT: PHÍM TẮT ---
    // Sử dụng capture phase để bắt sớm hơn
    function handleKeyDown(e) {
        // 1. Phím PrintScreen
        if (e.key === 'PrintScreen' || e.keyCode === 44) {
            e.preventDefault();
            e.stopPropagation();
            handleSecurityBreach('PrintScreen');
            return false;
        }

        // 2. MACBOOK: Command + Shift + 3/4/5
        // Kiểm tra nhiều cách để chắc chắn bắt được
        const isMeta = e.metaKey || e.key === 'Meta' || e.keyCode === 91 || e.keyCode === 93;
        const isShift = e.shiftKey || e.keyCode === 16;
        const key = e.key;
        const keyCode = e.keyCode;
        
        // KeyCode cho số: 3=51, 4=52, 5=53
        const isNumberKey = (keyCode === 51 || keyCode === 52 || keyCode === 53) || 
                           (key === '3' || key === '4' || key === '5');

        // Kiểm tra tổ hợp Command + Shift + số
        if (isMeta && isShift && isNumberKey) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            const number = key || String.fromCharCode(keyCode);
            handleSecurityBreach('Mac Screenshot: Cmd+Shift+' + number);
            return false;
        }

        // 3. WINDOWS: Win + Shift + S
        if (isMeta && isShift && (key === 's' || key === 'S' || keyCode === 83)) {
            e.preventDefault();
            e.stopPropagation();
            handleSecurityBreach('Win+Shift+S');
            return false;
        }

        // 4. Chặn F12 / DevTools
        if (e.key === 'F12' || e.keyCode === 123 ||
           (e.ctrlKey && isShift && ['I','J','C'].includes(key.toUpperCase())) ||
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

    // Keyup dự phòng
    function handleKeyUp(e) {
        // Dự phòng cho PrintScreen
        if (e.key === 'PrintScreen' || e.keyCode === 44) {
            handleSecurityBreach('PrintScreen KeyUp');
        }

        // Dự phòng cho Mac Screenshot - kiểm tra lại khi thả phím
        const isMeta = e.metaKey || e.key === 'Meta' || e.keyCode === 91 || e.keyCode === 93;
        const isShift = e.shiftKey || e.keyCode === 16;
        const keyCode = e.keyCode;
        const isNumberKey = (keyCode === 51 || keyCode === 52 || keyCode === 53);
        
        if (isMeta && isShift && isNumberKey) {
            const number = String.fromCharCode(keyCode);
            handleSecurityBreach('Mac Screenshot KeyUp: Cmd+Shift+' + number);
        }
    }

    document.addEventListener('keyup', handleKeyUp, true);
    window.addEventListener('keyup', handleKeyUp, true);

    // --- BẢO MẬT: MẤT TIÊU ĐIỂM (Blur) ---
    window.addEventListener('blur', function() {
        document.title = "⚠️ BẢO MẬT";
        mainView.classList.add('notebook-blur-effect');
    });

    window.addEventListener('focus', function() {
        document.title = "Wiki Notebook - Tài liệu nội bộ";
        mainView.classList.remove('notebook-blur-effect');
    });

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

