<?php

namespace App\Http\Controllers\WebAPI;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponse;

class UserController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $this->authorize('user.view');
        $users = User::latest()->paginate(10);
        return $this->paginated($users, 'Users fetched successfully');
    }

    public function store(Request $request)
    {
        $this->authorize('user.create');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'dob' => 'nullable|date',
            'profile_image' => 'nullable|image',
            'description' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $user->assignRole('user');

        return $this->success($user, 'User created successfully');
    }

    public function show($id)
    {
        $this->authorize('user.view');

        $user = User::findOrFail($id);
        return $this->success($user, 'User fetched successfully');
    }

    public function update(Request $request, $id)
    {
        $this->authorize('user.update');

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6',
            'dob' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return $this->success($user, 'User updated successfully');
    }

    public function destroy($id)
    {
        $this->authorize('user.delete');

        $user = User::findOrFail($id);
        $user->delete();

        return $this->success([], 'User deleted (soft) successfully');
    }

    public function toggleStatus($id)
    {
        $this->authorize('user.change-status');

        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return $this->success([
            'id' => $user->id,
            'is_active' => $user->is_active,
        ], 'User status updated successfully');
    }
}
