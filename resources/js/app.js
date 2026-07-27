import './bootstrap';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import AOS from 'aos';
import 'aos/dist/aos.css';
import './interactions';

// Make available globally
window.Alpine = Alpine;
window.Swal = Swal;

// Initialize AOS for scroll animations
AOS.init({
    duration: 800,
    easing: 'ease-out-cubic',
    once: true,
    offset: 50,
});

Alpine.start();
