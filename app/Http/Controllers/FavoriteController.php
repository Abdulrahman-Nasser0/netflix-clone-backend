<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Favorites",
 *     description="API Endpoints for managing user favorites"
 * )
 */
class FavoriteController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/add",
     *     tags={"Favorites"},
     *     summary="Add a media item to favorites",
     *     description="Adds or updates a media item in the authenticated user's favorites",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tmdb_id", "media_type"},
     *             @OA\Property(property="tmdb_id", type="string", example="12345"),
     *             @OA\Property(property="media_type", type="string", example="movie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Added to favorites successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Added to favorites successfully"),
     *             @OA\Property(property="favorite", type="object",
     *                 @OA\Property(property="tmdb_id", type="string", example="12345"),
     *                 @OA\Property(property="media_type", type="string", example="movie")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to add to favorites"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function add(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'tmdb_id' => 'required|string',
            'media_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $favorite = $request->user()->favorites()->updateOrCreate(
                ['tmdb_id' => $request->tmdb_id],
                ['tmdb_id' => $request->tmdb_id,'media_type'=> $request->media_type]
            );

            return response()->json([
                'message' => 'Added to favorites successfully',
                'favorite' => $favorite
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add to favorites',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/retrieve",
     *     tags={"Favorites"},
     *     summary="Retrieve user favorites",
     *     description="Returns a list of the authenticated user's favorite media items",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="favorites", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="tmdb_id", type="string", example="12345"),
     *                     @OA\Property(property="media_type", type="string", example="movie")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error retrieving favorites"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function retrieve()
    {
        try {
            $favorites = Auth::user()->favorites;
            return response()->json([
                'favorites' => $favorites,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving favorites',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/delete/{tmdb_id}",
     *     tags={"Favorites"},
     *     summary="Remove a media item from favorites",
     *     description="Deletes a specific media item from the authenticated user's favorites",
     *     @OA\Parameter(
     *         name="tmdb_id",
     *         in="path",
     *         required=true,
     *         description="The TMDB ID of the media item to delete",
     *         @OA\Schema(type="string", example="12345")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful deletion",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Movie removed from favorites")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Favorite not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Favorite not found")
     *         )
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function delete($tmdb_id)
    {
        $favorite = Auth::user()->favorites()->where('tmdb_id', $tmdb_id)->firstOrFail();
        $favorite->delete();

        return response()->json([
            'message' => 'Movie removed from favorites',
        ]);
    }
}
