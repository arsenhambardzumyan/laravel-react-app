<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        $user = $request->user();
        return response()->json(['user' => $user]);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|max:20|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 422);
        }

        $user->update(['password' => Hash::make($request->input('new_password'))]);

        return response()->json(['message' => 'Password updated successfully.']);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->hasAccessToAdminModule()) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $users = User::paginate(10);

        return response()->json(['users' => $users]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->hasAccessToAdminModule()) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8|max:20|confirmed|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json(['message' => 'User created successfully.']);
    }

    public function show(Request $request, User $user)
    {
        $authenticatedUser = $request->user();

        if (!$authenticatedUser->hasAccessToAdminModule() && $authenticatedUser->id !== $user->id) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        return response()->json(['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $authenticatedUser = $request->user();

        if (!$authenticatedUser->hasAccessToAdminModule() && $authenticatedUser->id !== $user->id) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => [
                'string',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'string|min:8|max:20|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return response()->json(['message' => 'User updated successfully.']);
    }

    public function blockUser(Request $request, User $user)
    {
        $authenticatedUser = $request->user();

        if (!$authenticatedUser->hasAccessToAdminModule()) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $user->update(['blocked' => !$user->blocked]);

        return response()->json(['message' => 'User ' . ($user->blocked ? 'blocked' : 'unblocked') . ' successfully.']);
    }
}
