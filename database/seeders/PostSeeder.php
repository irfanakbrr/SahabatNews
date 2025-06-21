<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB; // Untuk transaksi jika perlu

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID Editor dan Kategori
        $editorUser = User::whereHas('role', function ($query) {
            $query->where('name', 'editor');
        })->first();
        
        if (!$editorUser) {
            $this->command->warn('Editor user not found, skipping post seeding.');
            return;
        }

        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->command->warn('No categories found, skipping post seeding.');
            return;
        }

        // Hapus semua komentar dulu agar foreign key tidak error
        \App\Models\Comment::query()->delete();
        // Hapus semua post lama
        \App\Models\Post::query()->delete();

        $postsData = [
            [
                'title' => 'Tren Investasi Hijau di Indonesia Meningkat, Dorong Pembangunan Berkelanjutan',
                'content' => 'Investasi hijau di Indonesia menunjukkan tren peningkatan yang signifikan, didorong oleh kesadaran global akan pentingnya pembangunan berkelanjutan. Sektor energi terbarukan, pengelolaan limbah, dan pertanian berkelanjutan menjadi magnet bagi investor lokal maupun asing. Pemerintah terus memberikan insentif untuk menarik lebih banyak modal ke sektor-sektor ramah lingkungan ini.',
                'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80', // panel surya
                'category_name' => 'Economics',
            ],
            [
                'title' => 'Program Kartu Prakerja Terus Berlanjut: Perluasan Skema Pelatihan',
                'content' => 'Program Kartu Prakerja akan terus dilanjutkan dengan perluasan skema pelatihan yang lebih relevan dengan kebutuhan pasar kerja. Pemerintah berfokus pada peningkatan kualitas dan relevansi kursus, serta jangkauan peserta, termasuk bagi penyandang disabilitas. Tujuannya adalah meningkatkan kompetensi angkatan kerja dan mengurangi angka pengangguran di Indonesia.',
                'image' => 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=800&q=80', // belajar online
                'category_name' => 'Indonesia News',
            ],
            [
                'title' => 'Adopsi 5G di Indonesia Meluas: Dampak pada Industri dan Masyarakat',
                'content' => 'Adopsi teknologi 5G di Indonesia semakin meluas, membawa potensi besar untuk transformasi berbagai sektor industri dan kehidupan masyarakat. Kecepatan dan latensi rendah 5G memungkinkan inovasi di bidang Internet of Things (IoT), kecerdasan buatan, hingga pengembangan kota pintar. Tantangannya kini adalah pemerataan infrastruktur dan edukasi publik tentang manfaat teknologi ini.',
                'image' => 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=800&q=80', // menara 5G
                'category_name' => 'IT',
            ],
            [
                'title' => 'Restorasi Ekosistem Mangrove: Peran Penting dalam Mitigasi Bencana Pesisir',
                'content' => 'Upaya restorasi ekosistem mangrove di berbagai wilayah pesisir Indonesia semakin digalakkan. Hutan mangrove tidak hanya menjadi habitat bagi keanekaragaman hayati laut, tetapi juga berperan vital sebagai benteng alami mitigasi bencana seperti abrasi dan tsunami. Program penanaman kembali dan edukasi masyarakat menjadi kunci keberhasilan restorasi ini.',
                'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80', // mangrove
                'category_name' => 'Nature',
            ],
            [
                'title' => 'RUU Kesehatan Disahkan: Kontroversi dan Harapan Perbaikan Sistem',
                'content' => 'Rancangan Undang-Undang (RUU) Kesehatan akhirnya disahkan menjadi undang-undang, memicu beragam reaksi dari berbagai pihak. Kontroversi seputar beberapa pasal menjadi sorotan, namun diharapkan undang-undang baru ini dapat membawa perbaikan signifikan dalam sistem kesehatan nasional, termasuk akses layanan, tenaga medis, dan ketersediaan obat-obatan.',
                'image' => 'https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=800&q=80', // gedung DPR
                'category_name' => 'Politics',
            ],
            [
                'title' => 'Revolusi CRISPR: Potensi dan Dilema Etis dalam Rekayasa Genetik',
                'content' => 'Teknologi penyuntingan gen CRISPR-Cas9 terus merevolusi bidang biologi dan kedokteran. Potensinya untuk mengobati penyakit genetik yang sebelumnya tak tersembuhkan sangat besar, namun juga memunculkan dilema etis yang kompleks terkait rekayasa genetik pada manusia. Para ilmuwan dan regulator terus berupaya menyeimbangkan inovasi dengan pertimbangan moral.',
                'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80', // DNA
                'category_name' => 'Science',
            ],
            [
                'title' => 'Sepak Bola Indonesia Menuju Level Dunia: Pembinaan Usia Dini Jadi Kunci',
                'content' => 'Sepak bola Indonesia memiliki ambisi besar untuk mencapai level dunia. Fokus utama kini adalah pada pembinaan usia dini yang lebih terstruktur dan berkesinambungan. Liga-liga junior, akademi sepak bola berkualitas, dan kompetisi berjenjang diharapkan dapat melahirkan talenta-talenta muda yang mampu bersaing di kancah internasional.',
                'image' => 'https://images.unsplash.com/photo-1505843273132-bc5c6f7bfa98?auto=format&fit=crop&w=800&q=80', // anak main bola
                'category_name' => 'Sports',
            ],
            [
                'title' => 'Krisis Pangan Global Memburuk: Dampak Konflik dan Perubahan Iklim',
                'content' => 'Krisis pangan global semakin memburuk, diperparah oleh konflik geopolitik, perubahan iklim, dan gangguan rantai pasok. Jutaan orang di berbagai belahan dunia menghadapi kelaparan dan kerawanan pangan akut. Organisasi internasional menyerukan tindakan kolektif dan mendesak untuk mengatasi tantangan kemanusiaan ini.',
                'image' => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?auto=format&fit=crop&w=800&q=80', // beras
                'category_name' => 'World News',
            ],
            [
                'title' => 'UMKM Go Digital: Strategi Bertahan dan Berkembang di Era Digital',
                'content' => 'Sektor Usaha Mikro, Kecil, dan Menengah (UMKM) di Indonesia semakin masif mengadopsi platform digital. Strategi "UMKM Go Digital" menjadi kunci bagi pelaku usaha untuk bertahan dan berkembang di tengah persaingan pasar yang ketat. Pelatihan literasi digital, akses ke platform e-commerce, dan dukungan finansial menjadi fokus pemerintah.',
                'image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80', // laptop UMKM
                'category_name' => 'Economics',
            ],
            [
                'title' => 'Peningkatan Kualitas Pendidikan Vokasi: Menjawab Kebutuhan Industri',
                'content' => 'Pemerintah terus berupaya meningkatkan kualitas pendidikan vokasi di Indonesia untuk menjawab kebutuhan industri yang semakin kompleks. Kolaborasi antara sekolah vokasi, politeknik, dan dunia usaha/industri menjadi kunci untuk menyelaraskan kurikulum dengan kompetensi yang dibutuhkan pasar kerja. Magang dan sertifikasi profesional diperkuat.',
                'image' => 'https://images.unsplash.com/photo-1515168833906-d2a3b82b3029?auto=format&fit=crop&w=800&q=80', // siswa vokasi
                'category_name' => 'Indonesia News',
            ],
            [
                'title' => 'Metaverse dan Web3: Tren Teknologi Masa Depan yang Mengubah Interaksi',
                'content' => 'Konsep Metaverse dan Web3 menjadi topik hangat di dunia teknologi, menjanjikan perubahan fundamental dalam cara manusia berinteraksi, bekerja, dan berbisnis. Meskipun masih dalam tahap awal pengembangan, potensi desentralisasi, kepemilikan aset digital, dan pengalaman imersif membuka era baru inovasi.',
                'image' => 'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=800&q=80', // VR headset
                'category_name' => 'IT',
            ],
            [
                'title' => 'Penemuan Spesies Baru di Hutan Kalimantan: Kekayaan Biodiversitas yang Belum Terungkap',
                'content' => 'Peneliti baru-baru ini mengumumkan penemuan beberapa spesies baru flora dan fauna di hutan pedalaman Kalimantan. Penemuan ini menegaskan kembali kekayaan biodiversitas Indonesia yang luar biasa, sekaligus menyoroti pentingnya upaya konservasi untuk melindungi ekosistem yang masih menyimpan banyak misteri dan potensi penemuan lainnya.',
                'image' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=800&q=80', // hutan kalimantan
                'category_name' => 'Nature',
            ],
            [
                'title' => 'Pemilu Kepala Daerah Serentak 2024: Dinamika Politik Lokal Menjelang Pemilihan',
                'content' => 'Menjelang Pemilu Kepala Daerah (Pilkada) Serentak 2024, dinamika politik lokal di berbagai provinsi, kota, dan kabupaten semakin terasa. Para calon mulai intens melakukan sosialisasi, sementara partai politik sibuk menyusun strategi koalisi. Pilkada ini menjadi barometer penting bagi peta kekuatan politik di daerah.',
                'image' => 'https://images.unsplash.com/photo-1518655048521-f130df041f66?auto=format&fit=crop&w=800&q=80', // kotak suara
                'category_name' => 'Politics',
            ],
            [
                'title' => 'Tantangan Penyakit Degeneratif: Harapan dari Riset Sel Punca',
                'content' => 'Penyakit degeneratif seperti Alzheimer, Parkinson, dan diabetes terus menjadi tantangan besar bagi kesehatan global. Namun, riset di bidang sel punca (stem cell) menunjukkan harapan baru untuk pengobatan yang lebih efektif. Terapi berbasis sel punca berpotensi meregenerasi sel dan jaringan yang rusak, meskipun masih memerlukan penelitian lebih lanjut.',
                'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80', // sel manusia
                'category_name' => 'Science',
            ],
            [
                'title' => 'E-sports di Indonesia Terus Bertumbuh: Pengakuan Sebagai Cabang Olahraga Prestasi',
                'content' => 'E-sports di Indonesia terus mengalami pertumbuhan pesat dan semakin mendapat pengakuan sebagai cabang olahraga prestasi. Turnamen-turnamen berskala nasional dan internasional kian marak, menarik jutaan penggemar dan potensi ekonomi yang besar. Pembinaan atlet e-sports profesional menjadi fokus untuk membawa nama Indonesia di kancah global.',
                'image' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=800&q=80', // gamer
                'category_name' => 'Sports',
            ],
            [
                'title' => 'Konflik Timur Tengah: Upaya Diplomatik Menuju Solusi Damai',
                'content' => 'Konflik di Timur Tengah masih menjadi sorotan dunia, dengan upaya diplomatik terus digalakkan untuk mencari solusi damai. Berbagai negara dan organisasi internasional berupaya menengahi konflik, mencari jalan keluar dari ketegangan yang telah berlangsung lama. Harapan besar tertumpu pada dialog dan negosiasi untuk mencapai stabilitas regional.',
                'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80', // ilustrasi perdamaian
                'category_name' => 'World News',
            ],
            [
                'title' => 'Resiliensi Sektor Manufaktur Indonesia: Kunci Pemulihan Ekonomi Nasional',
                'content' => 'Sektor manufaktur Indonesia menunjukkan resiliensi yang kuat di tengah gejolak ekonomi global. Kontribusinya terhadap Produk Domestik Bruto (PDB) terus meningkat, didorong oleh peningkatan kapasitas produksi dan diversifikasi produk. Inovasi dan penggunaan teknologi menjadi kunci untuk menjaga daya saing industri manufaktur nasional.',
                'image' => 'https://images.unsplash.com/photo-1515168833906-d2a3b82b3029?auto=format&fit=crop&w=800&q=80', // pabrik
                'category_name' => 'Economics',
            ],
            [
                'title' => 'Kebijakan Pariwisata Ramah Lingkungan: Menjaga Keindahan Alam Indonesia',
                'content' => 'Pemerintah Indonesia semakin serius dalam menerapkan kebijakan pariwisata ramah lingkungan. Fokusnya adalah menjaga kelestarian alam dan budaya di destinasi wisata, sekaligus memberdayakan masyarakat lokal. Peningkatan kesadaran wisatawan dan edukasi tentang praktik pariwisata bertanggung jawab menjadi prioritas.',
                'image' => 'https://images.unsplash.com/photo-1465101178521-c1a9136a3b99?auto=format&fit=crop&w=800&q=80', // membersihkan sampah
                'category_name' => 'Indonesia News',
            ],
            [
                'title' => 'Keamanan Data Pribadi: Tantangan dan Solusi di Era Digital',
                'content' => 'Keamanan data pribadi menjadi isu krusial di era digital, seiring dengan maraknya kasus kebocoran data dan penyalahgunaan informasi. Masyarakat dan perusahaan dituntut untuk lebih waspada dan proaktif dalam melindungi data. Regulasi yang kuat dan implementasi teknologi keamanan yang canggih menjadi solusi untuk mengatasi tantangan ini.',
                'image' => 'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=800&q=80', // gembok digital
                'category_name' => 'IT',
            ],
            [
                'title' => 'Ancaman Mikroplastik di Laut: Dampak dan Upaya Penanggulangan',
                'content' => 'Mikroplastik telah menjadi polutan yang mengkhawatirkan di ekosistem laut Indonesia. Partikel-partikel plastik kecil ini merusak kehidupan laut dan berpotensi masuk ke rantai makanan manusia. Berbagai upaya penanggulangan, mulai dari pengurangan penggunaan plastik sekali pakai hingga riset inovatif, terus dilakukan untuk mengatasi krisis lingkungan ini.',
                'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80', // sampah plastik di pantai
                'category_name' => 'Nature',
            ],
        ];

        foreach ($postsData as $data) {
            $category = $categories->firstWhere('name', $data['category_name']);
            if ($category) {
                Post::create([
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'image' => $data['image'],
                    'user_id' => $editorUser->id,
                    'category_id' => $category->id,
                    'status' => 'published',
                    'published_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        // Opsional: Buat post dummy lainnya menggunakan factory
        // Post::factory(15)->create(); // Pastikan factory di-setup dengan benar
    }
} 