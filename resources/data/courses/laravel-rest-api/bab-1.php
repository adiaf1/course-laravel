<?php

return [
    'number' => 1,
    'range' => 'Hari 1 – Hari 4',
    'title' => 'Fondasi RESTful API & Setup Laravel 13',
    'focus' => 'Konsep REST, HTTP Status Code, php artisan install:api, Migration & Endpoint Read',
    'days' => [
        [
            'day' => 1,
            'title' => 'Konsep RESTful API, HTTP Methods & Status Codes',
            'summary' => 'Memahami arsitektur REST, format JSON, HTTP verbs, dan status code standar industri.',
            'objectives' => [
                'Memahami perbedaan aplikasi web tradisional (HTML/Blade) dengan RESTful API (JSON).',
                'Mengenal HTTP Method utama: GET, POST, PUT/PATCH, dan DELETE.',
                'Memahami HTTP Status Code yang tepat untuk setiap respons API.',
            ],
            'blocks' => [
                [
                    'type' => 'text',
                    'heading' => 'Apa Itu RESTful API?',
                    'paragraphs' => [
                        'REST (Representational State Transfer) adalah gaya arsitektur komunikasi antar sistem melalui protokol HTTP. Berbeda dengan web biasa yang mengembalikan halaman HTML, RESTful API mengembalikan data terstruktur (umumnya JSON) sehingga bisa dikonsumsi oleh aplikasi mobile (Android/iOS), frontend SPA (React/Vue), maupun layanan pihak ketiga.',
                    ],
                ],
                [
                    'type' => 'table',
                    'heading' => 'HTTP Methods & Fungsinya pada Resource',
                    'headers' => ['Method', 'Contoh Endpoint', 'Kegunaan'],
                    'rows' => [
                        ['GET', '/api/tasks', 'Mengambil daftar semua data task'],
                        ['GET', '/api/tasks/1', 'Mengambil detail 1 task berdasarkan ID'],
                        ['POST', '/api/tasks', 'Membuat data task baru'],
                        ['PUT / PATCH', '/api/tasks/1', 'Memperbarui data task ID 1'],
                        ['DELETE', '/api/tasks/1', 'Menghapus data task ID 1'],
                    ],
                ],
                [
                    'type' => 'table',
                    'heading' => 'HTTP Status Code yang Wajib Diketahui',
                    'headers' => ['Kode', 'Arti & Kapan Digunakan'],
                    'rows' => [
                        ['200 OK', 'Permintaan berhasil (GET, PUT/PATCH, DELETE)'],
                        ['201 Created', 'Resource baru berhasil dibuat (POST)'],
                        ['401 Unauthorized', 'Pengguna belum terautentikasi / token tidak valid'],
                        ['403 Forbidden', 'Sudah login, tetapi tidak punya hak akses ke resource tersebut'],
                        ['404 Not Found', 'Resource atau endpoint tidak ditemukan'],
                        ['422 Unprocessable Entity', 'Validasi input gagal'],
                    ],
                ],
            ],
            'tasks' => [
                'Instal Postman, Insomnia, atau ekstensi Thunder Client / REST Client di VS Code untuk menguji endpoint API mulai Hari 2.',
                'Biasakan selalu mengembalikan header Accept: application/json saat melakukan request ke API Laravel.',
            ],
        ],
        [
            'day' => 2,
            'title' => 'Persiapan Proyek, Konfigurasi .env, Database & Setup API Laravel 13',
            'summary' => 'Menyiapkan proyek Laravel 13 (Composer, .env, APP_KEY, MySQL, migrate, serve) dan mengaktifkan API via install:api.',
            'objectives' => [
                'Menyiapkan proyek Laravel 13 dari awal maupun dari repositori GitHub (composer install).',
                'Menyalin .env.example menjadi .env, membuat APP_KEY, dan menghubungkan database MySQL.',
                'Menjalankan migrasi awal dan server lokal dengan php artisan serve.',
                'Mengaktifkan fitur API & Sanctum di Laravel 13 menggunakan php artisan install:api.',
            ],
            'blocks' => [
                [
                    'type' => 'text',
                    'heading' => 'Pengenalan Proyek & Persiapan Awal',
                    'paragraphs' => [
                        'Sebelum menulis endpoint API, kita perlu menyiapkan proyek Laravel 13 agar berjalan normal di komputer lokal. Anda bisa membuat proyek baru dari nol, atau mengunduh/clone proyek starter dari GitHub lalu menginstal dependensinya.',
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Membuat Proyek Baru atau Menginstal Dependensi (Composer)',
                    'description' => 'Jika membuat proyek baru, jalankan composer create-project. Jika Anda baru mengunduh/clone proyek dari GitHub, folder vendor/ belum tersedia—masuk ke folder proyek lewat terminal lalu jalankan composer install untuk mengunduh semua paket yang tercatat di composer.json:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal (Proyek Baru)',
                            'code' => <<<'CODE'
composer create-project laravel/laravel laravel13-api
cd laravel13-api
CODE,
                        ],
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal (Jika Clone / Unduh dari GitHub)',
                            'code' => <<<'CODE'
git clone https://github.com/username/nama-repo.git
cd nama-repo
composer install
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menyalin .env.example & Membuat APP_KEY',
                    'description' => 'File .env menyimpan konfigurasi sensitif lokal (tidak ikut di-upload ke GitHub). Salin .env.example menjadi .env, lalu jalankan php artisan key:generate untuk mengisi APP_KEY (kunci enkripsi session, cookie, dan token aplikasi):',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal (Windows CMD / PowerShell / Mac / Linux)',
                            'code' => <<<'CODE'
# Windows CMD:
copy .env.example .env

# PowerShell, Git Bash, macOS, atau Linux:
cp .env.example .env

# Generate APP_KEY:
php artisan key:generate
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3',
                    'title' => 'Konfigurasi Basis Data MySQL di File .env',
                    'description' => 'Buka file .env di editor Anda. Secara default Laravel 13 memakai SQLite; ubah bagian DB_* agar terhubung ke MySQL lokal (hapus tanda # jika barisnya masih dikomentari):',
                    'codes' => [
                        [
                            'language' => 'ini',
                            'filename' => '.env',
                            'code' => <<<'CODE'
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel13_api
DB_USERNAME=root
DB_PASSWORD=
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'note',
                    'title' => 'Catatan Penting Sebelum Migrasi',
                    'content' => 'Pastikan service MySQL di Laragon/XAMPP sudah berjalan dan Anda sudah membuat database kosong bernama laravel13_api (misalnya lewat phpMyAdmin, HeidiSQL, atau TablePlus) sebelum menjalankan php artisan migrate.',
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 4',
                    'title' => 'Menjalankan Migrasi & Server Lokal (php artisan serve)',
                    'description' => 'Jalankan migrasi untuk membentuk tabel bawaan Laravel (users, cache, jobs), lalu jalankan server pengembangan lokal. Kunjungi http://127.0.0.1:8000 di browser untuk memastikan aplikasi tampil tanpa error:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan migrate
php artisan serve
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 5',
                    'title' => 'Install Laravel API (php artisan install:api)',
                    'description' => 'Sejak Laravel 11 hingga Laravel 13, konfigurasi API tidak disertakan secara default agar proyek tetap ringan. Jalankan perintah berikut untuk memasang Laravel Sanctum, membuat routes/api.php, dan mendaftarkannya di bootstrap/app.php. Jika muncul pertanyaan untuk menjalankan migrasi tambahan (tabel personal_access_tokens), ketik yes lalu tekan Enter:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan install:api
# Saat ditanya "One new database migration has been published. Would you like to run all pending database migrations? (yes/no)"
# Ketik: yes lalu tekan Enter
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 6',
                    'title' => 'Konfigurasi Pengecualian CSRF di bootstrap/app.php',
                    'description' => 'Agar rute /api/* tidak terkena validasi CSRF (yang dapat memicu error 419 Page Expired saat diakses dari Postman, aplikasi mobile, atau frontend terpisah), tambahkan validateCsrfTokens(except: [\'/api/*\']) di bootstrap/app.php. Berikut tampilan lengkap file bootstrap/app.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'bootstrap/app.php',
                            'code' => <<<'CODE'
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            '/api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 7',
                    'title' => 'Membuat Endpoint Health Check Pertama (/api/ping)',
                    'description' => 'Buka routes/api.php dan tambahkan route /ping untuk memastikan API berjalan dan mengembalikan JSON:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/api.php',
                            'code' => <<<'CODE'
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Laravel 13 API siap digunakan!',
        'timestamp' => now()->toIso8601String(),
    ]);
});
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Akses GET http://127.0.0.1:8000/api/ping di Postman/browser dan pastikan muncul respons JSON {"status":"ok",...}.',
                'Periksa file bootstrap/app.php untuk melihat bagaimana Laravel 13 mendaftarkan api: __DIR__.\'/../routes/api.php\'.',
            ],
        ],
        [
            'day' => 3,
            'title' => 'Merancang Database, Migration & Model Studi Kasus Task API',
            'summary' => 'Membuat tabel tasks berelasi dengan users serta menyiapkan Model Task.',
            'objectives' => [
                'Membuat Model sekaligus file Migration menggunakan flag -m.',
                'Mendefinisikan kolom tabel tasks dengan foreign key user_id.',
                'Mengatur $fillable dan casts() pada Model Task di Laravel 13.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Membuat Model & Migration Task',
                    'description' => 'Buat Model Task beserta file migrasinya dalam satu perintah Artisan:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:model Task -m
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Mendefinisikan Skema Tabel tasks',
                    'description' => 'Buka file migrasi yang baru dibuat dan tambahkan kolom user_id, title, description, status, dan due_date:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'database/migrations/xxxx_create_tasks_table.php',
                            'code' => <<<'CODE'
public function up(): void
{
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->text('description')->nullable();
        $table->enum('status', ['pending', 'in_progress', 'done'])->default('pending');
        $table->date('due_date')->nullable();
        $table->timestamps();
    });
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3',
                    'title' => 'Mengonfigurasi Model Task',
                    'description' => 'Atur $fillable, casts(), dan relasi user() pada app/Models/Task.php, lalu jalankan php artisan migrate:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Models/Task.php',
                            'code' => <<<'CODE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Jalankan php artisan migrate untuk membentuk tabel tasks di database.',
                'Tambahkan juga relasi public function tasks(): HasMany pada app/Models/User.php.',
            ],
        ],
        [
            'day' => 4,
            'title' => 'Membuat API Controller & Endpoint Read (GET)',
            'summary' => 'Membuat TaskController dengan --api serta endpoint daftar dan detail task.',
            'objectives' => [
                'Membuat Controller khusus API menggunakan flag --api (tanpa method create/edit).',
                'Mengimplementasikan endpoint GET /api/tasks dan GET /api/tasks/{task}.',
                'Menggunakan Route Model Binding untuk otomatis mengembalikan 404 jika data tidak ada.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Generate API Controller',
                    'description' => 'Jalankan perintah berikut untuk membuat app/Http/Controllers/Api/TaskController.php:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:controller Api/TaskController --api
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2 & 3',
                    'title' => 'Implementasi index() & show() serta Route API',
                    'description' => 'Isi method index() dan show() di TaskController, lalu daftarkan Route::apiResource di routes/api.php:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/Api/TaskController.php',
                            'code' => <<<'CODE'
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $tasks = Task::latest()->get();

        return response()->json([
            'data' => $tasks,
        ]);
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json([
            'data' => $task,
        ]);
    }
}
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'routes/api.php',
                            'code' => <<<'CODE'
use App\Http\Controllers\Api\TaskController;

Route::apiResource('tasks', TaskController::class);
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Jalankan php artisan route:list --path=api untuk melihat 5 route yang otomatis dibuat oleh Route::apiResource.',
                'Uji GET /api/tasks/999 dengan header Accept: application/json dan pastikan Laravel membalas status 404 JSON.',
            ],
        ],
    ],
];
