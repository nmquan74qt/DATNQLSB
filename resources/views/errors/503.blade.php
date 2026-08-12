<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Đang Bảo Trì</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-100 p-8 text-center overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
        
        <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-person-digging text-4xl text-emerald-500"></i>
        </div>
        
        <h1 class="text-2xl font-bold text-slate-800 mb-3">Hệ thống đang bảo trì</h1>
        
        <p class="text-slate-500 text-sm leading-relaxed mb-8">
            Chúng tôi đang tiến hành nâng cấp hệ thống để mang lại trải nghiệm tốt hơn. 
            Vui lòng quay lại sau ít phút. Xin lỗi vì sự bất tiện này!
        </p>
        
        <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-600 mb-6">
            <div class="flex items-center justify-center gap-2 mb-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="font-medium text-emerald-600">Đang tiến hành bảo trì định kỳ</span>
            </div>
            <p class="text-xs">Thời gian dự kiến hoàn thành: Sắp xong</p>
        </div>
        
        <div class="flex items-center justify-center gap-4 text-sm text-slate-400">
            <a href="#" onclick="location.reload()" class="hover:text-emerald-500 transition-colors flex items-center gap-1">
                <i class="fa-solid fa-rotate-right"></i> Tải lại trang
            </a>
            <span>&bull;</span>
            <a href="mailto:support@pitchmanage.com" class="hover:text-emerald-500 transition-colors">Liên hệ hỗ trợ</a>
        </div>
    </div>
</body>
</html>
