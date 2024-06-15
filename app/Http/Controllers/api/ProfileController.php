<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            return response()->json([
                'status' => true,
                'message' => 'Get profile successful',
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|string|email',
            'password' => 'sometimes|required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'data' => $validator->errors()
            ]);
        }

        $user = Auth::user();

        Log::info('User: ' . json_encode($user)); // Debugging line

        if ($user) {
            if ($request->has('name')) $user->name = $request->name;
            if ($request->has('email')) $user->email = $request->email;
            if ($request->has('password')) $user->password = bcrypt($request->password);

            $user->save(); // Ensure $user is an instance of User model

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => $user->id
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }
    }
}
