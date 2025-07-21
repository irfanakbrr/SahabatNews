@props(['class' => ''])

<div class="my-4 {{ $class }}">
    <a href="https://wa.me/6287827054701" target="_blank" rel="noopener noreferrer" class="attractive-compact-banner block w-full group transition-all duration-300 hover:transform hover:scale-105">
        
        <!-- Clean Professional Design -->
        <div class="relative overflow-hidden bg-white dark:bg-gray-800 px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-gray-500 transition-all duration-300 hover:shadow-md">
            
            <!-- Subtle Accent Line -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between">
                
                <!-- Left Content with Clean Icon -->
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Ruang Iklan Premium</span>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Promosikan bisnis Anda</div>
                    </div>
                </div>
                
                <!-- Right CTA with Pulse -->
                <div class="relative">
                    <div class="flex items-center gap-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 transform group-hover:scale-105 shadow-lg group-hover:shadow-xl">
                        <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.516"/>
                        </svg>
                        <span>WhatsApp</span>
                        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <!-- Button Pulse Ring -->
                    <div class="absolute -inset-1 bg-green-400 rounded-full opacity-0 group-hover:opacity-20 group-hover:animate-ping"></div>
                </div>
                
            </div>
            
        </div>
    </a>
</div>

<style>
/* Clean Professional Banner */
.attractive-compact-banner {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.attractive-compact-banner:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

/* Clean Icon Hover */
.attractive-compact-banner .w-8 {
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.attractive-compact-banner:hover .w-8 {
    background-color: #2563eb;
}

/* Text Hover Effect */
.attractive-compact-banner .text-sm {
    transition: color 0.3s ease;
}

/* Button Enhanced */
.attractive-compact-banner .bg-gradient-to-r {
    transition: all 0.3s ease;
}

.attractive-compact-banner:hover .bg-gradient-to-r {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(34, 197, 94, 0.3);
}

/* Responsive Design */
@media (max-width: 640px) {
    .attractive-compact-banner {
        margin: 0.5rem 0;
    }
    
    .attractive-compact-banner .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .attractive-compact-banner .py-3 {
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
    
    .attractive-compact-banner .text-sm {
        font-size: 0.875rem;
    }
    
    .attractive-compact-banner .text-xs {
        font-size: 0.75rem;
    }
    
    .attractive-compact-banner .w-8 {
        width: 1.75rem;
        height: 1.75rem;
    }
    
    .attractive-compact-banner .gap-3 {
        gap: 0.75rem;
    }
    
    /* Reduce hover effects on mobile */
    .attractive-compact-banner:hover {
        transform: scale(1.01);
    }
}

/* Focus states for accessibility */
.attractive-compact-banner:focus-visible {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Smooth loading */
.attractive-compact-banner {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(-10px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
</style> 