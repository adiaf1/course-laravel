<?php

return [
    'number' => 2,
    'range' => 'Hari 5 – Hari 8',
    'title' => 'CRUD API, Validasi Form Request & API Resource',
    'focus' => 'Create, Update, Delete, FormRequest Validation, TaskResource, Pagination & Filtering',
    'days' => [
        [
            'day' => 5,
            'title' => 'Endpoint Create (POST) & Form Request Validation',
            'summary' => 'Membuat StoreTaskRequest agar validasi API terpisah rapi dan otomatis membalas 422 JSON.',
            'objectives' => [
                'Membuat class Form Request khusus untuk validasi input API.',
                'Menangani endpoint POST /api/tasks dan mengembalikan status code 201 Created.',
                'Memahami respons error validasi 422 otomatis di Laravel.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Membuat StoreTaskRequest',
                    'description' => 'Buat Form Request untuk validasi pembuatan task:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:request StoreTaskRequest
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Requests/StoreTaskRequest.php',
                            'code' => <<<'CODE'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status'      => ['sometimes', 'in:pending,in_progress,done'],
            'due_date'    => ['nullable', 'date'],
        ];
    }
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menambahkan Method store() di TaskController',
                    'description' => 'Gunakan StoreTaskRequest dan kembalikan status 201 Created (sementara gunakan user_id = 1 sebelum Sanctum dipasang di Bab 3):',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/Api/TaskController.php',
                            'code' => <<<'CODE'
use App\Http\Requests\StoreTaskRequest;

public function store(StoreTaskRequest $request): JsonResponse
{
    $data = $request->validated();
    $data['user_id'] = $request->user()?->id ?? 1;

    $task = Task::create($data);

    return response()->json([
        'message' => 'Task berhasil dibuat.',
        'data'    => $task,
    ], 201);
}
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Kirim POST /api/tasks tanpa field title dengan header Accept: application/json dan amati respons 422 Unprocessable Entity.',
                'Kirim POST /api/tasks dengan data valid dan pastikan status respons adalah 201.',
            ],
        ],
        [
            'day' => 6,
            'title' => 'Endpoint Update (PUT/PATCH) & Delete (DELETE)',
            'summary' => 'Membuat UpdateTaskRequest dengan aturan sometimes dan endpoint hapus data.',
            'objectives' => [
                'Memahami perbedaan PUT (update penuh) dan PATCH (update parsial).',
                'Membuat UpdateTaskRequest dengan aturan validasi sometimes.',
                'Mengimplementasikan method update() dan destroy() di TaskController.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Membuat UpdateTaskRequest',
                    'description' => 'Aturan sometimes membuat field hanya divalidasi jika dikirim oleh client (cocok untuk PATCH):',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:request UpdateTaskRequest
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Requests/UpdateTaskRequest.php',
                            'code' => <<<'CODE'
public function rules(): array
{
    return [
        'title'       => ['sometimes', 'required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'status'      => ['sometimes', 'in:pending,in_progress,done'],
        'due_date'    => ['nullable', 'date'],
    ];
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menambahkan Method update() & destroy()',
                    'description' => 'Perbarui TaskController.php untuk menangani pembaruan dan penghapusan data:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/Api/TaskController.php',
                            'code' => <<<'CODE'
use App\Http\Requests\UpdateTaskRequest;

public function update(UpdateTaskRequest $request, Task $task): JsonResponse
{
    $task->update($request->validated());

    return response()->json([
        'message' => 'Task berhasil diperbarui.',
        'data'    => $task->fresh(),
    ]);
}

public function destroy(Task $task): JsonResponse
{
    $task->delete();

    return response()->json([
        'message' => 'Task berhasil dihapus.',
    ]);
}
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Uji PATCH /api/tasks/1 hanya dengan payload {"status": "done"} tanpa mengirim title.',
                'Uji DELETE /api/tasks/1 dan verifikasi data terhapus dari database.',
            ],
        ],
        [
            'day' => 7,
            'title' => 'Transformasi Response JSON dengan API Resource',
            'summary' => 'Menggunakan JsonResource (TaskResource) untuk menyaring field dan menyeragamkan format JSON.',
            'objectives' => [
                'Memahami mengapa Model tidak sebaiknya langsung di-return mentah ke JSON.',
                'Membuat TaskResource menggunakan php artisan make:resource.',
                'Mengembalikan single resource dan resource collection secara konsisten.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Membuat TaskResource',
                    'description' => 'Generate class Resource dan tentukan field JSON yang ingin diekspos ke publik:',
                    'codes' => [
                        [
                            'language' => 'bash',
                            'filename' => 'Terminal',
                            'code' => <<<'CODE'
php artisan make:resource TaskResource
CODE,
                        ],
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Resources/TaskResource.php',
                            'code' => <<<'CODE'
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
            'due_date'    => $this->due_date?->format('Y-m-d'),
            'created_at'  => $this->created_at?->toIso8601String(),
        ];
    }
}
CODE,
                        ],
                    ],
                ],
                [
                    'type' => 'step',
                    'label' => 'Langkah 2',
                    'title' => 'Menggunakan TaskResource di Controller',
                    'description' => 'Perbarui method index(), show(), store(), dan update() agar menggunakan TaskResource:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/Api/TaskController.php',
                            'code' => <<<'CODE'
use App\Http\Resources\TaskResource;

public function show(Task $task): TaskResource
{
    return new TaskResource($task);
}

public function store(StoreTaskRequest $request): JsonResponse
{
    $data = $request->validated();
    $data['user_id'] = $request->user()?->id ?? 1;

    $task = Task::create($data);

    return (new TaskResource($task))
        ->additional(['message' => 'Task berhasil dibuat.'])
        ->response()
        ->setStatusCode(201);
}
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Perhatikan bahwa TaskResource otomatis membungkus output dalam key "data" secara standar.',
                'Pastikan kolom sensitif atau internal seperti user_id tidak ikut terekspos jika tidak diperlukan client.',
            ],
        ],
        [
            'day' => 8,
            'title' => 'Pagination, Filter Status & Pencarian pada Endpoint API',
            'summary' => 'Menambahkan query parameter ?status=, ?search=, dan ?per_page= pada GET /api/tasks.',
            'objectives' => [
                'Menggunakan ->paginate() bersama TaskResource::collection().',
                'Menyaring data berdasarkan query parameter status dan search menggunakan ->when().',
                'Memahami metadata pagination (links & meta) otomatis di API Resource.',
            ],
            'blocks' => [
                [
                    'type' => 'step',
                    'label' => 'Langkah 1',
                    'title' => 'Menambahkan Filter & Pagination di index()',
                    'description' => 'Perbarui method index(Request $request) di TaskController menggunakan helper ->when() agar query tetap bersih dan mudah dibaca:',
                    'codes' => [
                        [
                            'language' => 'php',
                            'filename' => 'app/Http/Controllers/Api/TaskController.php',
                            'code' => <<<'CODE'
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

public function index(Request $request): AnonymousResourceCollection
{
    $perPage = min((int) $request->integer('per_page', 10), 50);

    $tasks = Task::query()
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('status', $request->string('status'));
        })
        ->when($request->filled('search'), function ($query) use ($request) {
            $keyword = '%' . $request->string('search') . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', $keyword)
                  ->orWhere('description', 'like', $keyword);
            });
        })
        ->latest()
        ->paginate($perPage)
        ->withQueryString();

    return TaskResource::collection($tasks);
}
CODE,
                        ],
                    ],
                ],
            ],
            'tasks' => [
                'Uji endpoint GET /api/tasks?status=pending&per_page=5 di Postman.',
                'Amati struktur JSON "data", "links", dan "meta" yang otomatis dihasilkan Laravel.',
            ],
        ],
    ],
];
