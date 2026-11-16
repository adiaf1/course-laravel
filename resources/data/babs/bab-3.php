<?php

return [
    'number' => 3,
    'range' => 'Hari 11 – Hari 14',
    'title' => 'Validasi Input, Session Notifikasi, Hapus Data & Upload File',
    'focus' => 'Validasi, Notifikasi & File: Form Validation, Flash Message Session, Delete Record & Upload Lampiran',
    'days' => [
        [
            'day' => 11,
            'title' => 'Validasi Input Form',
            'summary' => 'Validasi sisi server dengan $request->validate(), pesan kustom Bahasa Indonesia, dan blok $errors.',
            'objectives' => [
                'Menerapkan aturan validasi sisi server menggunakan $request->validate().',
                'Menampilkan daftar pesan error validasi menggunakan variabel global $errors di Blade.',
                'Membuat pesan error kustom berbahasa Indonesia.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1, 2 & 5',
                    'title' => 'Menambahkan Validasi di Controller',
                    'description' => 'Tambahkan validasi pada method store() dan update() di CatatanController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
$validated = $request->validate([
    'judul' => 'required|max:255',
    'isi'   => 'required',
], [
    'judul.required' => 'Judul wajib diisi.',
    'judul.max'      => 'Judul maksimal 255 karakter.',
    'isi.required'   => 'Isi catatan tidak boleh kosong.',
]);
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3',
                    'title' => 'Menampilkan Pesan Error di Form Blade',
                    'description' => 'Tampilkan blok pesan error di bagian atas form pada create.blade.php dan edit.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/create.blade.php & edit.blade.php',
                            'code' => <<<'CODE'
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Terapkan validasi pada KontakController (nama: required|max:100, email: required|email, pesan: required) serta tampilkan blok $errors di view kontak.',
                "Jangan pernah hanya mengandalkan atribut 'required' di HTML; validasi server di Laravel mutlak diperlukan.",
            ],
        ],
        [
            'day' => 12,
            'title' => 'Flash Message & Session untuk Notifikasi',
            'summary' => 'Memusatkan alert flash session sukses/error di layouts/app.blade.php dan mengaktifkan Bootstrap JS.',
            'objectives' => [
                'Memahami mekanisme flash session sekali tampil di Laravel.',
                'Memusatkan komponen alert notifikasi sukses dan error di layout utama.',
                'Mengaktifkan Bootstrap Bundle JS agar tombol tutup (dismiss) alert berfungsi.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menambahkan Alert Global di Layout Utama',
                    'description' => 'Tambahkan blok alert global tepat di dalam <div class="container my-4"> pada resources/views/layouts/app.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/layouts/app.blade.php',
                            'code' => <<<'CODE'
@if(session('sukses'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('sukses') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 4 & 5',
                    'title' => 'Menambahkan Bootstrap Bundle JS',
                    'description' => 'Tambahkan script Bootstrap JS sebelum tag penutup </body> di layouts/app.blade.php, lalu hapus duplikasi @if(session(\'sukses\')) pada view lama:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/layouts/app.blade.php',
                            'code' => <<<'CODE'
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Hapus seluruh kode alert flash message yang berulang di masing-masing halaman index karena sudah ditangani secara global di layouts/app.blade.php.',
                "Data yang dikirim lewat ->with('sukses', '...') otomatis dihapus dari session setelah halaman dimuat satu kali.",
            ],
        ],
        [
            'day' => 13,
            'title' => 'Menghapus Data dari Database',
            'summary' => 'Membuat route DELETE, method destroy($id), dan form tombol hapus dengan @method(\'DELETE\').',
            'objectives' => [
                'Mendefinisikan route HTTP DELETE untuk penghapusan data.',
                "Menggunakan method spoofing @method('DELETE') dan token @csrf pada form hapus.",
                'Menambahkan dialog konfirmasi sebelum mengeksekusi penghapusan.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 2',
                    'title' => 'Route DELETE & Method destroy()',
                    'description' => 'Daftarkan route DELETE di routes/web.php dan buat method destroy($id) di CatatanController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php',
                            'code' => <<<'CODE'
Route::delete('/catatan/{id}', [CatatanController::class, 'destroy']);
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
public function destroy($id)
{
    $catatan = Catatan::findOrFail($id);
    $catatan->delete();

    return redirect('/catatan')->with('sukses', 'Catatan berhasil dihapus.');
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3',
                    'title' => 'Menambahkan Tombol Hapus di View',
                    'description' => 'Tambahkan form tombol hapus di halaman daftar (index.blade.php) dan halaman detail (show.blade.php):',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/index.blade.php & show.blade.php',
                            'code' => <<<'CODE'
<form action="/catatan/{{ $item->id }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger"
            onclick="return confirm('Yakin hapus catatan ini?')">
        Hapus
    </button>
</form>
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Tambahkan fitur hapus untuk modul Kontak (route DELETE /kontak/{id}, method destroy(), dan tombol hapus di daftar serta detail).',
                "Penghapusan data wajib menggunakan metode POST + @method('DELETE'), jangan pernah menggunakan link GET biasa.",
            ],
        ],
        [
            'day' => 14,
            'title' => 'Upload File Sederhana',
            'summary' => 'Menambah kolom lampiran, menangani upload file dengan disk public Laravel 13, dan php artisan storage:link.',
            'objectives' => [
                'Menambahkan kolom lampiran pada tabel catatan melalui migrasi baru.',
                'Menangani upload file dengan enctype="multipart/form-data" dan storeAs() pada disk public.',
                'Membuat symbolic link storage publik dengan php artisan storage:link.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Migrasi Penambahan Kolom Lampiran',
                    'description' => 'Buat migrasi penambahan kolom lampiran dan jalankan php artisan migrate:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:migration add_lampiran_to_catatan_table --table=catatan
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'database/migrations/xxxx_add_lampiran_to_catatan_table.php',
                            'code' => <<<'CODE'
public function up(): void
{
    Schema::table('catatan', function (Blueprint $table) {
        $table->string('lampiran')->nullable()->after('isi');
    });
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2 & 3',
                    'title' => 'Update Model & Form Upload',
                    'description' => "Daftarkan 'lampiran' pada properti \$fillable di Model Catatan, lalu tambahkan enctype=\"multipart/form-data\" serta input file pada create.blade.php dan edit.blade.php:",
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Models/Catatan.php',
                            'code' => <<<'CODE'
protected $fillable = ['judul', 'isi', 'lampiran'];
CODE,
                        ],
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/create.blade.php',
                            'code' => <<<'CODE'
<form method="POST" action="/catatan" enctype="multipart/form-data">
    @csrf
    <!-- ... input judul & isi ... -->
    <div class="mb-3">
        <label for="lampiran" class="form-label">Lampiran (opsional)</label>
        <input type="file" name="lampiran" id="lampiran" class="form-control">
        <div class="form-text">Format: PDF, gambar, atau teks. Maks 2MB.</div>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Catatan</button>
</form>
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 4',
                    'title' => 'Menyimpan & Menghapus File di Controller (Laravel 13)',
                    'description' => "Perbarui method store() dan update() di CatatanController.php. Pada Laravel 13, gunakan disk 'public' secara eksplisit agar file tersimpan di storage/app/public/lampiran dan bisa diakses lewat symlink public/storage:",
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
use Illuminate\Support\Facades\Storage;

$request->validate([
    'judul'    => 'required|max:255',
    'isi'      => 'required',
    'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,txt|max:2048',
]);

$data = $request->only(['judul', 'isi']);

if ($request->hasFile('lampiran')) {
    // Saat update: hapus file lama jika ada
    if (isset($catatan) && $catatan->lampiran) {
        Storage::disk('public')->delete('lampiran/' . $catatan->lampiran);
    }

    $file = $request->file('lampiran');
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->storeAs('lampiran', $filename, 'public');
    $data['lampiran'] = $filename;
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 5 & 6',
                    'title' => 'Symlink Storage & Preview Lampiran',
                    'description' => 'Buat symlink storage publik dan tampilkan preview gambar/tautan di show.blade.php:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan storage:link
CODE,
                        ],
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/show.blade.php',
                            'code' => <<<'CODE'
@if($catatan->lampiran)
    <hr>
    <p class="mb-0"><strong>Lampiran:</strong></p>
    @php
        $ext = strtolower(pathinfo($catatan->lampiran, PATHINFO_EXTENSION));
    @endphp
    @if(in_array($ext, ['jpg', 'jpeg', 'png']))
        <img src="{{ asset('storage/lampiran/' . $catatan->lampiran) }}"
             alt="Lampiran" class="img-fluid rounded mt-2">
    @else
        <a href="{{ asset('storage/lampiran/' . $catatan->lampiran) }}"
           target="_blank" class="btn btn-sm btn-outline-info mt-2">
            Lihat Lampiran
        </a>
    @endif
@endif
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Uji validasi file dengan mengunggah file > 2MB atau ekstensi terlarang (.exe/.php) untuk memastikan sistem menolaknya.',
                'Penambahan prefix time() pada nama file mencegah bentrokan nama apabila user mengunggah file dengan nama yang sama.',
            ],
        ],
    ],
];
