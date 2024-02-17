<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
        // Get all users
        public function index()
        {
            $users = User::all();
            $users = $this->addCreatedFromColumn($users);
            return response()->json($users);
        }

        // Get a specific user
        public function show($id)
        {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            $user = $this->addCreatedFromColumn([$user])->first();
            return response()->json($user);
        }

        // Create a new user
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
            ]);

            $user = User::create($request->all());

            return response()->json($user, 201);
        }

        // Update a user
        public function update(Request $request, $id)
        {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $id,
            ]);

            $user->update($request->all());

            return response()->json($user, 200);
        }

        // Delete a user
        public function destroy($id)
        {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $user->delete();

            return response()->json(null, 204);
        }

        private function addCreatedFromColumn($users)
        {
            $collection = collect($users);

            return $collection->map(function ($user) {
                $user->created_from = $this->calculateCreatedAt($user->created_at);
                return $user;
            });
        }
        private function calculateCreatedAt($created_at)
        {
            $diff = now()->diffInHours($created_at);
            $created_from = $diff == 1 ? $diff . ' hour ago' : $diff . ' hours ago';

            return $created_from;
        }
}
