<?php

return [
    'number' => 5,
    'range' => 'Hari 20 – Hari 26',
    'title' => 'Pencarian, Pengurutan, Pagination & Penyempurnaan UX',
    'focus' => 'Pencarian, Pengurutan & UX Lanjutan: Search, Sort, Pagination, Ikon Font Awesome, Format Tanggal, Modal Bootstrap & Counter',
    'days' => [
        [
            'day' => 20,
            'title' => 'Menambahkan Fitur Pencarian (Search)',
            'summary' => 'Form pencarian GET, query LIKE dalam closure agar user_id tetap terjaga, dan tombol Reset.',
            'objectives' => [
                'Membuat form pencarian dengan metode GET agar parameter tersimpan di URL.',
                'Menyaring data pada kolom judul atau isi menggunakan query LIKE dalam closure.',
                'Menyediakan tombol Reset untuk menghapus filter pencarian.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 3',
                    'title' => 'Membuat Form Pencarian',
                    'description' => 'Tambahkan form pencarian di resources/views/catatan/index.blade.php dan arsip.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/index.blade.php',
                            'code' => <<<'CODE'
<form method="GET" action="/catatan" class="mb-4">
    <div class="input-group">
        <input type="text" name="cari" class="form-control"
               placeholder="Cari judul atau isi catatan..." value="{{ request('cari') }}">
        <button class="btn btn-outline-primary" type="submit">Cari</button>
        @if(request('cari'))
            <a href="/catatan" class="btn btn-outline-secondary">X Reset</a>
        @endif
    </div>
</form>
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2 & 4',
                    'title' => 'Filter Pencarian di Controller',
                    'description' => 'Perbarui query pada method index(Request $request) dan arsip(Request $request) di CatatanController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
if ($request->filled('cari')) {
    $keyword = '%' . $request->cari . '%';
    $query->where(function ($q) use ($keyword) {
        $q->where('judul', 'like', $keyword)
          ->orWhere('isi', 'like', $keyword);
    });
}
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Pengelompokan orWhere di dalam closure function($q) wajib dilakukan agar kondisi user_id = auth()->id() tetap berlaku pada kedua kriteria pencarian.',
                'Uji pencarian pada halaman daftar aktif maupun halaman arsip.',
            ],
        ],
        [
            'day' => 21,
            'title' => 'Menambahkan Fitur Pengurutan (Sort)',
            'summary' => 'Dropdown auto-submit Terbaru/Terlama dan menggabungkan parameter urut dengan pencarian.',
            'objectives' => [
                'Menambahkan pilihan urutan catatan berdasarkan waktu pembuatan (Terbaru / Terlama).',
                'Menggabungkan parameter pengurutan (urut) dengan parameter pencarian (cari) secara bersamaan.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 3',
                    'title' => 'Dropdown Pilihan Urutan',
                    'description' => 'Tambahkan dropdown select auto-submit di samping form pencarian pada index.blade.php dan arsip.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/index.blade.php',
                            'code' => <<<'CODE'
<form method="GET" action="/catatan" class="ms-2">
    <input type="hidden" name="cari" value="{{ request('cari') }}">
    <select name="urut" class="form-select" onchange="this.form.submit()">
        <option value="terbaru" {{ request('urut') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
        <option value="terlama" {{ request('urut') == 'terlama' ? 'selected' : '' }}>Terlama</option>
    </select>
</form>
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2 & 4',
                    'title' => 'Logika orderBy di Controller',
                    'description' => 'Terapkan logika orderBy pada method index() dan arsip() di CatatanController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
$urut = $request->get('urut', 'terbaru');
$query->orderBy('created_at', $urut === 'terlama' ? 'asc' : 'desc');
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Input tersembunyi <input type="hidden" name="cari"> memastikan kata kunci pencarian tidak hilang saat user mengubah urutan.',
                'Uji kombinasi pencarian kata kunci sekaligus perubahan urutan dari Terbaru ke Terlama.',
            ],
        ],
        [
            'day' => 22,
            'title' => 'Menambahkan Pagination (Pembagian Halaman)',
            'summary' => 'Memecah daftar catatan dengan paginate(5)->withQueryString() dan Paginator::useBootstrapFive().',
            'objectives' => [
                'Memecah daftar catatan menjadi beberapa halaman menggunakan paginate().',
                'Menampilkan tombol navigasi halaman otomatis dengan {{ $catatan->links() }}.',
                'Mempertahankan parameter query string saat berpindah halaman.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Menggunakan paginate() dan withQueryString()',
                    'description' => 'Ganti pemanggilan ->get() menjadi ->paginate(5)->withQueryString() pada method index() dan arsip() di CatatanController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
$catatan = $query->paginate(5)->withQueryString();
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menampilkan Links & Mengaktifkan Style Bootstrap 5',
                    'description' => 'Tambahkan {{ $catatan->links() }} di bagian bawah @endif pada index.blade.php dan arsip.blade.php. Agar pagination Laravel 13 tampil rapi dengan Bootstrap 5 (bukan Tailwind), daftarkan Paginator::useBootstrapFive() di AppServiceProvider.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/index.blade.php',
                            'code' => <<<'CODE'
{{ $catatan->links() }}
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'app/Providers/AppServiceProvider.php',
                            'code' => <<<'CODE'
use Illuminate\Pagination\Paginator;

public function boot(): void
{
    Paginator::useBootstrapFive();
}
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Buat minimal 7 catatan dan pastikan daftar terbagi menjadi 2 halaman (5 data di halaman 1, sisanya di halaman 2).',
                'Pastikan saat berpindah ke halaman 2, parameter pencarian dan urutan tetap terjaga.',
            ],
        ],
        [
            'day' => 23,
            'title' => 'Menyempurnakan Tampilan & UX',
            'summary' => 'Integrasi ikon Font Awesome 6, waktu relatif Carbon diffForHumans() Bahasa Indonesia, dan isoFormat().',
            'objectives' => [
                'Mengintegrasikan ikon Font Awesome 6 untuk memperjelas tombol aksi.',
                'Menampilkan waktu relatif yang ramah pengguna menggunakan Carbon diffForHumans().',
                'Memperbaiki tata letak header dan footer pada halaman detail catatan.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Menambahkan CDN Font Awesome 6',
                    'description' => 'Tambahkan CDN Font Awesome 6.5.0 di dalam tag <head> pada resources/views/layouts/app.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/layouts/app.blade.php',
                            'code' => <<<'CODE'
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2, 3 & 4',
                    'title' => 'Ikon Tombol & Waktu Relatif Bahasa Indonesia',
                    'description' => 'Ganti label teks tombol dengan ikon intuitif (fa-eye, fa-edit, fa-archive, fa-undo, fa-trash) serta ubah format waktu menjadi waktu relatif berbahasa Indonesia:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/index.blade.php',
                            'code' => <<<'CODE'
<small class="text-muted float-end">
    {{ $item->created_at->locale('id')->diffForHumans() }}
</small>
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 5',
                    'title' => 'Format Tanggal Lengkap di Halaman Detail',
                    'description' => 'Perbarui format tanggal lengkap di halaman detail show.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/show.blade.php',
                            'code' => <<<'CODE'
<small class="text-muted">
    {{ $catatan->created_at->locale('id')->isoFormat('dddd, D MMMM Y [pukul] HH:mm') }}
</small>
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Pastikan seluruh ikon tampil rapi dan tombol aksi dilengkapi atribut title sebagai tooltip.',
                "Format diffForHumans() menghasilkan teks alami seperti '2 jam yang lalu' atau '3 hari yang lalu'.",
            ],
        ],
        [
            'day' => 24,
            'title' => 'Konfirmasi Hapus dengan Modal Bootstrap',
            'summary' => 'Mengganti confirm() browser dengan Modal Bootstrap 5 yang reusable dan aman dari tanda kutip.',
            'objectives' => [
                'Mengganti dialog confirm() bawaan browser dengan komponen Modal Bootstrap 5.',
                'Mengirimkan URL tujuan dan nama item secara dinamis ke dalam modal menggunakan JavaScript.',
                'Mencegah error sintaks tanda kutip pada judul catatan dengan helper addslashes().',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 2',
                    'title' => 'Menambahkan Modal Konfirmasi Hapus Global',
                    'description' => 'Tambahkan struktur HTML Modal Konfirmasi Hapus dan fungsi JavaScript bukaModalHapus() sebelum tag penutup </body> pada resources/views/layouts/app.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/layouts/app.blade.php',
                            'code' => <<<'CODE'
<div class="modal fade" id="hapusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus <strong id="namaItem"></strong>?</p>
                <p class="text-danger small">Aksi ini tidak bisa dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="formHapus" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
window.bukaModalHapus = function(url, nama) {
    document.getElementById('namaItem').textContent = nama || 'item ini';
    document.getElementById('formHapus').action = url;
    var modal = new bootstrap.Modal(document.getElementById('hapusModal'));
    modal.show();
};
</script>
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3, 4 & 5',
                    'title' => 'Memanggil Modal dari Tombol Hapus',
                    'description' => 'Ubah tombol hapus pada index.blade.php, arsip.blade.php, dan show.blade.php agar memanggil modal:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/index.blade.php',
                            'code' => <<<'CODE'
<button type="button" class="btn btn-sm btn-outline-danger"
        onclick="bukaModalHapus('/catatan/{{ $item->id }}', '{{ addslashes($item->judul) }}')">
    <i class="fas fa-trash"></i>
</button>
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                "Uji tombol hapus pada catatan yang judulnya mengandung tanda petik tunggal (') untuk memastikan addslashes() bekerja tanpa error JavaScript.",
                'Satu modal di layout utama dapat digunakan ulang oleh seluruh halaman aplikasi.',
            ],
        ],
        [
            'day' => 25,
            'title' => 'Tampilkan Tanggal Pembuatan dengan Jelas',
            'summary' => 'Menampilkan tanggal & jam pembuatan catatan secara konsisten dengan karakter escape Carbon.',
            'objectives' => [
                'Menampilkan tanggal dan jam pembuatan catatan secara konsisten di halaman daftar aktif, arsip, dan detail.',
                'Menggunakan karakter escape pada string format tanggal Carbon (\p\u\k\u\l).',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1, 2 & 3',
                    'title' => 'Memperbarui Header Kartu Catatan',
                    'description' => 'Perbarui header kartu pada resources/views/catatan/index.blade.php, arsip.blade.php, dan show.blade.php dengan format tanggal yang jelas:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/index.blade.php',
                            'code' => <<<'CODE'
<div class="card-header">
    <h5 class="mb-1">{{ $item->judul }}</h5>
    <small class="text-muted">Dibuat pada: {{ $item->created_at->format('d F Y \p\u\k\u\l H.i') }}</small>
</div>
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                "Format 'd F Y \\p\\u\\k\\u\\l H.i' menghasilkan tampilan: '10 Februari 2026 pukul 14.30'.",
                "Setiap huruf pada kata 'pukul' harus diawali backslash (\\) agar tidak diterjemahkan sebagai kode format tanggal PHP.",
            ],
        ],
        [
            'day' => 26,
            'title' => 'Tampilkan Jumlah Total Catatan',
            'summary' => 'Menghitung total catatan aktif & arsip langsung dari database menggunakan ->count().',
            'objectives' => [
                'Menghitung total catatan aktif dan total catatan arsip langsung dari database menggunakan ->count().',
                'Menampilkan ringkasan statistik jumlah catatan di bagian atas halaman.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1 & 3',
                    'title' => 'Menghitung Jumlah Total di Controller',
                    'description' => 'Hitung jumlah catatan aktif pada method index() dan jumlah arsip pada method arsip() di CatatanController.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/CatatanController.php',
                            'code' => <<<'CODE'
// Di method index():
$jumlah_aktif = Catatan::where('user_id', auth()->id())
    ->where('is_archived', false)
    ->count();

return view('catatan.index', compact('catatan', 'jumlah_aktif'));

// Di method arsip():
$jumlah_arsip = Catatan::where('user_id', auth()->id())
    ->where('is_archived', true)
    ->count();

return view('catatan.arsip', compact('catatan', 'jumlah_arsip'));
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2 & 4',
                    'title' => 'Menampilkan Statistik Jumlah di View',
                    'description' => 'Tampilkan informasi jumlah tersebut di bawah judul halaman pada index.blade.php dan arsip.blade.php:',
                    'codes' => [
                        [
                            'language' => 'html',
                            'filename' => 'resources/views/catatan/index.blade.php',
                            'code' => <<<'CODE'
<h2>Catatan Aktif</h2>
<p class="text-muted">Kamu punya {{ $jumlah_aktif }} catatan aktif.</p>
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Verifikasi bahwa angka penghitung otomatis bertambah/berkurang saat menambah, mengarsipkan, atau menghapus catatan.',
                'Gunakan query ->count() langsung dari database agar jumlah total tetap akurat meskipun daftar menggunakan pagination.',
            ],
        ],
    ],
];
