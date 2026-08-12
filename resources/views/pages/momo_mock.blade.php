<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cổng thanh toán MoMo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        .text-momo { color: #a50064; }
        .bg-momo { background-color: #a50064; }
        .bg-momo-light { background-color: #fcecf4; }
        .border-momo { border-color: #a50064; }
        
        /* Laser scanning animation */
        .scan-container {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .laser-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #a50064;
            box-shadow: 0 0 10px #a50064, 0 0 20px #a50064;
            animation: scan 2s linear infinite;
            z-index: 10;
        }
        @keyframes scan {
            0% { top: 0; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }

        /* Pulse animation for the center logo */
        .pulse-logo {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(165, 0, 100, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(165, 0, 100, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(165, 0, 100, 0); }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button onclick="history.back()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors" title="Quay về">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <div class="flex items-center gap-3">
                    <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" class="h-8" alt="MoMo">
                    <div class="h-6 w-px bg-gray-300 hidden sm:block"></div>
                    <span class="text-gray-700 font-medium hidden sm:block">Cổng thanh toán an toàn</span>
                </div>
            </div>
            <div class="text-sm font-medium text-gray-500">
                <i class="fa-solid fa-headset mr-1"></i> Hỗ trợ: 1900 54 54 41
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-12 px-4">
        <div class="max-w-5xl w-full bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] overflow-hidden flex flex-col md:flex-row border border-gray-100">
            
            <!-- Left: QR Code Section -->
            <div class="w-full md:w-5/12 bg-gray-50 p-8 flex flex-col items-center justify-center border-r border-gray-100 relative">
                <!-- Decorative background shapes -->
                <div class="absolute top-0 left-0 w-full h-40 bg-momo opacity-5 rounded-br-full pointer-events-none"></div>
                <div class="absolute bottom-0 right-0 w-32 h-32 bg-momo opacity-5 rounded-tl-full pointer-events-none"></div>

                <div class="flex items-center gap-2 mb-6">
                    <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" class="h-6" alt="MoMo">
                </div>
                
                <h2 class="text-lg font-bold text-gray-800 mb-1">Quét mã để thanh toán</h2>
                <p class="text-sm text-gray-500 mb-8">Sử dụng Ứng dụng MoMo để quét mã</p>

                <div class="scan-container bg-white p-4 rounded-xl border-2 border-pink-100 shadow-sm mb-6">
                    <div class="laser-line"></div>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=MOMO-{{ $orderId }}" class="w-56 h-56 relative z-0" alt="QR Code">
                    <!-- MoMo logo in center -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-20">
                        <div class="bg-white p-1 rounded-full pulse-logo shadow-md">
                            <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" class="w-10 h-10 rounded-full object-contain" alt="MoMo">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-momo bg-momo-light px-4 py-2 rounded-full text-sm font-semibold mb-2">
                    <i class="fa-solid fa-clock"></i> Thời gian chờ thanh toán: <span id="countdown">10:00</span>
                </div>
                
                <div id="status-text" class="mt-4 text-sm font-medium text-gray-500 flex items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin text-momo"></i> Đang chờ người dùng quét mã...
                </div>
            </div>

            <!-- Right: Order Details -->
            <div class="w-full md:w-7/12 p-8 lg:p-12 flex flex-col justify-center">
                <div class="mb-10">
                    <p class="text-gray-500 text-sm font-medium mb-1">Nhà cung cấp dịch vụ</p>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        PitchManage - Đặt Sân Tự Động <i class="fa-solid fa-circle-check text-blue-500 text-lg"></i>
                    </h2>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 mb-8 border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-500">Mã giao dịch:</span>
                        <span class="font-bold text-gray-800 bg-white px-3 py-1 rounded border border-gray-200">{{ $orderId }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <span class="text-gray-600 font-medium">Số tiền thanh toán:</span>
                        <span class="text-3xl font-bold text-momo">{{ number_format($amount, 0, ',', '.') }}<span class="text-xl underline align-top ml-1">đ</span></span>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="font-bold text-gray-800 text-lg border-b pb-2">Hướng dẫn thanh toán</h3>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-momo text-white flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">1</div>
                        <p class="text-gray-600">Mở ứng dụng <b>MoMo</b> trên điện thoại của bạn.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-momo text-white flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">2</div>
                        <p class="text-gray-600">Trên màn hình chính, chọn biểu tượng <b class="text-momo"><i class="fa-solid fa-qrcode"></i> Quét mã</b>.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-momo text-white flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">3</div>
                        <p class="text-gray-600">Di chuyển camera quét mã QR trên màn hình này.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-momo text-white flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">4</div>
                        <p class="text-gray-600">Kiểm tra thông tin & bấm <b>Xác nhận thanh toán</b>.</p>
                    </div>
                </div>

                <!-- Simulation Button for Demo -->
                <div class="mt-8 border-t border-dashed border-gray-200 pt-6">
                    <p class="text-sm text-gray-500 mb-3 text-center"><i class="fa-solid fa-laptop-code mr-1"></i> Tính năng dành cho báo cáo Đồ Án:</p>
                    <button onclick="successSimulate()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl shadow-md transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-mobile-screen-button"></i> Giả lập Khách đã quét mã & Chuyển khoản
                    </button>
                    <a href="{{ $returnUrl }}?orderId={{ $orderId }}&resultCode=1006&message=Cancel" class="block w-full mt-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-center font-bold py-3.5 rounded-xl transition-colors">
                        <i class="fa-solid fa-xmark"></i> Hủy giao dịch (Giả lập)
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center py-6 text-gray-500 text-sm">
        <p>© 2026 PitchManage - Giao diện giả lập thanh toán phục vụ báo cáo Đồ án.</p>
    </footer>

    <script>
        // Countdown timer (10:00)
        let time = 600;
        const countdownEl = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            time--;
            let minutes = Math.floor(time / 60);
            let seconds = time % 60;
            countdownEl.textContent = `${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            if (time <= 0) clearInterval(timer);
        }, 1000);

        function successSimulate() {
            const statusEl = document.getElementById('status-text');
            statusEl.innerHTML = '<i class="fa-solid fa-circle-check text-green-500"></i> Đã thanh toán thành công! Đang xử lý...';
            statusEl.classList.remove('text-gray-500');
            statusEl.classList.add('text-green-600', 'font-bold');
            
            // Remove laser line on success
            const laser = document.querySelector('.laser-line');
            if (laser) laser.remove();

            // Redirect after 1.5s
            setTimeout(() => {
                window.location.href = "{{ $returnUrl }}?orderId={{ $orderId }}&resultCode=0&message=Success";
            }, 1500);
        }

    </script>
</body>
</html>
