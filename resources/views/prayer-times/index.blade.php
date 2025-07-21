@extends('layouts.main')

@section('title', 'Jadwal Sholat - ' . config('app.name'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">🕌 Jadwal Sholat</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Jadwal waktu sholat berdasarkan lokasi Anda. Tetap terhubung dengan Allah SWT di setiap waktu.
        </p>
    </div>

    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ $error }}
        </div>
    @endif

    <!-- Location Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">📍 Lokasi</h2>
            <button onclick="getCurrentLocation()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm transition-colors duration-300">
                Gunakan Lokasi Saya
            </button>
        </div>
        <p class="text-gray-600" id="currentLocation">{{ $location ?? 'Jakarta, Indonesia' }}</p>
    </div>

    @if(isset($prayerTimes))
        <!-- Date Section -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg p-6 mb-8">
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-2">{{ $prayerTimes['date']['gregorian'] }}</h2>
                <p class="text-green-100">{{ $prayerTimes['date']['hijri'] }} H</p>
            </div>
        </div>

        <!-- Prayer Times Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($prayerTimes['timings'] as $prayer => $time)
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-600 prayer-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-1">
                                {{ $prayer === 'Fajr' ? 'Subuh' : ($prayer === 'Dhuhr' ? 'Dzuhur' : ($prayer === 'Asr' ? 'Ashar' : ($prayer === 'Maghrib' ? 'Maghrib' : ($prayer === 'Isha' ? 'Isya' : $prayer)))) }}
                            </h3>
                            <p class="text-gray-500 text-sm">{{ $prayer }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-green-600">{{ $time }}</p>
                            <div class="text-xs text-gray-500 time-status" data-prayer="{{ $prayer }}" data-time="{{ $time }}">
                                <!-- Status will be filled by JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="prayer-emoji text-2xl">
                            @if($prayer === 'Fajr')
                                🌅
                            @elseif($prayer === 'Sunrise')
                                ☀️
                            @elseif($prayer === 'Dhuhr')
                                🌞
                            @elseif($prayer === 'Asr')
                                🌤️
                            @elseif($prayer === 'Maghrib')
                                🌅
                            @elseif($prayer === 'Isha')
                                🌙
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Next Prayer Countdown -->
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Waktu Sholat Selanjutnya</h3>
            <div id="nextPrayer" class="text-green-600 text-lg font-medium mb-2">
                <!-- Will be filled by JavaScript -->
            </div>
            <div id="countdown" class="text-3xl font-bold text-gray-800">
                <!-- Will be filled by JavaScript -->
            </div>
        </div>
    @endif

    <!-- Ad Space -->
    <div class="mt-8">
        <x-ad-space />
    </div>
</div>

@push('styles')
<style>
    .prayer-card {
        transition: all 0.3s ease;
    }
    
    .prayer-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .prayer-card.current {
        border-l-color: #f59e0b;
        background: linear-gradient(135deg, #fef3c7 0%, #ffffff 100%);
    }
    
    .prayer-card.next {
        border-l-color: #10b981;
        background: linear-gradient(135deg, #d1fae5 0%, #ffffff 100%);
    }
</style>
@endpush

@push('scripts')
<script>
let prayerTimes = @json($prayerTimes['timings'] ?? []);

function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            
            // Get location name using reverse geocoding
            fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=id`)
                .then(response => response.json())
                .then(data => {
                    const location = data.city + ', ' + data.countryName;
                    updatePrayerTimes(latitude, longitude, location);
                })
                .catch(error => {
                    updatePrayerTimes(latitude, longitude, 'Lokasi Anda');
                });
        }, function(error) {
            alert('Gagal mengambil lokasi Anda. Pastikan Anda memberikan izin akses lokasi.');
        });
    } else {
        alert('Browser Anda tidak mendukung geolocation.');
    }
}

function updatePrayerTimes(latitude, longitude, location) {
    fetch('/prayer-times/location', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            latitude: latitude,
            longitude: longitude,
            location: location
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('Gagal memperbarui jadwal sholat.');
    });
}

function updateTimeStatus() {
    const now = new Date();
    const currentTime = now.getHours() * 60 + now.getMinutes();
    const currentSeconds = now.getSeconds();
    
    let nextPrayer = null;
    let nextPrayerTime = null;
    let currentPrayer = null;
    
    // Convert prayer times to minutes
    const prayers = Object.entries(prayerTimes).map(([name, time]) => {
        const [hours, minutes] = time.split(':').map(Number);
        return {
            name,
            time: hours * 60 + minutes,
            timeString: time,
            hours,
            minutes
        };
    }).sort((a, b) => a.time - b.time);
    
    // Find current and next prayer
    for (let i = 0; i < prayers.length; i++) {
        if (currentTime < prayers[i].time) {
            nextPrayer = prayers[i];
            break;
        }
        if (i === prayers.length - 1 || (currentTime >= prayers[i].time && currentTime < prayers[i + 1].time)) {
            currentPrayer = prayers[i];
        }
    }
    
    // If no prayer found for today, next prayer is tomorrow's first prayer
    let nextPrayerDate = new Date(now);
    if (!nextPrayer) {
        nextPrayer = prayers[0];
        nextPrayerDate.setDate(now.getDate() + 1);
    }
    
    // Set next prayer time (today or tomorrow)
    let nextPrayerTimeObj = new Date(nextPrayerDate);
    nextPrayerTimeObj.setHours(nextPrayer.hours, nextPrayer.minutes, 0, 0);
    if (nextPrayerTimeObj < now) {
        nextPrayerTimeObj.setDate(nextPrayerTimeObj.getDate() + 1);
    }
    
    // Update UI
    document.querySelectorAll('.prayer-card').forEach(card => {
        card.classList.remove('current', 'next');
    });
    
    if (currentPrayer) {
        const currentCard = document.querySelector(`[data-prayer="${currentPrayer.name}"]`).closest('.prayer-card');
        if (currentCard) currentCard.classList.add('current');
    }
    
    if (nextPrayer) {
        const nextCard = document.querySelector(`[data-prayer="${nextPrayer.name}"]`).closest('.prayer-card');
        if (nextCard) nextCard.classList.add('next');
        
        // Update next prayer info
        const prayerNameMap = {
            'Fajr': 'Subuh',
            'Dhuhr': 'Dzuhur', 
            'Asr': 'Ashar',
            'Maghrib': 'Maghrib',
            'Isha': 'Isya'
        };
        
        document.getElementById('nextPrayer').textContent = prayerNameMap[nextPrayer.name] || nextPrayer.name;
        
        // Calculate countdown in seconds
        const diffMs = nextPrayerTimeObj - now;
        let totalSeconds = Math.floor(diffMs / 1000);
        if (totalSeconds < 0) totalSeconds = 0;
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;
        
        document.getElementById('countdown').textContent = 
            `${hours.toString().padStart(2, '0')}:` +
            `${minutes.toString().padStart(2, '0')}:` +
            `${seconds.toString().padStart(2, '0')}`;
    }
}

// Update every second
if (Object.keys(prayerTimes).length > 0) {
    updateTimeStatus();
    setInterval(updateTimeStatus, 1000);
}
</script>
@endpush
@endsection 