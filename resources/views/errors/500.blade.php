<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Lỗi Máy Chủ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body { background-color: #0f172a; overflow: hidden; color: white; }
        .gear { transform-origin: center; }
    </style>
</head>
<body class="h-screen w-screen flex flex-col items-center justify-center relative">

    <!-- Big Background Gears -->
    <div class="absolute inset-0 overflow-hidden opacity-5 pointer-events-none flex items-center justify-center">
        <i class="fa-solid fa-gear text-[500px] text-white gear" id="gear-1" style="position: absolute; left: -100px; top: -100px;"></i>
        <i class="fa-solid fa-gear text-[300px] text-white gear" id="gear-2" style="position: absolute; right: 100px; bottom: 100px;"></i>
    </div>

    <div class="relative z-10 p-8 text-center" id="main-content">
        <div class="text-amber-500 text-6xl mb-6 flex justify-center gap-4">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>

        <h1 class="text-6xl md:text-8xl font-heading font-black tracking-tighter mb-4 text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600">500</h1>
        <h2 class="text-2xl md:text-3xl font-bold font-heading mb-4">Hệ Thống Quá Tải</h2>
        <p class="text-slate-400 max-w-md mx-auto mb-8">Oops! Một vài bánh răng trong hệ thống máy chủ của chúng tôi vừa bị kẹt. Đội ngũ kỹ sư đang tức tốc khắc phục!</p>
        
        <button onclick="window.location.reload()" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold px-8 py-4 rounded-xl shadow-lg hover:-translate-y-1 transition-transform">
            <i class="fa-solid fa-rotate-right"></i> Thử Lại Ngay
        </button>
    </div>

    <script>
        gsap.from("#main-content", {
            y: 50,
            opacity: 0,
            duration: 1,
            ease: "power3.out"
        });

        // Rotate gears infinitely but slowly
        gsap.to("#gear-1", {
            rotation: 360,
            duration: 20,
            repeat: -1,
            ease: "none"
        });
        
        gsap.to("#gear-2", {
            rotation: -360,
            duration: 15,
            repeat: -1,
            ease: "none"
        });
    </script>
</body>
</html>
