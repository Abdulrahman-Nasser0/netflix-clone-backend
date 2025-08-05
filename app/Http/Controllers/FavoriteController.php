<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

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
    public function delete($tmdb_id)
    {
        $favorite = Auth::user()->favorites()->where('tmdb_id', $tmdb_id)->firstOrFail();
        $favorite->delete();

        return response()->json([
            'message' => 'Movie removed from favorites',
        ]);
    }
}
