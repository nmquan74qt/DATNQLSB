<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Truy Cập Bị Từ Chối</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body { background-color: #f8fafc; overflow: hidden; }
        .dark body { background-color: #0f172a; }
        .lock-container { perspective: 1000px; }
    </style>
</head>
<body class="h-screen w-screen flex flex-col items-center justify-center relative dark">

    <div class="lock-container relative z-10 p-8 text-center" id="main-content">
        <!-- Lock Animation -->
        <div class="w-32 h-32 mx-auto mb-8 relative" id="lock-icon">
            <div class="absolute inset-0 bg-red-500 rounded-3xl opacity-20 blur-xl"></div>
            <div class="relative bg-gradient-to-br from-red-500 to-red-700 w-full h-full rounded-3xl flex items-center justify-center text-6xl text-white shadow-2xl shadow-red-500/50 border border-red-400/30">
                <i class="fa-solid fa-lock"></i>
            </div>
        </div>

        <h1 class="text-6xl md:text-8xl font-heading font-black text-slate-900 dark:text-white tracking-tighter mb-4">403</h1>
        <h2 class="text-2xl md:text-3xl font-bold font-heading text-red-500 mb-4">Khu Vực Hạn Chế</h2>
        <p class="text-slate-500 max-w-md mx-auto mb-8">Bạn không có quyền truy cập vào phân hệ này. Xin vui lòng liên hệ Quản trị viên để được cấp quyền.</p>
        
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold px-8 py-4 rounded-xl shadow-lg hover:-translate-y-1 transition-transform">
            <i class="fa-solid fa-arrow-left"></i> Quay Lại
        </a>
    </div>

    <script>
        gsap.from("#main-content", {
            scale: 0.8,
            opacity: 0,
            duration: 1,
            ease: "elastic.out(1, 0.5)"
        });

        // Shake the lock
        gsap.to("#lock-icon", {
            x: -10,
            duration: 0.1,
            repeat: 5,
            yoyo: true,
            delay: 1,
            ease: "none"
        });
    </script>
</body>
</html>
