<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    //Funcion que devuelve los datos de los usuarios registrados mediante su EMAIL
    public function getUserProfile($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Obtener promedio de reseñas recibidas
        $averageScore = $user->reviewsReceived()->avg('score');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phoneNumber' => $user->phoneNumber,
            'userDescription' => $user->userDescription,
            'imageProfile' => $user->imageProfile,
            'dni' => $user->dni,
            'location' => $user->location,
            'is_worker' => $user->is_worker,
            'id_job' => $user->id_job,
            'job_description' => $user->job_description,
            'job_images' => $user->job_images,
            'score' => round($averageScore, 2),
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if ($user && !$user instanceof \App\Models\User) {
            $user = \App\Models\User::find($user->id);
        }

        // Validaciones
        $validator = Validator::make($request->all(), [
            'dni' => 'required|numeric',
            'userDescription' => 'required|string|max:255',
            'imageProfile' => 'required|string',
            'location' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Guardar en base de datos
        $user->dni = $request->dni;
        $user->userDescription = $request->userDescription;
        $user->imageProfile = $request->imageProfile;
        $user->location = $request->location;
        if (method_exists($user, 'save')) {
            $user->save();
        } else {
            return response()->json([
                'message' => 'No se pudo actualizar el perfil: método save() no disponible en el usuario autenticado.'
            ], 500);
        }

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'dni' => $user->dni,
                'userDescription' => $user->userDescription,
                'imageProfile' => $user->imageProfile,
                'location' => $user->location,
            ]
        ], 200);
    }

    public function uploadJobPhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'photoUrl' => 'required|string'
        ]);

        // Decodificar si llega como string
        $images = $user->job_images ?? [];

        if (is_string($images)) {
            $images = json_decode($images, true);
        }

        // Asegurar array de objetos
        $images = is_array($images) ? $images : [];

        // Obtener el ID más alto
        $maxId = count($images) > 0 ? max(array_column($images, 'id')) : 0;
        $nextId = $maxId + 1;

        // Agregar nueva imagen
        $images[] = [
            'id' => $nextId,
            'url' => $request->photoUrl,
        ];

        $user->job_images = $images;

        // Ensure $user is an Eloquent model instance before saving
        if ($user && !$user instanceof \App\Models\User) {
            $user = \App\Models\User::find($user->id);
        }

        if (method_exists($user, 'save')) {
            $user->save();
        } else {
            return response()->json([
                'message' => 'No se pudo guardar la imagen: método save() no disponible en el usuario autenticado.'
            ], 500);
        }

        return response()->json(['id' => $nextId, 'url' => $request->photoUrl], 201);
    }



    public function deleteJobPhoto($id)
    {
        $user = Auth::user();

        $images = $user->job_images ?? [];

        if (is_string($images)) {
            $images = json_decode($images, true);
        }

        $images = array_filter($images, function ($image) use ($id) {
            return $image['id'] != $id;
        });

        // Reindexar el array (opcional)
        $images = array_values($images);

        $user->job_images = $images;

        // Ensure $user is an Eloquent model instance before saving
        if ($user && !$user instanceof \App\Models\User) {
            $user = \App\Models\User::find($user->id);
        }

        if (method_exists($user, 'save')) {
            $user->save();
        } else {
            return response()->json([
                'message' => 'No se pudo eliminar la imagen: método save() no disponible en el usuario autenticado.'
            ], 500);
        }

        return response()->json(['message' => 'Imagen eliminada']);
    }



    public function updateProfilePic(Request $request)
    {
        $user = Auth::user();
        if ($user && !$user instanceof \App\Models\User) {
            $user = \App\Models\User::find($user->id);
        }

        $validator = Validator::make($request->all(), [
            'imageProfile' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Imagen no válida.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->imageProfile = $request->imageProfile;

        if (method_exists($user, 'save')) {
            $user->save();
        } else {
            return response()->json([
                'message' => 'No se pudo actualizar el perfil: método save() no disponible en el usuario autenticado.'
            ], 500);
        }

        return response()->json([
            'message' => 'Imagen actualizada correctamente.',
            'user' => [
                'id' => $user->id,
                'imageProfile' => $user->imageProfile,
            ]
        ], 200);
    }

    public function updateProfileWorker(Request $request)
    {
        $user = Auth::user();
        if ($user && !$user instanceof \App\Models\User) {
            $user = \App\Models\User::find($user->id);
        }

        // Validaciones
        $validator = Validator::make($request->all(), [
            'dni' => 'required|numeric',
            'userDescription' => 'required|string|max:255',
            'imageProfile' => 'required|string',
            'location' => 'required|string',
            'is_worker' => 'required|boolean',
            'job_description' => 'required|string',
            'job_images' => 'required|array',
            'id_job' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Guardar en base de datos
        $user->dni = $request->dni;
        $user->userDescription = $request->userDescription;
        $user->imageProfile = $request->imageProfile;
        $user->location = $request->location;
        $user->is_worker = $request->is_worker;
        $user->job_description = $request->job_description;
        $user->job_images = $request->job_images;
        $user->id_job = $request->id_job;
        if (method_exists($user, 'save')) {
            $user->save();
        } else {
            return response()->json([
                'message' => 'No se pudo actualizar el perfil: método save() no disponible en el usuario autenticado.'
            ], 500);
        }

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'dni' => $user->dni,
                'userDescription' => $user->userDescription,
                'imageProfile' => $user->imageProfile,
                'location' => $user->location,
                'is_worker' => $user->is_worker,
                'job_description' => $user->job_description,
                'job_images' => $user->job_images,
                'id_job' => $user->id_job,
            ]
        ], 200);
    }

    public function getAllWorkers()
    {
        $workers = \App\Models\User::where('is_worker', true)->get();

        return response()->json([
            'success' => true,
            'workers' => $workers
        ]);
    }

    public function searchWorkers(Request $request)
    {
        $search = $request->query('search');
        $id_job = $request->query('id_job');

        $query = \App\Models\User::with('job')
            ->where('is_worker', true);

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($id_job) {
            $query->where('id_job', $id_job);
        }

        $users = $query->get();

        $result = $users->map(function ($user) {
            $avgScore = $user->reviewsReceived()->avg('score');
            return [
                'id' => $user->id,
                'fullname' => $user->name,
                'score' => round($avgScore ?? 0, 1),
                'imageProfile' => $user->imageProfile,
                'jobCategory' => $user->job ? $user->job->name : '',
            ];
        });

        return response()->json([
            'success' => true,
            'workers' => $result
        ]);
    }


    public function getUserProfileById($id)
    {
        $user = User::with('job')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Calcular promedio de reseñas recibidas
        $avgScore = $user->reviewsReceived()->avg('score');

        return response()->json([
            'id' => $user->id,
            'fullname' => $user->name,
            'location' => $user->location,
            'score' => round($avgScore ?? 0, 1),
            'jobDescription' => $user->job_description,
            'userDescription' => $user->userDescription,
            'imageProfile' => $user->imageProfile,
            'jobCategory' => $user->job?->name,
        ]);
    }


    public function getUserPhotos($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $images = $user->job_images;

        // Si las imágenes vienen como string (JSON), decodificamos
        if (is_string($images)) {
            $images = json_decode($images, true);
        }

        // Nos quedamos solo con las URLs
        $urls = collect($images)->pluck('url')->toArray();

        return response()->json($urls);
    }


    public function updateName(Request $request)
    {
        $user = Auth::user();
        if ($user && !$user instanceof \App\Models\User) {
            $user = \App\Models\User::find($user->id);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Nombre inválido.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->name = $request->name;

        if (method_exists($user, 'save')) {
            $user->save();
        } else {
            return response()->json(['message' => 'No se pudo actualizar el nombre.'], 500);
        }

        return response()->json([
            'message' => 'Nombre actualizado correctamente.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
        ], 200);
    }

    public function updateUserDescription(Request $request)
    {
        $user = Auth::user();
        if ($user && !$user instanceof \App\Models\User) {
            $user = \App\Models\User::find($user->id);
        }

        $validator = Validator::make($request->all(), [
            'userDescription' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Descripción inválida.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->userDescription = $request->userDescription;

        if (method_exists($user, 'save')) {
            $user->save();
        } else {
            return response()->json(['message' => 'No se pudo actualizar la descripción.'], 500);
        }

        return response()->json([
            'message' => 'Descripción actualizada correctamente.',
            'user' => [
                'id' => $user->id,
                'userDescription' => $user->userDescription,
            ],
        ], 200);
    }

    public function updateJobDescription(Request $request)
    {
        $user = Auth::user();
        if ($user && !$user instanceof \App\Models\User) {
            $user = \App\Models\User::find($user->id);
        }

        $validator = Validator::make($request->all(), [
            'job_description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Descripción de trabajo inválida.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->job_description = $request->job_description;

        if (method_exists($user, 'save')) {
            $user->save();
        } else {
            return response()->json(['message' => 'No se pudo actualizar la descripción de trabajo.'], 500);
        }

        return response()->json([
            'message' => 'Descripción de trabajo actualizada correctamente.',
            'user' => [
                'id' => $user->id,
                'job_description' => $user->job_description,
            ],
        ], 200);
    }

    //metodo para actualizar el oficio del usuario
    public function updateJob(Request $request)
{
    $user = Auth::user();
    if ($user && !$user instanceof \App\Models\User) {
        $user = \App\Models\User::find($user->id);
    }

    $validator = Validator::make($request->all(), [
        'id_job' => 'required|exists:trabajo,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'ID de trabajo inválido.',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user->id_job = $request->id_job;
    $user->save();

    return response()->json([
        'message' => 'Oficio actualizado correctamente.',
        'user' => [
            'id' => $user->id,
            'id_job' => $user->id_job,
        ],
    ], 200);
}


    //metodo para actualizar ubicación
    public function updateLocation(Request $request)
{
    $user = Auth::user();
    if ($user && !$user instanceof \App\Models\User) {
        $user = \App\Models\User::find($user->id);
    }

    $validator = Validator::make($request->all(), [
        'location' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Ubicación inválida.',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user->location = $request->location;

    if (method_exists($user, 'save')) {
        $user->save();
    } else {
        return response()->json(['message' => 'No se pudo actualizar la ubicación.'], 500);
    }

    return response()->json([
        'message' => 'Ubicación actualizada correctamente.',
        'user' => [
            'id' => $user->id,
            'location' => $user->location,
        ],
    ], 200);
}

//metodo para actualizar numero telefonico
    public function updatePhoneNumber(Request $request)
{
    $user = Auth::user();
    if ($user && !$user instanceof \App\Models\User) {
        $user = \App\Models\User::find($user->id);
    }

    $validator = Validator::make($request->all(), [
        'phoneNumber' => 'required|string|max:20',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Número de teléfono inválido.',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user->phoneNumber = $request->phoneNumber;

    if (method_exists($user, 'save')) {
        $user->save();
    } else {
        return response()->json(['message' => 'No se pudo actualizar el número.'], 500);
    }

    return response()->json([
        'message' => 'Número de teléfono actualizado correctamente.',
        'user' => [
            'id' => $user->id,
            'phoneNumber' => $user->phoneNumber,
        ],
    ], 200);
}


}
