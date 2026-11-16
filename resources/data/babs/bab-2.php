<?php

return [
    'number' => 2,
    'range' => 'Hari 6 – Hari 10',
    'title' => 'Database, Migrasi, Model Eloquent & CRUD Dasar',
    'focus' => 'Database & CRUD Dasar: Konfigurasi MySQL, Migrasi, Eloquent Model, Create, Read, Detail & Update',
    'days' => [
        [
            'day' => 6,
            'title' => 'Setup Database & Membuat Migrasi Pertama',
            'summary' => 'Konfigurasi koneksi MySQL di .env, membuat skema migrasi tabel catatan, dan menjalankan migrate.',
            'objectives' => [
                'Menghubungkan aplikasi Laravel ke database MySQL melalui file .env.',
                'Membuat skema tabel database secara terprogram dengan Migration.',
                'Menjalankan migrasi menggunakan Artisan CLI.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1, 2 & 3',
                    'title' => 'Konfigurasi Database MySQL di .env',
                    'description' => 'Buat database baru bernama les_laravel di phpMyAdmin/MySQL. Karena Laravel 13 menggunakan SQLite secara default, sesuaikan (dan hapus komentar # pada baris DB_*) di file .env:',
                    'codes' => [
                        [
                            'language' => 'ini',
                            'filename' => '.env',
                            'code' => <<<'CODE'
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=les_laravel
DB_USERNAME=root
DB_PASSWORD=
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 5 & 6',
                    'title' => 'Membuat File Migrasi Tabel Catatan',
                    'description' => 'Buat file migrasi untuk tabel catatan dan definisikan kolom-kolomnya:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:migration create_catatan_table --create=catatan
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'database/migrations/xxxx_create_catatan_table.php',
                            'code' => <<<'CODE'
public function up(): void
{
    Schema::create('catatan', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->text('isi');
        $table->timestamps();
    });
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 7',
                    'title' => 'Menjalankan Migrasi ke Database',
                    'description' => 'Eksekusi migrasi ke dalam database melalui terminal:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan migrate
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                "Buat migrasi untuk tabel 'kontak' dengan kolom id, nama (string), email (string), pesan (text), dan timestamps(), lalu jalankan php artisan migrate.",
                'Verifikasi di phpMyAdmin bahwa tabel catatan dan kontak sudah terbentuk.',
            ],
        ],
        [
            'day' => 7,
            'title' => 'Mengenal Model Eloquent & Menyimpan Data ke Database',
            'summary' => 'Membuat Model Catatan, mengatur $fillable, serta membuat form dan logika store().',
            'objectives' => [
                'Memahami konsep Eloquent ORM dan aturan penamaan Model.',
                'Mengatur properti $table dan $fillable untuk keamanan Mass Assignment.',
                'Membuat form input dan menyimpan record baru ke database.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 2',
                    'title' => 'Membuat Model Catatan',
                    'description' => 'Buat Model Catatan dan atur properti $table serta $fillable di app/Models/Catatan.php:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:model Catatan
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'app/Models/Catatan.php',
                            'code' => <<<'CODE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catatan extends Model
{
    protected $table = 'catatan';

    protected $fillable = ['judul', 'isi'];
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 4',
                    'title' => 'Membuat CatatanController',
                    'description' => 'Buat CatatanController dan tambahkan method index() serta store():',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:controller CatatanController
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catatan;

class CatatanController extends Controller
{
    public function index()
    {
        return view('catatan.index');
    }

    public function store(Request $request)
    {
        Catatan::create($request->only(['judul', 'isi']));

        return redirect('/catatan')->with('sukses', 'Catatan berhasil disimpan!');
    }
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 4c & 4d',
                    'title' => 'Route & Form Input dengan @csrf',
                    'description' => 'Daftarkan route GET dan POST di routes/web.php, lalu buat form input dengan proteksi @csrf di resources/views/catatan/index.blade.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php',
                            'code' => <<<'CODE'
use App\Http\Controllers\CatatanController;

Route::get('/catatan', [CatatanController::class, 'index']);
Route::post('/catatan', [CatatanController::class, 'store']);
CODE,
                        ],
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/index.blade.php',
                            'code' => <<<'CODE'
<form method="POST" action="/catatan">
    @csrf
    <div class="mb-3">
        <label for="judul" class="form-label">Judul</label>
        <input type="text" name="judul" id="judul" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="isi" class="form-label">Isi Catatan</label>
        <textarea name="isi" id="isi" class="form-control" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Catatan</button>
</form>
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Buat Model Kontak ($fillable: nama, email, pesan) beserta form dan method store() di KontakController.',
                'Direktif @csrf wajib disertakan pada setiap form HTML dengan method POST di Laravel.',
            ],
        ],
        [
            'day' => 8,
            'title' => 'Menampilkan Data dari Database',
            'summary' => 'Mengambil data dengan Catatan::all(), memisahkan halaman daftar & tambah, serta Str::limit().',
            'objectives' => [
                'Mengambil seluruh baris data menggunakan Eloquent Catatan::all().',
                'Memisahkan halaman daftar catatan (index.blade.php) dan halaman form tambah (create.blade.php).',
                'Memformat tanggal Carbon dan membatasi panjang teks dengan Str::limit().',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 3',
                    'title' => 'Memperbarui CatatanController',
                    'description' => 'Perbarui CatatanController.php untuk mengambil data dan menampilkan form tambah terpisah:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
public function index()
{
    $catatan = Catatan::all();

    return view('catatan.index', compact('catatan'));
}

public function create()
{
    return view('catatan.create');
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3c',
                    'title' => 'Memisahkan Route Daftar dan Form Tambah',
                    'description' => 'Perbarui routes/web.php untuk memisahkan route daftar dan form tambah:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php',
                            'code' => <<<'CODE'
Route::get('/catatan', [CatatanController::class, 'index']);
Route::get('/catatan/tambah', [CatatanController::class, 'create']);
Route::post('/catatan', [CatatanController::class, 'store']);
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menampilkan Daftar Kartu Catatan',
                    'description' => 'Tampilkan daftar kartu catatan di resources/views/catatan/index.blade.php (pindahkan form tambah ke resources/views/catatan/create.blade.php):',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/index.blade.php',
                            'code' => <<<'CODE'
@extends('layouts.app')

@section('title', 'Daftar Catatan')

@section('content')
<div class="row justify-content-between mb-3">
    <div class="col">
        <h2>Daftar Catatan</h2>
    </div>
    <div class="col-auto">
        <a href="/catatan/tambah" class="btn btn-success">+ Tambah Baru</a>
    </div>
</div>

@if($catatan->count() > 0)
    <div class="row">
        @foreach($catatan as $item)
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        {{ $item->judul }}
                        <small class="text-muted float-end">{{ $item->created_at->format('d M Y') }}</small>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($item->isi, 100) }}</p>
                        <a href="/catatan/{{ $item->id }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                        <a href="/catatan/{{ $item->id }}/edit" class="btn btn-sm btn-outline-warning">Edit</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info">
        Belum ada catatan. <a href="/catatan/tambah">Buat catatan pertama!</a>
    </div>
@endif
@endsection
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Buat halaman daftar kontak (kontak/index.blade.php) beserta method index() di KontakController dan tautannya di navbar.',
                'Kolom created_at otomatis menjadi objek Carbon sehingga dapat diformat langsung di Blade.',
            ],
        ],
        [
            'day' => 9,
            'title' => 'Menampilkan Detail Data (Show Single Record)',
            'summary' => 'Route parameter {id}, Catatan::findOrFail($id), dan rendering teks aman dengan nl2br(e(...)).',
            'objectives' => [
                'Mendefinisikan route dengan parameter dinamis {id}.',
                'Mengambil data tunggal menggunakan Catatan::findOrFail($id) untuk penanganan error 404 otomatis.',
                'Menampilkan teks multi-baris secara aman menggunakan nl2br(e(...)).',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Menambahkan Route Parameter',
                    'description' => 'Tambahkan route parameter di routes/web.php (letakkan di bawah route statis /catatan/tambah agar tidak bentrok):',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php',
                            'code' => <<<'CODE'
Route::get('/catatan/{id}', [CatatanController::class, 'show']);
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menambahkan Method show() di Controller',
                    'description' => 'Tambahkan method show($id) di app/Http/Controllers/CatatanController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
public function show($id)
{
    $catatan = Catatan::findOrFail($id);

    return view('catatan.show', compact('catatan'));
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3',
                    'title' => 'Membuat View Detail Catatan',
                    'description' => 'Buat view detail di resources/views/catatan/show.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/show.blade.php',
                            'code' => <<<'CODE'
@extends('layouts.app')

@section('title', $catatan->judul)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ $catatan->judul }}</span>
                <small class="text-muted">{{ $catatan->created_at->format('d F Y, H:i') }}</small>
            </div>
            <div class="card-body">
                <p class="card-text">{!! nl2br(e($catatan->isi)) !!}</p>
            </div>
            <div class="card-footer text-end">
                <a href="/catatan" class="btn btn-secondary">Kembali</a>
                <a href="/catatan/{{ $catatan->id }}/edit" class="btn btn-warning">Edit</a>
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
                'Buat halaman detail untuk kontak (route /kontak/{id}, method show($id), dan view kontak/show.blade.php).',
                'Fungsi e() mencegah serangan XSS dengan meng-escape tag HTML, sedangkan nl2br() mengubah baris baru menjadi <br>.',
            ],
        ],
        [
            'day' => 10,
            'title' => 'Mengedit Data (Update Record)',
            'summary' => 'Membuat form edit dengan prefill old(), method spoofing @method(\'PUT\'), dan update Eloquent.',
            'objectives' => [
                'Membuat halaman form edit yang terisi otomatis (prefill) dengan data lama.',
                "Menggunakan method spoofing @method('PUT') pada form HTML.",
                'Memperbarui record di database melalui Eloquent.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Menambahkan Route Edit & Update',
                    'description' => 'Tambahkan route edit dan update di routes/web.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php',
                            'code' => <<<'CODE'
Route::get('/catatan/{id}/edit', [CatatanController::class, 'edit']);
Route::put('/catatan/{id}', [CatatanController::class, 'update']);
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2 & 4',
                    'title' => 'Menambahkan Method edit() dan update()',
                    'description' => 'Tambahkan method edit() dan update() di CatatanController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
public function edit($id)
{
    $catatan = Catatan::findOrFail($id);

    return view('catatan.edit', compact('catatan'));
}

public function update(Request $request, $id)
{
    $catatan = Catatan::findOrFail($id);
    $catatan->update($request->only(['judul', 'isi']));

    return redirect("/catatan/{$id}")->with('sukses', 'Catatan berhasil diperbarui!');
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3',
                    'title' => 'Membuat Form Edit Catatan',
                    'description' => 'Buat form edit di resources/views/catatan/edit.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/edit.blade.php',
                            'code' => <<<'CODE'
@extends('layouts.app')

@section('title', 'Edit Catatan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Edit Catatan</div>
            <div class="card-body">
                <form method="POST" action="/catatan/{{ $catatan->id }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" name="judul" id="judul" class="form-control"
                               value="{{ old('judul', $catatan->judul) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi Catatan</label>
                        <textarea name="isi" id="isi" class="form-control" rows="5" required>{{ old('isi', $catatan->isi) }}</textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    </div>

                    <a href="/catatan/{{ $catatan->id }}" class="mt-2 d-block text-center">Kembali</a>
                </form>
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
                'Lengkapi fitur edit & update untuk modul Kontak (route GET /kontak/{id}/edit dan PUT /kontak/{id}).',
                "Helper old('judul', \$catatan->judul) menjaga nilai input user apabila terjadi kegagalan validasi.",
            ],
        ],
    ],
];
