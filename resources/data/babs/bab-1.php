<?php

return [
    'number' => 1,
    'range' => 'Hari 1 – Hari 5',
    'title' => 'Fondasi Dasar Laravel, Routing, Blade & Controller',
    'focus' => 'Fondasi Laravel: Instalasi, Routing, Blade Layout, Controller & Passing Data',
    'days' => [
        [
            'day' => 1,
            'title' => 'Mengenal Laravel & Menyiapkan Lingkungan Pengembangan',
            'summary' => 'Instalasi Laravel 13, menjalankan server lokal, dan mengenal struktur folder proyek.',
            'objectives' => [
                'Memahami apa itu framework Laravel dan keunggulannya.',
                'Menginstal Laravel 13 di komputer lokal.',
                'Menjalankan server pengembangan lokal Laravel.',
                'Mengenal struktur folder dasar proyek Laravel.',
            ],
            'blocks' => [
                [
                    'type' => 'text',
                    'heading' => 'Apa Itu Laravel?',
                    'paragraphs' => [
                        'Laravel adalah framework PHP modern yang dirancang untuk membangun aplikasi web dengan cepat, aman, dan mudah dipelihara. Laravel menyediakan fitur lengkap seperti routing yang ekspresif, sistem templating Blade, ORM (Eloquent) untuk database, serta autentikasi bawaan.',
                    ],
                ],
                [
                    'type' => 'list',
                    'heading' => 'Persyaratan Sistem (Laravel 13)',
                    'items' => [
                        'PHP versi 8.3.x atau lebih baru (cek melalui terminal dengan perintah: php -v)',
                        'Composer (package manager resmi untuk PHP)',
                        'Git (opsional namun sangat disarankan untuk version control)',
                        'Database (MySQL, MariaDB, atau SQLite — Laravel 13 menggunakan SQLite secara default)',
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Membuat Proyek Laravel 13 Baru',
                    'description' => 'Buka terminal (CMD/PowerShell/Bash), lalu jalankan salah satu perintah berikut untuk membuat proyek baru:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
composer create-project laravel/laravel les-laravel

# Atau jika sudah menginstal Laravel Installer:
laravel new les-laravel
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2 & 3',
                    'title' => 'Masuk ke Folder Proyek & Jalankan Server Lokal',
                    'description' => 'Masuk ke direktori proyek dan jalankan server pengembangan bawaan Laravel. Buka browser dan kunjungi http://localhost:8000. Untuk menghentikan server, tekan Ctrl + C di terminal.',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
cd les-laravel
php artisan serve

# Jika port 8000 sedang dipakai, gunakan port lain:
php artisan serve --port=8080
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'table',
                    'heading' => 'Struktur Folder Penting',
                    'headers' => ['Folder / File', 'Kegunaan Utama'],
                    'rows' => [
                        ['app/', 'Tempat Model, Controller, dan logika bisnis aplikasi'],
                        ['routes/', 'File pendefinisian rute URL (web.php, console.php)'],
                        ['resources/views/', 'File tampilan antarmuka pengguna menggunakan Blade (.blade.php)'],
                        ['public/', 'Root web publik berisi aset statis (CSS, JS, gambar) dan index.php'],
                        ['.env', 'File konfigurasi lingkungan sensitif (koneksi database, APP_KEY, dll)'],
                    ],
                ],
            ],
            'tasks' => [
                "Pastikan PHP 8.3+ dan Composer terinstal, lalu buat proyek 'les-laravel' (Laravel 13) dan jalankan php artisan serve.",
                "Uji coba ubah teks pada resources/views/welcome.blade.php menjadi 'Belajar Laravel Hari 1'.",
                'Jangan pernah membagikan file .env ke publik atau GitHub karena berisi kredensial rahasia.',
            ],
        ],
        [
            'day' => 2,
            'title' => 'Routing Dasar & Menampilkan Halaman Sederhana',
            'summary' => 'Membuat route di routes/web.php dan menampilkan view Blade dengan Bootstrap 5.',
            'objectives' => [
                'Memahami konsep routing di Laravel melalui file routes/web.php.',
                'Membuat route yang menampilkan teks langsung maupun view Blade.',
                'Menggunakan styling Bootstrap 5 CDN pada halaman view pertama.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 2',
                    'title' => 'Menambahkan Route Teks Langsung',
                    'description' => 'Buka file routes/web.php dan tambahkan route baru yang mengembalikan teks langsung:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php',
                            'code' => <<<'CODE'
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/halo', function () {
    return 'Halo, Dunia! Hari ke-2 belajar Laravel!';
});
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3',
                    'title' => 'Membuat File View Blade dengan Bootstrap 5',
                    'description' => 'Buat file view baru di resources/views/halo.blade.php menggunakan Bootstrap 5 CDN:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/halo.blade.php',
                            'code' => <<<'CODE'
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Halo Laravel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card text-center">
            <div class="card-body">
                <h2 class="card-title">Halo, Dunia!</h2>
                <p class="card-text">Hari ke-2 belajar Laravel — ini halaman view pertamaku!</p>
                <a href="/" class="btn btn-primary">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 4',
                    'title' => 'Menghubungkan Route ke View',
                    'description' => 'Ubah route /halo di routes/web.php agar memanggil view tersebut:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php',
                            'code' => <<<'CODE'
Route::get('/halo', function () {
    return view('halo');
});
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Buat route /about dan file resources/views/about.blade.php dengan desain Bootstrap, lalu tambahkan tautannya di welcome.blade.php.',
                "Nama view di fungsi view('nama') otomatis merujuk ke file nama.blade.php tanpa perlu menulis ekstensi.",
            ],
        ],
        [
            'day' => 3,
            'title' => 'Blade Layout – Membuat Template Halaman yang Konsisten',
            'summary' => 'Menerapkan template inheritance Blade dengan @yield, @extends, dan @section.',
            'objectives' => [
                'Memahami konsep pewarisan layout (template inheritance) di Blade.',
                'Membuat file layout utama menggunakan @yield.',
                'Menerapkan @extends dan @section pada seluruh halaman aplikasi.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Membuat Master Layout',
                    'description' => 'Buat folder dan file master layout di resources/views/layouts/app.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/layouts/app.blade.php',
                            'code' => <<<'CODE'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Aplikasi Laravel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Les Laravel</a>
            <div class="navbar-nav">
                <a class="nav-link" href="/">Beranda</a>
                <a class="nav-link" href="/halo">Halo</a>
                <a class="nav-link" href="/about">Tentang</a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        @yield('content')
    </div>

    <footer class="bg-dark text-light text-center py-3 mt-5">
        <small>&copy; {{ date('Y') }} - Belajar Laravel 30 Hari</small>
    </footer>
</body>
</html>
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2, 3 & 4',
                    'title' => 'Menerapkan Layout ke Halaman View',
                    'description' => 'Terapkan layout pada resources/views/welcome.blade.php (serta halo.blade.php dan about.blade.php):',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/welcome.blade.php',
                            'code' => <<<'CODE'
@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Selamat Datang!</div>
            <div class="card-body text-center">
                <h3>Hari ke-3: Blade Layout</h3>
                <p class="lead">Sekarang semua halaman pakai template yang sama.</p>
                <a href="/halo" class="btn btn-success">Coba Halaman Halo</a>
                <a href="/about" class="btn btn-outline-secondary">Halaman About</a>
            </div>
        </div>
    </div>
</div>
@endsection
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                "Pastikan halaman /, /halo, dan /about semuanya menggunakan @extends('layouts.app').",
                'Tambahkan halaman baru /kontak (route, view resources/views/kontak.blade.php, dan menu di navbar).',
            ],
        ],
        [
            'day' => 4,
            'title' => 'Mengenal Controller – Memindahkan Logika dari Route ke Controller',
            'summary' => 'Membuat Controller dengan Artisan CLI dan menghubungkan route ke method Controller.',
            'objectives' => [
                'Memahami peran Controller dalam arsitektur MVC Laravel.',
                'Membuat Controller menggunakan Artisan CLI.',
                'Menghubungkan route ke method di dalam Controller.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 4',
                    'title' => 'Membuat Controller via Artisan CLI',
                    'description' => 'Buat BerandaController, HaloController, dan AboutController melalui terminal:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:controller BerandaController
php artisan make:controller HaloController
php artisan make:controller AboutController
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Mengisi Method di Controller',
                    'description' => 'Isi masing-masing Controller (contoh app/Http/Controllers/BerandaController.php):',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/BerandaController.php',
                            'code' => <<<'CODE'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3 & 5',
                    'title' => 'Memperbarui File routes/web.php',
                    'description' => 'Perbarui file routes/web.php agar tidak lagi menggunakan closure function:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php',
                            'code' => <<<'CODE'
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\HaloController;
use App\Http\Controllers\AboutController;

Route::get('/', [BerandaController::class, 'index']);
Route::get('/halo', [HaloController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                "Buat KontakController dengan method index() yang mengembalikan view('kontak') dan daftarkan route /kontak.",
                'Hapus seluruh route berbasis closure (function() { ... }) dan pastikan semua route mengarah ke Controller.',
            ],
        ],
        [
            'day' => 5,
            'title' => 'Mengirim Data dari Controller ke View',
            'summary' => 'Passing variabel dan array dengan compact() serta perulangan @foreach di Blade.',
            'objectives' => [
                'Mengirimkan variabel tunggal maupun array dari Controller ke Blade.',
                'Menggunakan fungsi compact() dan sintaks echo Blade {{ }}.',
                'Melakukan perulangan data array di view menggunakan direktif @foreach.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1, 3 & 5',
                    'title' => 'Mengirim Data Dinamis dari Controller',
                    'description' => 'Kirim data dinamis dan array menu dari app/Http/Controllers/BerandaController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/BerandaController.php',
                            'code' => <<<'CODE'
public function index()
{
    $judul = 'Selamat Datang di Les Laravel';
    $hari  = 'Hari ke-5';
    $nama  = 'Murid Laravel';
    $menu  = ['Beranda', 'Halo', 'Tentang', 'Kontak'];

    return view('welcome', compact('judul', 'hari', 'nama', 'menu'));
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2 & 5',
                    'title' => 'Menampilkan Variabel & Perulangan di Blade',
                    'description' => 'Tampilkan variabel dan perulangan array di resources/views/welcome.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/welcome.blade.php',
                            'code' => <<<'CODE'
@extends('layouts.app')

@section('title', $judul)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ $judul }}</div>
            <div class="card-body text-center">
                <h3>{{ $hari }}</h3>
                <p class="lead">Halo, {{ $nama }}!</p>
                <p>Hari ini tanggal: {{ date('d F Y') }}</p>
            </div>
        </div>

        <h5 class="mt-4">Daftar Menu Latihan:</h5>
        <ul class="list-group">
            @foreach($menu as $item)
                <li class="list-group-item">{{ $item }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                "Kirimkan data dinamis 'judul_halaman' dan 'deskripsi' ke halaman About, serta array \$kontak (email & telepon) ke halaman Kontak.",
                'Jangan pernah mengirimkan data sensitif seperti password atau API key ke dalam view.',
            ],
        ],
    ],
];
