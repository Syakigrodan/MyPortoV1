<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Sistem Kasir Restoran',
                'slug' => 'sistem-kasir-restoran',
                'description' => 'Aplikasi point-of-sale lengkap untuk restoran: manajemen menu, meja, pesanan, pembayaran, dan laporan penjualan harian secara real-time.',
                'category' => 'Web App',
                'year' => 2025,
                'link' => '#',
                'github' => 'https://github.com/',
                'tech_stack' => ['Laravel', 'MySQL', 'Bootstrap', 'JavaScript'],
                'highlights' => [
                    'Manajemen menu, meja, dan pesanan real-time',
                    'Checkout cepat dengan split pembayaran',
                    'Cetak struk otomatis ke printer thermal',
                    'Laporan penjualan harian & bulanan',
                    'Autentikasi kasir & role manajer',
                ],
                'challenge' => 'Menyinkronkan status meja dan antrean pesanan antar perangkat kasir secara real-time tanpa jeda, menjaga konsistensi stok saat banyak transaksi berlangsung bersamaan.',
                'featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'E-Commerce Kopi',
                'slug' => 'e-commerce-kopi',
                'description' => 'Toko online untuk produk kopi dengan keranjang belanja, checkout, pembayaran otomatis, dan dashboard admin untuk kelola produk serta pesanan.',
                'category' => 'Web App',
                'year' => 2025,
                'link' => '#',
                'github' => 'https://github.com/',
                'tech_stack' => ['Laravel', 'Vue.js', 'Tailwind CSS', 'MySQL'],
                'highlights' => [
                    'Keranjang belanja & checkout bertahap',
                    'Integrasi pembayaran otomatis',
                    'Dashboard admin kelola produk & pesanan',
                    'Rekomendasi produk berdasarkan riwayat',
                    'Kupon diskon & ongkir dinamis',
                ],
                'challenge' => 'Membangun alur checkout multi-langkah yang stabil sekaligus mengamankan transaksi pembayaran di tengah lonjakan trafik saat campaign flash sale.',
                'featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Aplikasi Presensi Digital',
                'slug' => 'aplikasi-presensi-digital',
                'description' => 'Sistem absensi berbasis web dengan QR code, laporan kehadiran, manajemen karyawan, dan notifikasi otomatis untuk mempermudah administrasi kepegawaian.',
                'category' => 'Web App',
                'year' => 2024,
                'link' => '#',
                'github' => 'https://github.com/',
                'tech_stack' => ['PHP', 'MySQL', 'JavaScript', 'Bootstrap'],
                'highlights' => [
                    'Absensi via QR code sekali pindai',
                    'Laporan kehadiran & rekapan bulanan',
                    'Manajemen data karyawan',
                    'Notifikasi otomatis ke admin',
                    'Filter absen cuti, izin, dan lembur',
                ],
                'challenge' => 'Membedakan QR code unik per karyawan dan mencegah pemindaian ulang (duplikat absen) dalam satu hari kerja tanpa merusak pengalaman pengguna.',
                'featured' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Landing Page Startup',
                'slug' => 'landing-page-startup',
                'description' => 'Landing page modern untuk startup teknologi dengan animasi halus, optimasi SEO, dan kecepatan muat di bawah 1 detik menggunakan teknik lazy loading.',
                'category' => 'Frontend',
                'year' => 2024,
                'link' => '#',
                'github' => 'https://github.com/',
                'tech_stack' => ['HTML', 'Tailwind CSS', 'JavaScript', 'Vite'],
                'highlights' => [
                    'Animasi scroll halus berbasis IntersectionObserver',
                    'SEO meta & Open Graph lengkap',
                    'Lazy loading gambar & code splitting',
                    'Tailwind CSS custom design system',
                ],
                'challenge' => 'Mendapatkan skor Lighthouse 95+ untuk performa sekaligus tetap menghadirkan animasi yang terasa premium tanpa memberatkan halaman pertama.',
                'featured' => false,
                'sort_order' => 4,
            ],
            [
                'title' => 'REST API Manajemen Inventori',
                'slug' => 'rest-api-manajemen-inventori',
                'description' => 'API RESTful untuk manajemen inventori gudang dengan autentikasi token, pagination, validasi, serta dokumentasi otomatis menggunakan Swagger.',
                'category' => 'Backend',
                'year' => 2024,
                'link' => '#',
                'github' => 'https://github.com/',
                'tech_stack' => ['Laravel', 'MySQL', 'REST API', 'Swagger'],
                'highlights' => [
                    'Autentikasi token berbasis Sanctum',
                    'Pagination & filtering canggih',
                    'Validasi input & error handling terpusat',
                    'Dokumentasi API otomatis via Swagger',
                ],
                'challenge' => 'Mendesain relasi data inventori multi-level (gudang-stok-transaksi) agar query tetap cepat saat volume data bertambah signifikan.',
                'featured' => false,
                'sort_order' => 5,
            ],
            [
                'title' => 'Dashboard Analitik',
                'slug' => 'dashboard-analitik',
                'description' => 'Dashboard analitik interaktif dengan visualisasi data penjualan, grafik real-time, serta filter dinamis untuk mendukung pengambilan keputusan bisnis.',
                'category' => 'Web App',
                'year' => 2023,
                'link' => '#',
                'github' => 'https://github.com/',
                'tech_stack' => ['Vue.js', 'Chart.js', 'Laravel', 'Tailwind CSS'],
                'highlights' => [
                    'Visualisasi data penjualan interaktif',
                    'Grafik real-time dengan WebSocket',
                    'Filter dinamis per periode & produk',
                    'Ekspor laporan CSV/PDF',
                ],
                'challenge' => 'Mengagregasi ribuan transaksi menjadi ringkasan grafik yang tetap real-time tanpa membebani server saat banyak pengguna melihat dashboard bersamaan.',
                'featured' => false,
                'sort_order' => 6,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
