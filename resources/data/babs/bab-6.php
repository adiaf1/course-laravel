<?php

return [
    'number' => 6,
    'range' => 'Hari 27 – Hari 30',
    'title' => 'Ekspor/Impor Data CSV & Deployment Portofolio GitHub',
    'focus' => 'Ekspor/Impor Data & Portofolio GitHub: Export CSV, Import CSV, Persiapan .gitignore/README & Upload ke GitHub',
    'days' => [
        [
            'day' => 27,
            'title' => 'Ekspor Data ke File CSV (untuk Excel)',
            'summary' => 'Membuat endpoint ekspor catatan aktif ke CSV menggunakan fputcsv() dan response()->stream().',
            'objectives' => [
                'Membuat endpoint ekspor data catatan aktif ke format CSV menggunakan fungsi PHP native fputcsv().',
                'Mengirimkan file unduhan langsung ke browser melalui stream response.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Menambahkan Route Ekspor CSV',
                    'description' => 'Tambahkan route GET /catatan/export di dalam group middleware auth pada routes/web.php (letakkan di atas route /catatan/{id}):',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php',
                            'code' => <<<'CODE'
Route::get('/catatan/export', [CatatanController::class, 'export']);
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Membuat Method export() di Controller',
                    'description' => 'Tambahkan method export() di app/Http/Controllers/CatatanController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
public function export()
{
    $catatan = Catatan::where('user_id', auth()->id())
        ->where('is_archived', false)
        ->orderBy('created_at', 'desc')
        ->get();

    $filename = 'catatan_aktif_' . date('Y-m-d') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function () use ($catatan) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Judul', 'Isi', 'Tanggal Dibuat']);

        foreach ($catatan as $item) {
            fputcsv($handle, [
                $item->judul,
                $item->isi,
                $item->created_at->format('d F Y H:i'),
            ]);
        }

        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'text',
                    'heading' => 'Langkah 3 — Tombol Ekspor CSV',
                    'paragraphs' => [
                        'Tambahkan tombol Ekspor CSV (<a href="/catatan/export" class="btn btn-outline-success">Ekspor CSV</a>) di sebelah tombol Tambah Baru pada resources/views/catatan/index.blade.php.',
                    ],
                ],
            ],
            'tasks' => [
                'Unduh file CSV dan buka menggunakan Microsoft Excel atau Google Sheets untuk memastikan kolom Judul, Isi, dan Tanggal terbaca rapi.',
                'Pendekatan php://output dengan fputcsv() tidak memerlukan library pihak ketiga tambahan.',
            ],
        ],
        [
            'day' => 28,
            'title' => 'Impor Data dari File CSV',
            'summary' => 'Membuat form unggah CSV, membaca baris dengan fgetcsv(), dan menyimpan catatan ke akun user login.',
            'objectives' => [
                'Membuat halaman form unggah file CSV untuk impor massal catatan.',
                'Membaca isi baris CSV menggunakan fgetcsv() dan menyimpannya ke database milik user yang login.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Menambahkan Route Impor CSV',
                    'description' => 'Tambahkan route GET dan POST /catatan/import di dalam middleware auth pada routes/web.php (di atas route /catatan/{id}):',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/web.php',
                            'code' => <<<'CODE'
Route::get('/catatan/import', [CatatanController::class, 'importForm']);
Route::post('/catatan/import', [CatatanController::class, 'import']);
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'text',
                    'heading' => 'Langkah 3 — Form Unggah CSV',
                    'paragraphs' => [
                        'Buat view form unggah di resources/views/catatan/import.blade.php dengan atribut enctype="multipart/form-data" dan accept=".csv".',
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2 & 4',
                    'title' => 'Menambahkan Method importForm() dan import()',
                    'description' => 'Tambahkan method importForm() dan import(Request $request) di CatatanController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
public function importForm()
{
    return view('catatan.import');
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:csv,txt|max:2048',
    ]);

    $file = $request->file('file');
    $handle = fopen($file->getPathname(), 'r');

    // Lewati baris pertama (header)
    fgetcsv($handle);

    $jumlah = 0;

    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) >= 2) {
            Catatan::create([
                'user_id'     => auth()->id(),
                'judul'       => $row[0],
                'isi'         => $row[1],
                'is_archived' => false,
            ]);
            $jumlah++;
        }
    }

    fclose($handle);

    return redirect('/catatan')->with('sukses', "Berhasil mengimpor {$jumlah} catatan.");
}
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Uji fitur impor menggunakan file CSV hasil ekspor dari Hari 27 dan pastikan seluruh catatan baru masuk ke akun user yang aktif.',
                'Pastikan file CSV disimpan dengan encoding UTF-8 agar karakter tanda baca terbaca sempurna.',
            ],
        ],
        [
            'day' => 29,
            'title' => 'Siapkan Proyek untuk GitHub',
            'summary' => 'Memeriksa .gitignore agar file sensitif aman dan menyusun README.md profesional.',
            'objectives' => [
                'Memastikan file sensitif dan direktori dependensi tercantum dalam .gitignore.',
                'Membuat dokumentasi proyek yang profesional pada file README.md.',
                'Melakukan pengecekan akhir sebelum mengunggah kode ke repositori publik.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 2',
                    'title' => 'Memeriksa File .gitignore',
                    'description' => 'Periksa file .gitignore di root proyek dan pastikan aturan pengecualian berikut aktif:',
                    'codes' => [
                        [
                            'language' => 'text',
                            'filename' => '.gitignore',
                            'code' => <<<'CODE'
.env
/vendor/
/storage/logs/*.log
/storage/debugbar/
/node_modules/
/public/storage
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3',
                    'title' => 'Membuat Dokumentasi README.md',
                    'description' => 'Buat file README.md di root proyek sebagai halaman profil proyek Anda:',
                    'codes' => [
                        [
                            'language' => 'markdown',
                            'filename' => 'README.md',
                            'code' => <<<'CODE'
# Aplikasi Catatan Pribadi (Laravel 30 Hari)

Aplikasi manajemen catatan pribadi dibuat sebagai latihan Laravel selama 30 hari.

## Fitur Utama
- Login & register pengguna (Laravel Breeze)
- Tambah, lihat detail, edit, dan hapus catatan (CRUD)
- Isolasi data per pengguna & fitur Arsip catatan
- Pencarian, pengurutan (Terbaru/Terlama), & pagination
- Ekspor & Impor data format CSV

## Cara Menjalankan
1. Clone repositori ini
2. Jalankan: `composer install`
3. Salin `.env.example` menjadi `.env`, lalu sesuaikan konfigurasi database
4. Jalankan: `php artisan key:generate`
5. Jalankan migrasi: `php artisan migrate`
6. Jalankan server: `php artisan serve`

## Teknologi
Laravel 13 | PHP 8.3 | MySQL | Bootstrap 5
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Jangan pernah melakukan commit terhadap file .env karena berisi password database dan APP_KEY.',
                'Jalankan kembali php artisan serve untuk memastikan seluruh fitur berjalan normal tanpa error.',
            ],
        ],
        [
            'day' => 30,
            'title' => 'Upload ke GitHub',
            'summary' => 'Membuat repositori publik di GitHub, git init, commit, dan push sebagai portofolio profesional.',
            'objectives' => [
                'Membuat repositori publik baru di GitHub.',
                'Menginisialisasi Git lokal dan melakukan push proyek Laravel ke GitHub.',
                'Mempublikasikan tautan repositori sebagai portofolio profesional.',
            ],
            'blocks' => [
                [
                    'type' => 'text',
                    'heading' => 'Langkah 1 & 2 — Membuat Repositori di GitHub',
                    'paragraphs' => [
                        'Buka https://github.com, buat repositori baru bernama les-laravel-catatan dengan visibilitas Public (jangan centang opsi Initialize with a README).',
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3 & 4',
                    'title' => 'Inisialisasi Git & Push ke GitHub',
                    'description' => 'Inisialisasi Git di terminal folder proyek lokal dan kirim seluruh kode ke GitHub:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
git init
git add .
git commit -m "Aplikasi catatan pribadi selesai 30 hari"
git remote add origin https://github.com/namakamu/les-laravel-catatan.git
git branch -M main
git push -u origin main
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'text',
                    'heading' => 'Langkah 5 & 6 — Verifikasi di GitHub',
                    'paragraphs' => [
                        'Buka halaman repositori di browser untuk verifikasi: pastikan file README.md tampil di halaman utama, struktur folder Laravel lengkap, serta folder vendor/ dan file .env tidak ikut terunggah.',
                    ],
                ],
            ],
            'tasks' => [
                'Tautan repositori GitHub siap dicantumkan pada CV, profil LinkedIn, maupun portofolio web pribadi.',
                'Selamat! Seluruh rangkaian kurikulum 30 hari membangun aplikasi Laravel 13 dari nol hingga siap produksi dan portofolio telah selesai.',
            ],
        ],
    ],
];
