# 📰 SahabatNews - Portal Berita Modern

Portal berita berbasis Laravel dengan fitur lengkap dan desain modern yang responsif.

## 🌟 Fitur Utama

### 📱 Frontend Features
- Homepage Modern: Tampilan beranda dengan grid news layout yang responsif
- Load More System: Sistem load more berita dengan pagination Ajax
- Kategori Berita: Filter berita berdasarkan kategori dengan warna yang menarik
- Search Functionality: Pencarian berita dengan hasil yang akurat
- Responsive Design: Optimal di desktop, tablet, dan mobile
- PWA Support: Progressive Web App dengan offline capability
- Custom 404 Page: Halaman error 404 dengan tema "Lost in Space"

### 🔐 Authentication System
- Login/Register Forms: Formulir autentikasi dengan desain glassmorphism
- Role-based Access: Sistem peran (Admin, Editor, User)
- Profile Management: Manajemen profil pengguna lengkap
- Social Authentication: Login dengan provider eksternal (ready)

### 📊 Admin Dashboard
- Analytics Realtime: Dashboard dengan statistik views real-time
- Content Management: Manajemen artikel, kategori, dan pengguna
- Comment Moderation: Moderasi komentar dengan approval system
- User Management: Kelola pengguna dan peran mereka

### 🎯 Content Features
- Article Management: CRUD artikel dengan editor TinyMCE
- Category System: Sistem kategori dengan warna custom
- Comment System: Sistem komentar dengan notifikasi
- Bookmark Feature: Simpan artikel favorit
- View Tracking: Pelacakan views artikel real-time
- Image Handling: Upload dan optimasi gambar otomatis

### 🕌 Islamic Features
- Al-Quran Digital: Baca Al-Quran dengan navigasi surah
- Jadwal Sholat: Waktu sholat berdasarkan lokasi
- Konten Islami: Kategori khusus untuk konten keislaman

### 💰 Advertisement System
- Hero Banner Ads: Iklan banner di halaman utama
- In-Feed Ads: Iklan yang terintegrasi dalam feed berita
- Contextual Placement: Penempatan iklan yang kontekstual
- WhatsApp Integration: Semua iklan terhubung ke WhatsApp business

### 📄 Additional Pages
- All News Page: Halaman semua berita dengan filter dan search
- Contact Page: Integrasi WhatsApp untuk kontak
- About Page: Informasi tentang SahabatNews
- Privacy & Terms: Halaman kebijakan dan syarat penggunaan

## 🚀 Teknologi yang Digunakan

### Backend
- Laravel 11: Framework PHP modern
- MySQL: Database management system
- Eloquent ORM: Object-relational mapping
- Laravel Sanctum: API authentication
- Queue System: Background job processing

### Frontend
- Blade Templates: Laravel templating engine
- Tailwind CSS: Utility-first CSS framework
- Alpine.js: Lightweight JavaScript framework
- Chart.js: Data visualization library
- Anime.js: Animation library

### Additional Tools
- Vite: Build tool dan hot reload
- TinyMCE: Rich text editor
- FontAwesome: Icon library
- Service Workers: PWA functionality

## 📦 Instalasi

### Requirements
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/MariaDB

### Langkah Instalasi

1. Clone Repository
```bash
git clone https://github.com/username/SahabatNews.git
cd SahabatNews
```

2. Install Dependencies
```bash
# Backend dependencies
composer install

# Frontend dependencies
npm install
```

3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

4. Database Configuration
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sahabatnews
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Database Migration & Seeding
```bash
# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed
```

6. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

7. Storage Link
```bash
php artisan storage:link
```

8. Start Development Server
```bash
php artisan serve
```

## 👥 Default User Accounts

Setelah seeding, tersedia akun default:

### Admin Account
- Email: admin@sahabatnews.com
- Password: password
- Role: Administrator

### Editor Account
- Email: editor@sahabatnews.com
- Password: password
- Role: Editor

### Regular User
- Email: user@sahabatnews.com
- Password: password
- Role: User

## 🎨 Kustomisasi

### Logo & Branding
- Logo utama: `public/logosn.svg`
- Favicon: Otomatis menggunakan logo utama
- Nama aplikasi: Konfigurasi di `.env` → `APP_NAME`

### Warna Kategori
Edit `database/seeders/CategorySeeder.php` untuk mengubah warna kategori:
```php
'color' => 'bg-red-600', // Tailwind CSS classes
```

### Advertisement Settings
Konfigurasi iklan di file komponen:
- `resources/views/components/ad-space.blade.php`
- `resources/views/components/in-feed-ad.blade.php`

## 📱 PWA Configuration

### Manifest Settings
Konfigurasi PWA di `public/manifest.json`:
```json
{
  "name": "Sahabat News",
  "short_name": "SahabatNews",
  "start_url": "/",
  "background_color": "#1f2937",
  "theme_color": "#3b82f6"
}
```

### Service Worker
Service worker dikonfigurasi di `public/sw.js` untuk:
- Cache static assets
- Offline page fallback
- Background sync (future)

## 🔧 Fitur Development

### Artisan Commands
```bash
# Generate dummy posts
php artisan posts:generate

# Reset admin password
php artisan admin:reset-password
```

### API Endpoints
- `GET /api/posts` - Daftar artikel
- `GET /api/views/realtime` - Data views real-time
- `POST /api/posts/{id}/view` - Track view artikel

### Database Structure
- users: Data pengguna dan autentikasi
- roles: Sistem peran pengguna
- categories: Kategori artikel
- posts: Artikel/berita utama
- comments: Sistem komentar
- bookmarks: Bookmark pengguna
- post_views: Tracking views artikel

## 🚀 Deployment

### Shared Hosting
1. Upload files ke public_html
2. Move public folder contents to root
3. Update paths di `index.php`
4. Set file permissions (755/644)
5. Import database

### VPS/Cloud
1. Setup web server (Nginx/Apache)
2. Configure PHP-FPM
3. Setup SSL certificate
4. Configure environment variables
5. Setup cron jobs untuk queue

### Environment Variables
```env
APP_NAME="Sahabat News"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

## 🐛 Troubleshooting

### Common Issues

1. Migration Errors
```bash
# Reset and migrate
php artisan migrate:fresh --seed
```

2. Permission Issues
```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
```

3. Asset Not Loading
```bash
# Rebuild assets
npm run build
```

4. Database Connection
- Pastikan credentials di `.env` benar
- Cek apakah database service running
- Verifikasi user privileges

## 📚 Documentation

### Code Structure
```
app/
├── Http/Controllers/     # Controllers
├── Models/              # Eloquent models
├── Services/            # Business logic services
├── Jobs/               # Background jobs
└── Observers/          # Model observers

resources/
├── views/              # Blade templates
├── css/               # Stylesheets
└── js/                # JavaScript files

database/
├── migrations/         # Database migrations
├── seeders/           # Data seeders
└── factories/         # Model factories
```

### Key Components
- PostController: Manajemen artikel
- AnalyticsController: Dashboard analytics
- CategoryController: Manajemen kategori
- AiContentService: Service untuk AI content (future)
- UnsplashService: Integrasi gambar Unsplash

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

Project ini menggunakan MIT License. Lihat file `LICENSE` untuk detail.

## 👨‍💻 Developer

Dikembangkan dengan ❤️ untuk menyediakan platform berita yang modern dan user-friendly.

### Contact
- Website: [SahabatNews](#)
- WhatsApp: +62 878-2705-4701
- Email: admin@sahabatnews.com

---

SahabatNews - Portal Berita Terpercaya untuk Masa Depan yang Lebih Baik 🌟 