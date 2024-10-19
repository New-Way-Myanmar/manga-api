<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    public function index()
    {
        try {

            // Fetch category with paginate 12
            $category = Category::paginate(12); 

             // Successful response
            return response()->json($category, 200);
        } catch (\Exception $e) {
            
            // Error response with message and status code
            return response()->json([
                'error' => 'Failed to retrieve category list.',
                'message' => $e->getMessage()
            ], 500); 
        }
    }

    public function getMangasByCategory($category_id){

        try{
            // Fetch category with given id
            $category = Category::findOrFail($category_id);

            // Fetch mangas with given category
            $mangas = $category->mangas()->latest()->paginate(12);

            // Modify each manga object with the image URL
            $mangas->getCollection()->transform(function ($manga) {
                $manga->imagePath = $manga->imagePath ? \Storage::disk('s3')->url($manga->imagePath) : null;
                return $manga;
            });

            // Return data successfully
            return response()->json($mangas, 200);

        } catch(ModelNotFoundException $e){
            // Return 404 error if category not found
            return response()->json([
                'error' => 'Category not found',
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            // Return general server error
            return response()->json([
                'error' => 'Failed to retrieve mangas',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
