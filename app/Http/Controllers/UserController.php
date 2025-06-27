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
            'score' => $user->score,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        // Ensure $user is an Eloquent model instance
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
    if ($user && !$user instanceof \App\Models\User) {
        $user = \App\Models\User::find($user->id);
    }

    // Validar que recibimos la URL de la imagen (string)
    $validator = Validator::make($request->all(), [
        'photoUrl' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Datos inválidos',
            'errors' => $validator->errors(),
        ], 422);
    }

    $images = $user->job_images ?? [];

    // Simular un ID único para la nueva imagen
    $nextId = count($images) > 0
        ? max(array_column($images, 'id')) + 1
        : 1;

    $newImage = [
        'id' => $nextId,
        'url' => $request->photoUrl,
    ];

    $images[] = $newImage;

    $user->job_images = $images;

    if (method_exists($user, 'save')) {
        $user->save();
    } else {
        return response()->json([
            'message' => 'No se pudo guardar la imagen: método save() no disponible en el usuario autenticado.'
        ], 500);
    }

    return response()->json([
        'message' => 'Imagen agregada correctamente',
        'image' => $newImage
    ], 200);
}


public function deleteJobPhoto($id)
{
    $user = Auth::user();
    if ($user && !$user instanceof \App\Models\User) {
        $user = \App\Models\User::find($user->id);
    }

    $images = $user->job_images ?? [];

    $updated = array_filter($images, function ($img) use ($id) {
        return $img['id'] != $id;
    });

    $user->job_images = array_values($updated);

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
        // Ensure $user is an Eloquent model instance
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

}