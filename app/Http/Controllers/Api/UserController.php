<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json(['user' => $user], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in when retrieving the user',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, int $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;

            $user->save();

            return response()->json(['message' => 'User details updated.'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong when updating your details',
                'error' => $e->getMessage()
            ], 400);
        }
    }

}
