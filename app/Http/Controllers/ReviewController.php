<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Crear una reseña
    public function createReview(Request $request)
    {
        $request->validate([
            'reviewed_id' => 'required|exists:users,id',
            'description' => 'required|string',
            'score' => 'required|numeric|min:1|max:5',
        ]);

        $review = Review::create([
            'reviewer_id' => Auth::id(),
            'reviewed_id' => $request->reviewed_id,
            'description' => $request->description,
            'score' => $request->score,
        ]);

        return response()->json([
            'message' => 'Reseña creada con éxito',
            'review' => $review,
        ], 201);
    }

    // Obtener todas las reseñas recibidas por un usuario
    public function getReviewsByUser($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $reviews = $user->reviewsReceived()->with('reviewer:id,name,email,imageProfile')->get();


        return response()->json([
            'user_id' => $user->id,
            'reviews' => $reviews
        ]);
    }
}
