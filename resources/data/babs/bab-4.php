<?php

return [
    'number' => 4,
    'range' => 'Hari 15 – Hari 19',
    'title' => 'Autentikasi Laravel Breeze, Proteksi Route & Fitur Arsip',
    'focus' => 'Autentikasi & Manajemen Status: Laravel Breeze, Middleware Auth, Kepemilikan Data (user_id) & Fitur Arsip',
    'days' => [
        [
            'day' => 15,
            'title' => 'Instalasi Laravel Breeze – Setup Autentikasi Login & Register',
            'summary' => 'Menginstal autentikasi Blade, migrasi tabel users, dan mengatur redirect setelah login ke /catatan.',
            'objectives' => [
                'Menginstal starter kit autentikasi Blade di Laravel 13.',
                'Menjalankan migrasi tabel users dan password_reset_tokens.',
                'Menyesuaikan halaman login/register dengan Bootstrap 5 serta arah redirect setelah login.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 2',
                    'title' => 'Instalasi Laravel Breeze',
                    'description' => 'Instal paket Laravel Breeze melalui Composer dan jalankan installer Blade (catatan: pada proyek baru Laravel 13, Anda juga bisa memilih starter kit resmi saat menjalankan laravel new):',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
composer require laravel/breeze --dev
php artisan breeze:install blade
php artisan migrate
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 5',
                    'title' => 'Mengubah Arah Redirect Setelah Login',
                    'description' => 'Ubah arah redirect default setelah login dari /dashboard ke /catatan pada app/Http/Controllers/Auth/AuthenticatedSessionController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/Auth/AuthenticatedSessionController.php',
                            'code' => <<<'CODE'
return redirect()->intended('/catatan');
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'text',
                    'heading' => 'Langkah 7 & 8 — Penyesuaian Tampilan Bootstrap 5',
                    'paragraphs' => [
                        'Sesuaikan tampilan resources/views/auth/login.blade.php dan resources/views/auth/register.blade.php menggunakan komponen card Bootstrap 5 CDN agar seragam dengan tema aplikasi.',
                    ],
                ],
            ],
            'tasks' => [
                'Daftarkan akun pengguna baru melalui /register, pastikan otomatis login dan diarahkan ke /catatan.',
                "Cek tabel 'users' di database: Laravel otomatis mengenkripsi password menggunakan Bcrypt/Argon2.",
            ],
        ],
        [
            'day' => 16,
            'title' => 'Melindungi Route dengan Middleware Auth',
            'summary' => 'Membungkus route catatan dengan middleware auth serta navigasi dinamis @auth / @else.',
            'objectives' => [
                'Memahami cara kerja Middleware sebagai filter sebelum request masuk ke Controller.',
                "Membungkus seluruh route catatan dengan Route::middleware(['auth'])->group(...).",
                'Menampilkan menu dinamis berdasarkan status login menggunakan direktif @auth dan @else.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 5',
                    'title' => 'Mengelompokkan Route ke Middleware Auth',
                    'description' => 'Kelompokkan seluruh route CRUD catatan di routes/web.php ke dalam middleware auth:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php',
                            'code' => <<<'CODE'
// Halaman publik
Route::get('/', [BerandaController::class, 'index']);
Route::get('/halo', [HaloController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);

// Halaman terlindungi (wajib login)
Route::middleware(['auth'])->group(function () {
    Route::get('/catatan', [CatatanController::class, 'index']);
    Route::get('/catatan/tambah', [CatatanController::class, 'create']);
    Route::post('/catatan', [CatatanController::class, 'store']);
    Route::get('/catatan/{id}', [CatatanController::class, 'show']);
    Route::get('/catatan/{id}/edit', [CatatanController::class, 'edit']);
    Route::put('/catatan/{id}', [CatatanController::class, 'update']);
    Route::delete('/catatan/{id}', [CatatanController::class, 'destroy']);
});
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3',
                    'title' => 'Navigasi Status Pengguna & Logout di Navbar',
                    'description' => 'Tambahkan navigasi status pengguna dan tombol Logout berproteksi @csrf di navbar layouts/app.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/layouts/app.blade.php',
                            'code' => <<<'CODE'
@auth
    <span class="navbar-text text-light me-3">Halo, {{ Auth::user()->name }}!</span>
    <form method="POST" action="{{ route('logout') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
    </form>
@else
    <a class="nav-link" href="{{ route('login') }}">Login</a>
    <a class="nav-link" href="{{ route('register') }}">Register</a>
@endauth
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Uji akses ke http://localhost:8000/catatan dalam kondisi logout; sistem harus otomatis mengarahkan ke /login.',
                'Halaman publik seperti /, /halo, dan /about tetap dapat diakses tanpa login.',
            ],
        ],
        [
            'day' => 17,
            'title' => 'Mengaitkan Data ke User – Catatan Pribadi per Pengguna',
            'summary' => 'Menambahkan foreign key user_id, relasi belongsTo(User::class), dan isolasi query per auth()->id().',
            'objectives' => [
                "Menambahkan foreign key user_id ke tabel catatan dengan onDelete('cascade').",
                'Mendefinisikan relasi Eloquent belongsTo(User::class) pada Model Catatan.',
                'Membatasi seluruh query CRUD berdasarkan user yang sedang login (auth()->id()).',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Migrasi Foreign Key user_id',
                    'description' => 'Buat migrasi kolom user_id dan jalankan php artisan migrate:fresh:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:migration add_user_id_to_catatan_table --table=catatan
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'database/migrations/xxxx_add_user_id_to_catatan_table.php',
                            'code' => <<<'CODE'
public function up(): void
{
    Schema::table('catatan', function (Blueprint $table) {
        $table->foreignId('user_id')
            ->after('id')
            ->constrained('users')
            ->onDelete('cascade');
    });
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menambahkan Relasi di Model Catatan',
                    'description' => "Perbarui app/Models/Catatan.php dengan menambahkan 'user_id' ke \$fillable dan relasi user():",
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Models/Catatan.php',
                            'code' => <<<'CODE'
protected $fillable = ['judul', 'isi', 'lampiran', 'user_id'];

public function user()
{
    return $this->belongsTo(User::class);
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3 & 4',
                    'title' => 'Membatasi Query Berdasarkan auth()->id()',
                    'description' => 'Perbarui seluruh method di CatatanController.php agar menyimpan dan memfilter berdasarkan auth()->id():',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
// Pada method index():
$catatan = Catatan::where('user_id', auth()->id())->get();

// Pada method store():
$data['user_id'] = auth()->id();

// Pada method show(), edit(), update(), destroy():
$catatan = Catatan::where('id', $id)
    ->where('user_id', auth()->id())
    ->firstOrFail();
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Uji dengan dua akun berbeda (User A dan User B). Pastikan User B mendapat error 404 saat mencoba mengakses URL /catatan/{id} milik User A.',
                'Selalu validasi kepemilikan data di sisi query server, jangan pernah mempercayai parameter ID dari URL semata.',
            ],
        ],
        [
            'day' => 18,
            'title' => 'Menambahkan Status Arsip pada Catatan',
            'summary' => 'Menambah kolom boolean is_archived, memfilter catatan aktif, serta aksi archive dan unarchive.',
            'objectives' => [
                'Menambahkan kolom boolean is_archived (default false) pada tabel catatan.',
                'Memfilter halaman daftar utama agar hanya menampilkan catatan aktif (is_archived = false).',
                'Membuat aksi arsipkan (archive) dan pulihkan (unarchive) tanpa menghapus data.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 2',
                    'title' => 'Migrasi Kolom is_archived & Update Model',
                    'description' => "Buat migrasi penambahan kolom is_archived, jalankan migrasi, lalu tambahkan 'is_archived' ke \$fillable di Model Catatan:",
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:migration add_is_archived_to_catatan_table --table=catatan
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'database/migrations/xxxx_add_is_archived_to_catatan_table.php',
                            'code' => <<<'CODE'
public function up(): void
{
    Schema::table('catatan', function (Blueprint $table) {
        $table->boolean('is_archived')->default(false)->after('user_id');
    });
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3, 5 & 6',
                    'title' => 'Route & Method archive() / unarchive()',
                    'description' => 'Tambahkan route dan method archive() / unarchive() di CatatanController.php, serta tombol kondisional Arsipkan / Batalkan Arsip pada show.blade.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php (di dalam middleware auth)',
                            'code' => <<<'CODE'
Route::post('/catatan/{id}/archive', [CatatanController::class, 'archive']);
Route::post('/catatan/{id}/unarchive', [CatatanController::class, 'unarchive']);
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
public function archive($id)
{
    $catatan = Catatan::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $catatan->is_archived = true;
    $catatan->save();

    return redirect('/catatan')->with('sukses', 'Catatan telah diarsipkan.');
}

public function unarchive($id)
{
    $catatan = Catatan::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $catatan->is_archived = false;
    $catatan->save();

    return redirect('/catatan')->with('sukses', 'Catatan dipulihkan dari arsip.');
}
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                "Pastikan method index() menambahkan filter ->where('is_archived', false) sehingga catatan yang diarsipkan tersembunyi dari daftar utama.",
                'Fitur arsip lebih aman dibanding penghapusan permanen karena data tetap tersimpan di database.',
            ],
        ],
        [
            'day' => 19,
            'title' => 'Membuat Halaman Arsip Terpisah',
            'summary' => 'Membuat halaman khusus /catatan/arsip dengan indikator jumlah arsip dan tombol Pulihkan.',
            'objectives' => [
                'Membuat halaman khusus /catatan/arsip untuk mengelola catatan yang diarsipkan.',
                'Menampilkan indikator jumlah catatan arsip pada tombol navigasi.',
                'Memberikan pembeda visual (border-secondary) antara catatan aktif dan catatan arsip.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 2',
                    'title' => 'Route & Method arsip()',
                    'description' => 'Tambahkan route GET /catatan/arsip (letakkan di atas route parameter /catatan/{id}) dan method arsip() di CatatanController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php',
                            'code' => <<<'CODE'
Route::get('/catatan/arsip', [CatatanController::class, 'arsip']);
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
public function arsip()
{
    $catatan = Catatan::where('user_id', auth()->id())
        ->where('is_archived', true)
        ->get();

    return view('catatan.arsip', compact('catatan'));
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'text',
                    'heading' => 'Langkah 3 & 4 — View resources/views/catatan/arsip.blade.php',
                    'paragraphs' => [
                        'Buat view resources/views/catatan/arsip.blade.php dengan kartu border-secondary serta tombol Pulihkan dan Hapus Permanen, lalu tambahkan tombol navigasi Arsip di halaman catatan/index.blade.php.',
                    ],
                ],
            ],
            'tasks' => [
                'Pastikan halaman /catatan/arsip hanya menampilkan catatan arsip milik user yang sedang login.',
                'Hapus data dari halaman arsip bersifat permanen, sedangkan tombol Pulihkan mengembalikan status is_archived menjadi false.',
            ],
        ],
    ],
];
