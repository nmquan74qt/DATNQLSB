<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không Tìm Thấy Trang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; color: white; overflow: hidden; }
        .font-heading { font-family: 'Outfit', sans-serif; }
        #glitch-text { position: relative; }
        #glitch-text::before, #glitch-text::after {
            content: "404";
            position: absolute;
            top: 0; left: 0; right: 0;
            background: #0f172a;
            overflow: hidden;
            clip: rect(0, 900px, 0, 0);
        }
        #glitch-text::before { left: -2px; text-shadow: 2px 0 red; animation: glitch-anim-1 2s infinite linear alternate-reverse; }
        #glitch-text::after { left: 2px; text-shadow: -2px 0 blue; animation: glitch-anim-2 3s infinite linear alternate-reverse; }
        @keyframes glitch-anim-1 {
            0% { clip: rect(20px, 9999px, 85px, 0); }
            20% { clip: rect(92px, 9999px, 34px, 0); }
            40% { clip: rect(10px, 9999px, 60px, 0); }
            60% { clip: rect(40px, 9999px, 12px, 0); }
            80% { clip: rect(72px, 9999px, 90px, 0); }
            100% { clip: rect(33px, 9999px, 20px, 0); }
        }
        @keyframes glitch-anim-2 {
            0% { clip: rect(60px, 9999px, 15px, 0); }
            20% { clip: rect(20px, 9999px, 80px, 0); }
            40% { clip: rect(80px, 9999px, 30px, 0); }
            60% { clip: rect(15px, 9999px, 90px, 0); }
            80% { clip: rect(45px, 9999px, 50px, 0); }
            100% { clip: rect(90px, 9999px, 40px, 0); }
        }
        .orbit { position: absolute; top: 50%; left: 50%; border-radius: 50%; border: 1px dashed rgba(255,255,255,0.1); transform: translate(-50%, -50%); pointer-events: none;}
    </style>
</head>
<body class="h-screen w-screen flex flex-col items-center justify-center relative">

    <!-- Orbits Background -->
    <div class="orbit w-[400px] h-[400px]"></div>
    <div class="orbit w-[600px] h-[600px]"></div>
    <div class="orbit w-[800px] h-[800px]"></div>

    <!-- Floating Astronaut/Ball -->
    <div id="ufo" class="absolute w-20 h-20 bg-primary rounded-full blur-xl opacity-50 mix-blend-screen"></div>

    <div class="text-center relative z-10 p-8">
        <h1 id="glitch-text" class="text-[150px] md:text-[200px] font-heading font-black leading-none tracking-tighter text-transparent bg-clip-text bg-gradient-to-br from-white to-slate-500">404</h1>
        
        <div class="space-y-4 mt-4" id="error-content">
            <h2 class="text-2xl md:text-3xl font-bold font-heading text-white">Lạc Lối Giữa Không Gian</h2>
            <p class="text-slate-400 max-w-md mx-auto">Trang bạn đang tìm kiếm dường như đã bị một lỗ đen vũ trụ nuốt chửng, hoặc có thể nó chưa từng tồn tại.</p>
            
            <div class="mt-8">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-white text-slate-900 font-bold px-8 py-4 rounded-full hover:scale-105 hover:shadow-[0_0_30px_rgba(255,255,255,0.3)] transition-all duration-300">
                    <i class="fa-solid fa-rocket"></i> Trở Về Trái Đất
                </a>
            </div>
        </div>
    </div>

    <script>
        // GSAP Animations
        gsap.to("#ufo", {
            x: "random(-200, 200)",
            y: "random(-200, 200)",
            scale: "random(0.8, 1.5)",
            duration: 4,
            ease: "sine.inOut",
            repeat: -1,
            yoyo: true
        });

        gsap.from("#error-content", {
            y: 50,
            opacity: 0,
            duration: 1,
            delay: 0.5,
            ease: "power3.out"
        });

        // Mouse parallax
        document.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth / 2 - e.pageX) * 0.05;
            const y = (window.innerHeight / 2 - e.pageY) * 0.05;
            gsap.to("#glitch-text", { x: x, y: y, duration: 1 });
        });
    </script>
</body>
</html>
