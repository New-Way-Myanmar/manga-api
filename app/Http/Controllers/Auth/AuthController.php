<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailOtpVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userId' => 'unique:users',
                'name' => 'required|max:30',
                'username' => 'required|max:30|unique:users',
                'email' => 'required|email|max:50|unique:users',
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required|min:8',
                'phone' => 'required|min:11|max:12|unique:users',
                'gender' => 'required|in:0,1,2',
                'dob' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $otp = rand(100000, 999999);

            $user = User::create([
                'userId' => $this->generateUserId(),
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'coin' => '0',
                'status' => '1',
                'coinUsed' => '0',
                'otp_code' => $otp,
            ]);

            \Mail::to($user->email)->send(new EmailOtpVerification($otp));

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user,
                'type' => 'bearer',
                'token' => $token,
            ], 201);
        } catch (JWTException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function verification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:50',
            'otp_code' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->otp_code != $request->otp_code) {
            return response()->json(['message' => 'Invalid OTP or email.'], 400);
        }

        $user->update([
            'email_verified_at' => Carbon::now(),
            'otp_code' => null,
        ]);

        return response()->json([
            'message' => 'User successfully verified',
        ], 200);
    }

    private function generateUserId()
    {
        // Get the last userId and extract the number part
        $lastUser = User::orderBy('id', 'desc')->first();
        $lastUserIdNumber = $lastUser ? (int)substr($lastUser->userId, 4) : 0;

        // Increment the number and format it
        $newUserIdNumber = $lastUserIdNumber + 1;

        // Return the new userId in the format "nwm-00000001"
        return 'nwm-' . str_pad($newUserIdNumber, 8, '0', STR_PAD_LEFT);
    }
}
