<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="stylesheet" href="{{ asset('css/404-space.css') }}">
    <link rel="icon" href="{{ asset('logosn.svg') }}" type="image/svg+xml">
</head>
<body class="bg-purple">
    <div class="stars">
        <div class="central-body">
            <img class="image-404" src="{{ asset('assets/404/404.svg') }}" width="300px">
            <a href="{{ url('/') }}" class="btn-go-home">KEMBALI KE BERANDA</a>
        </div>
        <div class="objects">
            <img class="object_rocket" src="{{ asset('assets/404/rocket.svg') }}" width="40px">
            <div class="earth-moon">
                <img class="object_earth" src="{{ asset('assets/404/earth.svg') }}" width="100px">
                <img class="object_moon" src="{{ asset('assets/404/moon.svg') }}" width="80px">
            </div>
            <div class="box_astronaut">
                <img class="object_astronaut" src="{{ asset('assets/404/astronaut.svg') }}" width="140px">
            </div>
        </div>
        <div class="glowing_stars">
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
            <div class="star"></div>
        </div>
    </div>
</body>
</html> 