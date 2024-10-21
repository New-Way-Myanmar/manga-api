<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\EmailOtpVerification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileManagementController extends Controller
{
    
    public function updateProfile(Request $request)
    {
        try{

            $user = Auth::user(); // Get the currently authenticated user

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|max:30',
                'username' => 'required|max:30|unique:users,username,' . $user->id,
                'email' => 'sometimes|email|max:50|unique:users,email,' . $user->id,
                'old_password' => 'sometimes|required_with:password', 
                'new_password' => 'sometimes|min:8|confirmed',  
                'avatar' => 'sometimes|image|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            // Handle name update
            if ($request->has('name') && $request->name !== $user->name) {
                $user->update(['name' => $request->name]);
            } 

            // Handle username update
            if ($request->has('username') && $request->username !== $user->username) {
                $user->update(['username' => $request->username]);
            }

            // Handle profile photo update
            if ($request->hasFile('avatar')) {
                try{
                    // Delete the old avatar from S3 if it exists
                    if ($user->avatarPath) {
                        \Storage::disk('s3')->delete($user->avatarPath);
                    }

                    // Store the new avatar in S3
                    $path = $request->file('avatar')->store('avatars', 's3');
                    $user->update(['avatarPath' => $path]);

                } catch(\Exception $e){

                    // Return error response 
                    return response()->json([
                        'error' => 'Failed to update profile photo.',
                        'message' => $e->getMessage()
                    ], 500);
                }
            }

            // Handle password update
            if ($request->has('password')) {
                // Check if the provided old password matches the hashed password in the database
                if (!Hash::check($request->old_password, $user->password)) {
                    return response()->json(['message' => 'Old password is incorrect.'], 400);
                }

                // Update the password with the new hashed password
                $user->update(['password' => Hash::make($request->password)]);
            }

            // Handle email update with OTP verification
            if ($request->has('email') && $request->email !== $user->email) {

                $otp = rand(100000, 999999);

                // Send OTP to the old email
                \Mail::to($user->email)->send(new EmailOtpVerification($otp));

                // Store the OTP temporarily
                $user->update([
                    'otp_code' => Hash::make($otp),
                ]);

                // Store the new email in session 
                session(["new_email"=>$request->email]);

                return response()->json([
                    'message' => 'OTP sent to your old email. Please confirm to change email.',
                ], 200);
            }

            return response()->json([
                'message' => 'Profile updated successfully.',
                'user' => $user,
            ], 200);

        } catch(\Exception $e){

            // Return error response 
            return response()->json([
                'error' => 'Failed to update profile.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyOldEmailOtp(Request $request)
    {
        
        try{

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:50|unique:users,email',
                'otp_code' => 'required|digits:6',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
    
            $user = Auth::user();

            // Check if email matches
            if ($user->email != $request->email) {
                return response()->json(['message' => 'Invalid emailz.'], 400);
            }
    

            // Check if OTP matches
            if (!Hash::check($request->otp_code, $user->otp_code)) {
                return response()->json(['message' => 'Invalid OTP.'], 400);
            }
    
            // Clear OTP after successful verification
            $user->update(['otp_code' => null]);
    
            return response()->json([
                'message' => 'Old email verified. You can now change your email.',
            ], 200);

        } catch(\Exception $e){

            // Return error response 
            return response()->json([
                'error' => 'Failed to verify old email.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function changeEmail(Request $request)
    {
        try{

            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'new_email' => 'required|email|max:50|unique:users,email',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
    
            // Generate OTP for new email verification
            $otp = rand(100000, 999999);

            $user->update([
                'email' => session('new_email'),
                'otp_code' => Hash::make($otp),
                'email_verified_at' => null, 
            ]);
    
            // Send OTP to new email
            \Mail::to($user->email)->send(new EmailOtpVerification($otp));
    
            return response()->json([
                'message' => 'Email updated. OTP sent to your new email for verification.',
            ], 200);

        } catch(\Exception $e){

            // Return error response 
            return response()->json([
                'error' => 'Failed to change email.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyNewEmailOtp(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'otp_code' => 'required|digits:6',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
    
            $user = Auth::user();
    
            // Check if OTP matches the one sent to the new email
            if (!Hash::check($request->otp_code, $user->otp_code)) {
                return response()->json(['message' => 'Invalid OTP.'], 400);
            }
    
            // Mark email as verified and clear OTP
            $user->update([
                'email_verified_at' => Carbon::now(),
                'otp_code' => null,
            ]);
    
            return response()->json([
                'message' => 'New email successfully verified.',
            ], 200);

        } catch(\Exception $e){

            // Return error response 
            return response()->json([
                'error' => 'Failed to verify new email.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}


