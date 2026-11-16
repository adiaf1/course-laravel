<?php

return [
    'number' => 4,
    'range' => 'Hari 12 – Hari 14',
    'title' => 'Keamanan, Penanganan Error, Testing & Dokumentasi API',
    'focus' => 'TaskPolicy, Exception Handler JSON di bootstrap/app.php, Rate Limiting, CORS & Feature Testing',
    'days' => [
        [
            'day' => 12,
            'title' => 'Authorization Policy & Error Handling JSON Global',
            'summary' => 'Membuat TaskPolicy untuk kepemilikan resource dan memastikan semua error API berformat JSON.',
            'objectives' => [
                'Membuat TaskPolicy dengan php artisan make:policy untuk membatasi akses show/update/delete.',
                'Menggunakan $this->authorize() di TaskController.',
                'Mengatur rendering exception JSON konsisten di bootstrap/app.php.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Membuat TaskPolicy',
                    'description' => 'Buat policy untuk model Task dan izinkan aksi hanya jika $user->id === $task->user_id:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:policy TaskPolicy --model=Task
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'app/Policies/TaskPolicy.php',
                            'code' => <<<'CODE'
public function view(User $user, Task $task): bool
{
    return $user->id === $task->user_id;
}

public function update(User $user, Task $task): bool
{
    return $user->id === $task->user_id;
}

public function delete(User $user, Task $task): bool
{
    return $user->id === $task->user_id;
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Memaksa Exception JSON untuk Route /api/* di Laravel 13',
                    'description' => 'Pada bootstrap/app.php, atur shouldRenderJsonWhen agar seluruh route /api/* selalu mengembalikan JSON meski client lupa mengirim header Accept: application/json:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'bootstrap/app.php',
                            'code' => <<<'CODE'
->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e): bool {
        if ($request->is('api/*')) {
            return true;
        }

        return $request->expectsJson();
    });
})
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Panggil Gate::authorize(\'view\', $task) pada show(), \'update\' pada update(), dan \'delete\' pada destroy() di TaskController.',
                'Uji akses task milik User A menggunakan token User B dan pastikan server membalas 403 Forbidden dalam format JSON.',
            ],
        ],
        [
            'day' => 13,
            'title' => 'Rate Limiting API & Konfigurasi CORS',
            'summary' => 'Membatasi jumlah request per menit dengan RateLimiter::for() dan mengizinkan frontend SPA via CORS.',
            'objectives' => [
                'Mencegah penyalahgunaan API (brute-force/spam) dengan RateLimiter::for().',
                'Menerapkan middleware throttle:api pada grup route API.',
                'Memahami konfigurasi CORS (config/cors.php) untuk integrasi React/Vue/Mobile.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Mendefinisikan Rate Limiter di AppServiceProvider',
                    'description' => 'Buka app/Providers/AppServiceProvider.php dan definisikan batas 60 request per menit per user atau IP:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Providers/AppServiceProvider.php',
                            'code' => <<<'CODE'
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

public function boot(): void
{
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menerapkan Throttle & Mengecek Konfigurasi CORS',
                    'description' => 'Pasang middleware throttle:api di routes/api.php dan publikasikan config/cors.php jika ingin menyesuaikan domain frontend yang diizinkan:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/api.php',
                            'code' => <<<'CODE'
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::apiResource('tasks', TaskController::class);
});
CODE,
                        ],
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal (opsional untuk kustomisasi CORS)',
                            'code' => <<<'CODE'
php artisan config:publish cors
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Periksa header respons X-RateLimit-Limit dan X-RateLimit-Remaining di Postman setiap kali memanggil endpoint API.',
                'Pada config/cors.php, hindari memakai allowed_origins => [\'*\'] di production; sebutkan domain frontend secara spesifik.',
            ],
        ],
        [
            'day' => 14,
            'title' => 'Automated Feature Testing API & Dokumentasi',
            'summary' => 'Menulis pengujian otomatis endpoint Sanctum dengan PHPUnit dan merapikan dokumentasi API.',
            'objectives' => [
                'Menulis Feature Test untuk endpoint Register, Login, dan CRUD Tasks.',
                'Menggunakan Sanctum::actingAs() untuk mensimulasikan user terautentikasi dalam test.',
                'Menjalankan php artisan test dan memastikan seluruh skenario lulus.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Membuat Feature Test TaskApiTest',
                    'description' => 'Buat file test baru dan tulis skenario pengujian otentikasi serta CRUD API:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:test TaskApiTest
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'tests/Feature/TaskApiTest.php',
                            'code' => <<<'CODE'
<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_tasks_api(): void
    {
        $this->getJson('/api/tasks')->assertStatus(401);
    }

    public function test_authenticated_user_can_create_and_list_tasks(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/tasks', [
            'title' => 'Belajar Sanctum Laravel 13',
            'status' => 'in_progress',
        ])->assertStatus(201)
          ->assertJsonPath('data.title', 'Belajar Sanctum Laravel 13');

        $this->getJson('/api/tasks')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menjalankan Test Suite',
                    'description' => 'Jalankan seluruh pengujian untuk memastikan API bebas regresi:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan test --filter=TaskApiTest
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Tambahkan test skenario update, delete, dan validasi gagal (422) serta kepemilikan user (403).',
                'Ekspor koleksi Postman atau pasang paket seperti Scramble (dedoc/scramble) untuk dokumentasi OpenAPI otomatis. Selamat, kurikulum RESTful API Laravel 13 & Sanctum selesai!',
            ],
        ],
    ],
];
