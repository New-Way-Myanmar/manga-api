<?php

namespace App\Http\Controllers\Manga;


use App\Models\Manga;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class MangaController extends Controller
{
    public function index()
    {
        try {
            // Fetch mangas with paginate 12
            $mangas = Manga::paginate(12); 

            // Successful response
            return response()->json($mangas, 200); 
        } catch (\Exception $e) {
            // Error response with message and status code
            return response()->json([
                'error' => 'Failed to retrieve manga list.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPopularMangas()
    {
        try {
            // Fetch popular mangas and paginate by 12 items per page
            $popularMangas = Manga::orderBy('views', 'desc')->paginate(12);

            // Modify each manga object with the image URL
            $popularMangas->getCollection()->transform(function ($manga) {
                $manga->imagePath = $manga->imagePath ? \Storage::disk('s3')->url($manga->imagePath) : null;
                return $manga;
            });

            // Return data successfully
            return response()->json($popularMangas, 200);

        } catch (\Exception $e) {
            // Return error response 
            return response()->json([
                'error' => 'Failed to retrieve popular mangas.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getLatestMangas(){
        try{
            // Fetch popular mangas and paginate by 12 items per page
            $latestMangas = Manga::latest()->paginate(12);

            // Modify each manga object with the image URL
            $latestMangas->getCollection()->transform(function ($manga) {
                $manga->imagePath = $manga->imagePath ? \Storage::disk('s3')->url($manga->imagePath) : null;
                return $manga;
            });

            // Return data successfully
            return response()->json($latestMangas, 200);

        } catch (\Exception $e){
            // Return error response
            return response()->json([
                'error' => 'Failed to retrieve latest mangas.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    

}
