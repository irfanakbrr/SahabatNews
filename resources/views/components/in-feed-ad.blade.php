@props(['type' => 'gradient', 'title' => 'Sponsored Content', 'subtitle' => 'Dapatkan Penawaran Terbaik!', 'cta' => 'Klik Sekarang'])

<div class="in-feed-ad bg-white rounded-xl shadow-sm hover:shadow-md transition flex flex-col overflow-hidden group cursor-pointer" onclick="handleAdClick('{{ $type }}')">
    <!-- Ad Header -->
    <div class="relative h-40 overflow-hidden">
        @if($type === 'gradient')
            <!-- Gradient Animation Ad -->
            <div class="gradient-ad-bg w-full h-full relative">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500 via-pink-500 to-blue-500 animate-gradient-shift"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-white">
                        <div class="floating-icons mb-2">
                            <i class="fas fa-gift text-3xl animate-bounce"></i>
                            <i class="fas fa-star text-xl animate-pulse ml-2"></i>
                            <i class="fas fa-heart text-lg animate-ping ml-1"></i>
                        </div>
                        <div class="ad-text-white text-lg font-bold animate-pulse">🎉 PROMO SPESIAL 🎉</div>
                    </div>
                </div>
            </div>
        
        @elseif($type === 'shopping')
            <!-- Shopping Animation Ad -->
            <div class="shopping-ad-bg w-full h-full relative bg-gradient-to-r from-green-400 to-blue-500">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-white">
                        <div class="shopping-cart-animation mb-2">
                            <i class="fas fa-shopping-cart text-4xl animate-bounce"></i>
                            <div class="inline-block ml-2">
                                <i class="fas fa-coins text-yellow-300 animate-spin"></i>
                                <i class="fas fa-percentage text-yellow-300 animate-pulse ml-1"></i>
                            </div>
                        </div>
                        <div class="ad-text-white text-lg font-bold">💰 DISKON 50% 💰</div>
                    </div>
                </div>
            </div>

        @elseif($type === 'tech')
            <!-- Tech Animation Ad -->
            <div class="tech-ad-bg w-full h-full relative bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-white">
                        <div class="tech-icons mb-2">
                            <i class="fab fa-android text-3xl animate-pulse text-green-300"></i>
                            <i class="fab fa-apple text-3xl animate-bounce ml-2"></i>
                            <i class="fas fa-laptop text-2xl animate-pulse ml-2 text-blue-300"></i>
                        </div>
                        <div class="ad-text-white text-lg font-bold animate-pulse">📱 GADGET TERBARU 📱</div>
                    </div>
                </div>
            </div>

        @elseif($type === 'food')
            <!-- Food Animation Ad -->
            <div class="food-ad-bg w-full h-full relative bg-gradient-to-r from-orange-400 via-red-500 to-pink-500">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-white">
                        <div class="food-icons mb-2">
                            <i class="fas fa-pizza-slice text-3xl animate-bounce text-yellow-300"></i>
                            <i class="fas fa-hamburger text-2xl animate-pulse ml-2"></i>
                            <i class="fas fa-ice-cream text-2xl animate-bounce ml-2 text-pink-200"></i>
                        </div>
                        <div class="ad-text-white text-lg font-bold">🍔 DELIVERY GRATIS! 🍕</div>
                    </div>
                </div>
            </div>

        @else
            <!-- Default Animation Ad -->
            <div class="default-ad-bg w-full h-full relative bg-gradient-to-br from-blue-500 to-purple-600">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-white">
                        <div class="default-icons mb-2">
                            <i class="fas fa-bullhorn text-3xl animate-pulse"></i>
                            <i class="fas fa-lightning-bolt text-xl animate-bounce ml-2 text-yellow-300"></i>
                        </div>
                        <div class="ad-text-white text-lg font-bold animate-pulse">✨ PENAWARAN KHUSUS ✨</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Floating particles effect -->
        <div class="floating-particles absolute inset-0 pointer-events-none">
            <div class="particle particle-1"></div>
            <div class="particle particle-2"></div>
            <div class="particle particle-3"></div>
        </div>
    </div>

    <!-- Ad Content -->
    <div class="p-4 flex-1 flex flex-col">
        <span class="sponsor-label inline-block text-xs font-bold px-2 py-1 rounded mb-2 animate-pulse">
            📢 IKLAN SPONSOR
        </span>
        <div class="ad-title font-semibold text-base mb-1 group-hover:text-purple-600 transition">{{ $title }}</div>
        <div class="ad-subtitle text-sm mb-2">{{ $subtitle }}</div>
        <div class="mt-auto">
            <button class="cta-button w-full text-white py-2 px-4 rounded-lg font-semibold transition-all transform">
                {{ $cta }} →
            </button>
        </div>
    </div>
</div>

<style>
/* Ensure ad is always visible */
.in-feed-ad {
    opacity: 1 !important;
    visibility: visible !important;
    display: flex !important;
    background: white !important;
    border: 2px solid #f3f4f6;
    position: relative;
    z-index: 1;
}

/* Gradient Animation - More visible */
@keyframes gradient-shift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.animate-gradient-shift {
    background-size: 400% 400% !important;
    animation: gradient-shift 4s ease infinite;
}

/* Solid background colors for visibility */
.gradient-ad-bg {
    background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c) !important;
    background-size: 400% 400% !important;
}

.shopping-ad-bg {
    background: linear-gradient(-45deg, #4facfe, #00f2fe) !important;
}

.tech-ad-bg {
    background: linear-gradient(-45deg, #667eea, #764ba2) !important;
}

.food-ad-bg {
    background: linear-gradient(-45deg, #ffecd2, #fcb69f) !important;
}

.default-ad-bg {
    background: linear-gradient(-45deg, #a8edea, #fed6e3) !important;
}

/* Floating Particles - More visible */
.floating-particles {
    overflow: hidden;
    pointer-events: none;
}

.particle {
    position: absolute;
    width: 6px;
    height: 6px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    animation: float 3s infinite ease-in-out;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
}

.particle-1 {
    top: 20%;
    left: 20%;
    animation-delay: 0s;
    animation-duration: 3s;
}

.particle-2 {
    top: 60%;
    left: 80%;
    animation-delay: 1s;
    animation-duration: 4s;
}

.particle-3 {
    top: 80%;
    left: 40%;
    animation-delay: 2s;
    animation-duration: 3.5s;
}

@keyframes float {
    0%, 100% { 
        transform: translateY(0px) translateX(0px);
        opacity: 0.3;
    }
    50% { 
        transform: translateY(-15px) translateX(8px);
        opacity: 1;
    }
}

/* Icon animations - more prominent */
@keyframes bounce-prominent {
    0%, 20%, 53%, 80%, 100% {
        transform: translate3d(0,0,0);
    }
    40%, 43% {
        transform: translate3d(0,-15px,0);
    }
    70% {
        transform: translate3d(0,-7px,0);
    }
    90% {
        transform: translate3d(0,-2px,0);
    }
}

@keyframes pulse-prominent {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.floating-icons i,
.shopping-cart-animation i,
.tech-icons i,
.food-icons i,
.default-icons i {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.animate-bounce {
    animation: bounce-prominent 2s infinite;
}

.animate-pulse {
    animation: pulse-prominent 2s infinite;
}

/* Hover Effects */
.in-feed-ad:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #e5e7eb;
}

.in-feed-ad:hover .floating-icons i {
    animation-duration: 0.8s;
    transform: scale(1.1);
}

.in-feed-ad:hover .tech-icons i {
    transform: scale(1.15);
    transition: transform 0.3s ease;
}

.in-feed-ad:hover .food-icons i {
    animation-duration: 0.5s;
    transform: scale(1.1);
}

.in-feed-ad:hover .shopping-cart-animation i {
    animation-duration: 0.6s;
    transform: scale(1.1);
}

/* Sponsor label more visible */
.sponsor-label {
    background: linear-gradient(45deg, #fbbf24, #f59e0b) !important;
    color: #000 !important;
    font-weight: 800 !important;
    border: 1px solid #d97706;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* CTA button more prominent */
.cta-button {
    background: linear-gradient(45deg, #8b5cf6, #ec4899) !important;
    border: none !important;
    font-weight: 700 !important;
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3) !important;
    transition: all 0.3s ease !important;
}

.cta-button:hover {
    transform: translateY(-1px) scale(1.02) !important;
    box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4) !important;
}

/* Responsive Design */
@media (max-width: 640px) {
    .in-feed-ad .text-3xl {
        font-size: 1.5rem;
    }
    
    .in-feed-ad .text-lg {
        font-size: 1rem;
    }
    
    .in-feed-ad .text-2xl {
        font-size: 1.25rem;
    }
}

/* Ensure text is readable */
.ad-text-white {
    color: white !important;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    font-weight: 600;
}

.ad-title {
    color: #1f2937 !important;
    font-weight: 700;
}

.ad-subtitle {
    color: #6b7280 !important;
    font-weight: 500;
}
</style>

<script>
function handleAdClick(adType) {
    // Analytics tracking
    console.log('Ad clicked:', adType);
    
    // Add click animation
    event.currentTarget.style.transform = 'scale(0.98)';
    setTimeout(() => {
        event.currentTarget.style.transform = 'scale(1)';
    }, 150);
    
    // Simulate ad redirect (ganti dengan URL iklan yang sebenarnya)
    const adUrls = {
        'gradient': 'https://example.com/promo-special',
        'shopping': 'https://example.com/shopping-discount',
        'tech': 'https://example.com/gadget-terbaru',
        'food': 'https://example.com/food-delivery',
        'default': 'https://example.com/penawaran-khusus'
    };
    
    // Uncomment untuk redirect actual
    // window.open(adUrls[adType] || adUrls['default'], '_blank');
    
    // Untuk demo, tampilkan alert
    alert(`Iklan ${adType} diklik! (Demo - ganti dengan URL iklan yang sebenarnya)`);
}

// Optional: Random animation intensity
document.addEventListener('DOMContentLoaded', function() {
    const ads = document.querySelectorAll('.in-feed-ad');
    ads.forEach(ad => {
        // Add random delay to animations for more organic feel
        const icons = ad.querySelectorAll('.animate-bounce, .animate-pulse, .animate-ping');
        icons.forEach((icon, index) => {
            icon.style.animationDelay = `${Math.random() * 2}s`;
        });
    });
});
</script> 