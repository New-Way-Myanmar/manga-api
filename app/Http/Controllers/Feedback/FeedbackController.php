<?php

namespace App\Http\Controllers\Feedback;

use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FeedbackController extends Controller
{
    public function index()
    {
        try {
            // Fetch feedback with paginate 12
            $feedbacks = Feedback::paginate(12); 

            // Successful response
            return response()->json($feedbacks, 200); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve feedback list.',
                'message' => $e->getMessage()
            ], 500); // Error response with message and status code
        }
    }

    public function store(Request $request){
        try{
            if(Auth::check()){
                // validate data
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:users,id',
                    'message' => 'required|string|max:1000',
                ]);

                if ($validator->fails()) {
                    // response error if validate fail
                    return response()->json($validator->errors(), 400);
                }

                // Create the feedback 
                $feedback = Feedback::create([
                    'user_id' => Auth::user()->user_id,
                    'message' => $request->message
                ]);
            } else {
                // validate data
                $validator = Validator::make($request->all(), [
                    'message' => 'required|string|max:1000',
                ]);

                if ($validator->fails()) {
                    // response error if validate fail
                    return response()->json($validator->errors(), 400);
                }

                // Create the feedback 
                $feedback = Feedback::create([
                    'message' => $request->message
                ]);
            }

            // Return the created feedback as a response
            return response()->json([
                'message' => 'Feedback submitted successfully!',
                'data' => $feedback,
            ], 201);


        } catch(\Exception $e) {

            // Return error response 
            return response()->json([
                'error' => 'Failed to submit feedback.',
                'message' => $e->getMessage()
            ], 500);
            
        }    

    }


}
