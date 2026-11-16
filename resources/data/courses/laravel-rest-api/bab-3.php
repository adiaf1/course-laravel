<?php

return [
    'number' => 3,
    'range' => 'Hari 9 – Hari 11',
    'title' => 'Autentikasi Token dengan Laravel Sanctum',
    'focus' => 'HasApiTokens, Register, Login, Bearer Token, Logout & Isolasi Data per User',
    'days' => [
        [
            'day' => 9,
            'title' => 'Create Auth Controller, Model User (HasApiTokens) & Register API',
            'summary' => 'Membuat Api/AuthController, mengaktifkan trait HasApiTokens di User, mendaftarkan route register, dan mengecek route:list.',
            'objectives' => [
                'Membuat API Controller baru di app/Http/Controllers/Api/AuthController.php.',
                'Mengaktifkan trait HasApiTokens dari Laravel\\Sanctum pada Model User.',
                'Membuat fungsi register() dengan Validator::make(), Password::defaults(), dan createToken().',
                'Mendaftarkan route POST /api/register dan memverifikasinya dengan php artisan route:list.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Membuat Api/AuthController',
                    'description' => 'Pada materi ini, kita akan membuat API controller khusus untuk menangani register, login, dan logout. Jalankan perintah berikut di terminal (gunakan garis miring / agar kompatibel di Windows, macOS, maupun Linux):',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:controller Api/AuthController
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menambahkan Function register() di AuthController',
                    'description' => 'Buka file app/Http/Controllers/Api/AuthController.php dan isi method register() menggunakan Validator::make(), Password::defaults(), dan createToken() agar respons sukses maupun gagal validasi (422) memiliki format JSON yang seragam:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/Api/AuthController.php',
                            'code' => <<<'CODE'
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
                'message' => 'Registration failed. Please check your input.',
            ], 422);
        }

        // Buat user baru
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Buat token Sanctum untuk user baru
        $responseData = [
            'token' => $user->createToken($user->name)->plainTextToken,
            'name'  => $user->name,
        ];

        // Kirim respons sukses (201 Created)
        return response()->json([
            'success' => true,
            'data'    => $responseData,
            'message' => 'User successfully registered.',
        ], 201);
    }
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 3',
                    'title' => 'Menambahkan Trait HasApiTokens di Model User',
                    'description' => 'Buka app/Models/User.php, import Laravel\\Sanctum\\HasApiTokens, dan tambahkan trait HasApiTokens ke dalam class User agar method createToken() tersedia:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Models/User.php',
                            'code' => <<<'CODE'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 4 & 5',
                    'title' => 'Mendaftarkan Route Register & Cek Rute',
                    'description' => 'Buka routes/api.php dan daftarkan route POST /register. Setelah itu jalankan php artisan route:list --path=api untuk memastikan endpoint api/register sudah terdaftar:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/api.php',
                            'code' => <<<'CODE'
<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
CODE,
                        ],
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan route:list --path=api
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 6',
                    'title' => 'Uji Coba Endpoint Register dengan Postman',
                    'description' => 'Buka aplikasi Postman (unduh di https://www.postman.com/downloads/ jika belum terpasang). Buat Collection baru bernama LARAVEL13-RESTAPI, tambahkan subfolder AUTH, lalu buat request POST bernama register:',
                    'codes' => [
                        [
                            'language' => 'text',
                            'filename' => 'Konfigurasi Request Postman',
                            'code' => <<<'CODE'
Method  : POST
URL     : http://127.0.0.1:8000/api/register
Headers : Accept: application/json

Body (form-data atau raw JSON):
{
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
CODE,
                        ],
                        [
                            'language' => 'json',
                            'filename' => 'Contoh Respons Sukses (201 Created)',
                            'code' => <<<'CODE'
{
    "success": true,
    "data": {
        "token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz123456",
        "name": "Budi Santoso"
    },
    "message": "User successfully registered."
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'note',
                    'title' => 'Catatan Arsitektur Stateless API',
                    'content' => 'Untuk API berbasis Bearer Token (mobile app / Postman), route /register di routes/api.php tidak perlu dibungkus middleware("web") maupun memanggil Auth::login($user), karena autentikasi selanjutnya menggunakan token Sanctum pada header Authorization: Bearer <token>.',
                ],
            ],
            'tasks' => [
                'Uji POST http://127.0.0.1:8000/api/register di Postman dengan field name, email, password, dan password_confirmation.',
                'Uji juga dengan password kurang dari 8 karakter atau email yang sudah terdaftar untuk melihat respons JSON 422 dari Validator.',
            ],
        ],
        [
            'day' => 10,
            'title' => 'Endpoint Get User, Login, Generate Token & Logout',
            'summary' => 'Mengambil profil user login via $request->user(), verifikasi login Hash::check(), dan mencabut token saat logout.',
            'objectives' => [
                'Membuat endpoint GET /api/user menggunakan $request->user() dan middleware auth:sanctum.',
                'Memverifikasi email dan password pengguna dengan Hash::check() di method login().',
                'Menerbitkan Bearer Token baru saat login dan mencabut token aktif saat logout.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Menambahkan Function getUser(), login() & logout() di AuthController',
                    'description' => 'Buka app/Http/Controllers/Api/AuthController.php dan tambahkan method getUser() untuk mengambil data user yang sedang login berdasarkan Bearer Token, serta method login() dan logout():',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/Api/AuthController.php',
                            'code' => <<<'CODE'
public function getUser(Request $request): JsonResponse
{
    return response()->json([
        'success' => true,
        'data'    => $request->user(),
    ], 200);
}

public function login(Request $request): JsonResponse
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'email'    => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation errors occurred.',
            'errors'  => $validator->errors(),
        ], 422);
    }

    // Verifikasi kredensial
    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials.',
        ], 401);
    }

    $responseData = [
        'token' => $user->createToken($user->name)->plainTextToken,
        'name'  => $user->name,
    ];

    return response()->json([
        'success' => true,
        'message' => 'Login successful.',
        'data'    => $responseData,
    ], 200);
}

public function logout(Request $request): JsonResponse
{
    $user = $request->user();

    // Periksa apakah pengguna memiliki token aktif
    if ($user->tokens()->count() === 0) {
        return response()->json([
            'success' => false,
            'message' => 'No active tokens found for this user.',
        ], 404);
    }

    // Hapus semua token yang terkait dengan pengguna
    $user->tokens()->delete();

    return response()->json([
        'success' => true,
        'message' => 'Successfully logged out.',
    ], 200);
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Konfigurasi Rute di routes/api.php',
                    'description' => 'Daftarkan /register dan /login sebagai rute publik, serta /user dan /logout di dalam grup middleware auth:sanctum:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/api.php',
                            'code' => <<<'CODE'
<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'table',
                    'heading' => 'Uji Coba di Postman (Login, Get User & Logout)',
                    'headers' => ['Endpoint', 'Method', 'Header / Body'],
                    'rows' => [
                        ['http://127.0.0.1:8000/api/login', 'POST', 'Header Accept: application/json | Body: email, password'],
                        ['http://127.0.0.1:8000/api/user', 'GET', 'Header Accept: application/json & Authorization: Bearer <TOKEN>'],
                        ['http://127.0.0.1:8000/api/logout', 'POST', 'Header Accept: application/json & Authorization: Bearer <TOKEN>'],
                    ],
                ],
                [
                    'type' => 'note',
                    'title' => 'Catatan Pengujian Token di Postman',
                    'content' => 'Saat memanggil POST /api/logout dengan Bearer Token yang valid, seluruh token milik user dihapus dan server membalas 200 OK ("Successfully logged out."). Jika token sudah tidak ada atau tidak valid, akses ke endpoint yang dilindungi auth:sanctum akan ditolak dengan 401 Unauthenticated.',
                ],
            ],
            'tasks' => [
                'Uji GET /api/user menggunakan token dari hasil register atau login.',
                'Uji POST /api/logout dengan Bearer Token yang sama, lalu coba panggil GET /api/user lagi—pastikan server membalas 401 Unauthenticated karena token sudah dicabut.',
            ],
        ],
        [
            'day' => 11,
            'title' => 'Proteksi Route dengan Middleware auth:sanctum & Isolasi Data',
            'summary' => 'Memindahkan endpoint tasks ke dalam middleware auth:sanctum dan membatasi data per user.',
            'objectives' => [
                'Melindungi seluruh endpoint CRUD /api/tasks dengan middleware auth:sanctum.',
                'Mengambil data task hanya milik user yang sedang login melalui $request->user()->tasks().',
                'Mencegah user melihat atau mengubah task milik user lain.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Memindahkan Route Tasks ke Middleware auth:sanctum',
                    'description' => 'Perbarui routes/api.php agar seluruh endpoint tasks wajib menyertakan Bearer Token:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'routes/api.php',
                            'code' => <<<'CODE'
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', fn (Request $request) => response()->json(['user' => $request->user()]));
    Route::apiResource('tasks', TaskController::class);
});
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menggunakan Relasi $request->user()->tasks()',
                    'description' => 'Perbarui index() dan store() di TaskController agar selalu terikat ke user yang terautentikasi:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/Api/TaskController.php',
                            'code' => <<<'CODE'
// Di dalam method index():
$tasks = $request->user()->tasks()
    ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
    ->latest()
    ->paginate($perPage);

// Di dalam method store():
$task = $request->user()->tasks()->create($request->validated());
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Coba akses GET /api/tasks tanpa Bearer Token dan pastikan server membalas 401 Unauthorized.',
                'Di Hari 12, kita akan melengkapi otorisasi show, update, dan destroy menggunakan Laravel Policy.',
            ],
        ],
    ],
];
