<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProfileController extends Controller
{
    use ApiResponse;

    public function view(Request $request)
    {
        return $this->success($request->user(), 'Profile fetched');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'description' => 'nullable|string',
            'profile_image' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $user = $request->user();

        $user->update($request->only('name', 'dob', 'description', 'profile_image'));

        return $this->success($user, 'Profile updated');
    }

}
