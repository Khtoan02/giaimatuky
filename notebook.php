<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <!-- CẬP NHẬT MOBILE: user-scalable=no để chặn zoom, ngăn nhìn trộm kỹ hơn -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Wiki Notebook - Tài liệu nội bộ</title>
    <link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-bg: #2c3e50;
            --sidebar-text: #ecf0f1;
            --paper-bg: #fffdf0;
            --line-color: #94a3b8;
            --margin-line: #ef4444;
            --text-color: #334155;
            --sidebar-width: 260px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #e2e8f0;
            height: 100vh;
            display: flex;
            overflow: hidden;
            
            /* --- BẢO MẬT CẤP ĐỘ CSS (QUAN TRỌNG CHO MOBILE) --- */
            
            /* 1. Chống bôi đen cơ bản */
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

            /* 2. CHẶN MENU KHI ẤN GIỮ TRÊN IPHONE (iOS Safari) */
            -webkit-touch-callout: none;
            
            /* 3. Chặn hành động kéo thả ảnh ra ngoài */
            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            z-index: 10;
            transition: transform 0.3s ease;
        }

        /* Nút menu cho Mobile */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 15px; left: 15px;
            z-index: 100;
            background: #2c3e50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .brand {
            font-family: 'Patrick Hand', cursive;
            font-size: 1.8rem;
            margin-bottom: 30px;
            color: #f1c40f;
            text-align: center;
            border-bottom: 2px dashed #7f8c8d;
            padding-bottom: 15px;
        }

        .menu-list { list-style: none; padding: 0; margin: 0; overflow-y: auto; }

        .menu-item {
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .menu-item:hover, .menu-item.active {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(5px);
        }

        .menu-item span { margin-right: 10px; }

        .contact-box {
            margin-top: auto;
            background: rgba(0,0,0,0.2);
            padding: 15px;
            border-radius: 8px;
            font-size: 0.85rem;
            text-align: center;
        }

        /* --- MAIN CONTENT AREA --- */
        .main-container {
            flex: 1;
            position: relative;
            overflow-y: auto;
            padding: 40px;
            display: flex;
            justify-content: center;
            transition: opacity 0.05s; /* Tăng tốc độ ẩn */
            /* Đảm bảo scroll mượt trên iOS */
            -webkit-overflow-scrolling: touch; 
        }

        .notebook-paper {
            width: 100%;
            max-width: 900px;
            min-height: 1000px;
            background-color: var(--paper-bg);
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
            position: relative;
            padding: 60px 80px 60px 80px;
            color: var(--text-color);
            background-image: linear-gradient(var(--line-color) 1px, transparent 1px);
            background-size: 100% 32px;
            line-height: 32px;
        }

        .notebook-paper::before {
            content: '';
            position: absolute;
            top: 0; bottom: 0; left: 60px;
            width: 2px;
            background-color: var(--margin-line);
        }

        .content-area h1 {
            font-family: 'Patrick Hand', cursive;
            color: #d35400;
            font-size: 2.5rem;
            margin-top: -10px;
            margin-bottom: 32px;
            border-bottom: 2px solid #d35400;
            display: inline-block;
        }

        .content-area h2 {
            font-family: 'Patrick Hand', cursive;
            color: #2980b9;
            margin-top: 32px;
            font-size: 1.8rem;
        }

        .content-area p { margin: 0 0 32px 0; text-align: justify; }

        /* --- WATERMARK --- */
        .watermark {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 6rem;
            font-weight: bold;
            color: rgba(0,0,0,0.04);
            pointer-events: none;
            white-space: nowrap;
            z-index: 0;
        }
        
        .repeat-watermark {
             position: absolute;
             top: 0; left: 0; width: 100%; height: 100%;
             /* SVG Watermark lặp lại dày đặc hơn cho Mobile */
             background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' height='150px' width='150px'><text transform='translate(20, 80) rotate(-45)' fill='rgba(255,0,0,0.04)' font-size='16' font-weight='bold' font-family='sans-serif'>CHỐNG SAO CHÉP - LIÊN HỆ ADMIN</text></svg>");
             pointer-events: none;
             z-index: 0;
        }

        /* --- SECURITY OVERLAY (Màn hình đen) --- */
        #security-alert {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #000; /* Đen tuyệt đối giống Netflix */
            z-index: 99999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        #security-alert h2 { color: #e74c3c; font-size: 2rem; margin-bottom: 15px; }
        .btn-unlock {
            padding: 12px 30px; background: #e74c3c; color: white;
            border: none; border-radius: 50px; font-size: 1.1rem;
            cursor: pointer; font-weight: bold; margin-top: 20px;
        }

        /* Blur Effect */
        .blur-effect {
            filter: blur(25px) grayscale(100%);
            transition: filter 0.05s; /* Siêu nhanh */
        }
        
        /* Hiệu ứng Blackout khi chụp màn hình */
        .blackout {
            background-color: #000 !important;
            filter: brightness(0) !important;
            opacity: 1 !important; /* Ghi đè opacity */
        }
        .blackout * {
            visibility: hidden !important;
        }

        /* --- RESPONSIVE MOBILE --- */
        @media (max-width: 768px) {
            .mobile-toggle { display: block; }
            
            .sidebar {
                position: fixed;
                height: 100%;
                left: -260px; /* Ẩn sidebar đi */
                top: 0;
            }
            .sidebar.open { left: 0; }

            .main-container { padding: 10px; margin-top: 40px; }
            .notebook-paper { 
                padding: 40px 20px; 
                line-height: 32px; 
            }
            .notebook-paper::before { left: 20px; } /* Thu gọn lề đỏ */
            
            .watermark { font-size: 2.5rem; word-wrap: break-word; text-align: center; }
            .content-area h1 { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

    <!-- Nút mở menu trên Mobile -->
    <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>

    <div id="security-alert">
        <h2>🚫 MÀN HÌNH ĐƯỢC BẢO VỆ</h2>
        <p>Hệ thống đã tự động che nội dung khi phát hiện chụp màn hình.</p>
        <button class="btn-unlock" onclick="hideAlert()">Mở lại</button>
    </div>

    <aside class="sidebar" id="sidebar">
        <div class="brand">📒 Wiki Mobile</div>
        <ul class="menu-list">
            <li class="menu-item active" onclick="loadContent(1)"><span>📌</span> Tổng quan</li>
            <li class="menu-item" onclick="loadContent(2)"><span>📱</span> Giao diện Mobile</li>
            <li class="menu-item" onclick="loadContent(3)"><span>🍏</span> Bảo mật macOS</li>
            <li class="menu-item" onclick="loadContent(4)"><span>🔒</span> Admin Only</li>
        </ul>
        <div class="contact-box">
            Bảo mật tối đa cho<br><strong>Mobile & Desktop</strong>
        </div>
    </aside>

    <main class="main-container" id="main-view">
        <div class="notebook-paper">
            <div class="watermark">BẢN QUYỀN <br> (MOBILE FRIENDLY)</div>
            <div class="repeat-watermark"></div>

            <div class="content-area">
                <h1>Hệ thống Wiki Đa Nền Tảng</h1>
                <p>Phiên bản này đã được tối ưu hóa để hiển thị tốt trên cả Máy tính, MacBook và Điện thoại di động.</p>
                
                <h2>1. Đối với iPhone / Android</h2>
                <p>Trên điện thoại, việc copy đã bị chặn hoàn toàn nhờ CSS <code>-webkit-touch-callout: none</code>.</p>
                <p>Khi bạn nhấn giữ vào màn hình, menu "Sao chép/Lưu ảnh" sẽ <strong>không hiện ra</strong>.</p>
                <p>Tuy nhiên, chúng tôi không thể chặn phím cứng (Nguồn + Vol) để chụp ảnh. Vì vậy, dòng chữ chìm (Watermark) nền đỏ nhạt đã được tăng cường độ dày đặc.</p>

                <h2>2. Đối với MacBook</h2>
                <p>Hệ thống phát hiện phím Command (⌘) tốt hơn.</p>
                <p>Nếu bạn thử dùng tổ hợp phím tắt, nội dung sẽ bị làm mờ ngay lập tức.</p>
                
                <h2>3. Nội dung demo</h2>
                <p>Con cáo nâu nhanh nhẹn nhảy qua con chó lười biếng. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>
        </div>
    </main>

    <script>
        const alertBox = document.getElementById('security-alert');
        const mainView = document.getElementById('main-view');
        const sidebar = document.getElementById('sidebar');
        const body = document.body;

        function toggleSidebar() {
            sidebar.classList.toggle('open');
        }

        function handleSecurityBreach(reason) {
            // Hiệu ứng "Netflix Blackout": Phủ đen ngay lập tức
            // Cách 1: Ẩn opacity (Nội dung biến mất, nền hiện ra)
            mainView.style.opacity = '0';
            
            // Cách 2 (Mới): Bật lớp phủ đen ngay không chờ animation
            alertBox.style.display = 'flex';
            
            // Xóa clipboard
            try { navigator.clipboard.writeText('Protected Content'); } catch(e){}

            // Đóng sidebar trên mobile
            sidebar.classList.remove('open');
        }

        function hideAlert() {
            alertBox.style.display = 'none';
            mainView.style.opacity = '1';
        }

        // --- BẢO MẬT: CHẶN CHUỘT PHẢI & LONG PRESS (MOBILE) ---
        document.addEventListener('contextmenu', e => e.preventDefault());

        // --- BẢO MẬT: PHÍM TẮT ---
        document.addEventListener('keydown', function(e) {
            // 1. Phím PrintScreen
            if (e.key === 'PrintScreen') {
                e.preventDefault();
                handleSecurityBreach('PrintScreen');
                return;
            }

            // 2. MACBOOK: Command + Shift + 3/4
            if (e.metaKey && e.shiftKey) {
                if (e.key === '3' || e.key === '4' || e.key === '5') {
                    e.preventDefault();
                    handleSecurityBreach('Mac Screenshot');
                    return;
                }
            }

            // 3. WINDOWS: Win + Shift + S
            if (e.key === 'Meta' || e.metaKey) {
                if (e.shiftKey && (e.key === 's' || e.key === 'S')) {
                    handleSecurityBreach('Win+Shift+S');
                    return;
                }
            }

            // 4. Chặn F12 / DevTools
            if (e.key === 'F12' || 
               (e.ctrlKey && e.shiftKey && ['I','J','C'].includes(e.key.toUpperCase())) ||
               (e.metaKey && e.altKey && ['I','J','C'].includes(e.key.toUpperCase()))
            ) {
                e.preventDefault();
                return false;
            }
        });

        // Keyup dự phòng cho PrintScreen
        document.addEventListener('keyup', (e) => {
            if (e.key === 'PrintScreen') handleSecurityBreach('PrintScreen KeyUp');
        });

        // --- BẢO MẬT: MẤT TIÊU ĐIỂM (Blur) ---
        // Đây là cách mô phỏng Netflix tốt nhất trên Web Text
        // Nếu người dùng bật Snipping Tool => Mất focus => Đen màn hình
        window.addEventListener('blur', () => {
            document.title = "⚠️ BẢO MẬT";
            mainView.classList.add('blur-effect');
            // Bạn có thể kích hoạt luôn handleSecurityBreach('Blur') nếu muốn gắt hơn
            // handleSecurityBreach('Window Blur'); 
        });

        window.addEventListener('focus', () => {
            document.title = "Wiki Notebook - Tài liệu nội bộ";
            mainView.classList.remove('blur-effect');
        });

        function loadContent(id) {
            // Code xử lý chuyển bài...
            if(window.innerWidth < 768) sidebar.classList.remove('open');
        }
    </script>
</body>
</html>