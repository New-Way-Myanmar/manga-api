<?php

namespace App\Http\Controllers\Contact;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContactController extends Controller
{
    public function index()
    {
        try {
            // Fetch contact with paginate 12
            $contacts = Contact::paginate(12); 

            // Successful response
            return response()->json($contacts, 200); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve contact list.',
                'message' => $e->getMessage()
            ], 500); // Error response with message and status code
        }
    }

    public function store(Request $request){
        try{
            // validate data
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:100',
                'email' => 'required|email',
                'message' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                // response error if validate fail
                return response()->json($validator->errors(), 400);
            }

            // Create the contact 
            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message
            ]);

            // Return the created contact as a response
            return response()->json([
                'message' => 'Contact submitted successfully!',
                'data' => $contact,
            ], 201);


        } catch(\Exception $e) {

            // Return error response 
            return response()->json([
                'error' => 'Failed to submit contact.',
                'message' => $e->getMessage()
            ], 500);
        }    

    }

    
}
