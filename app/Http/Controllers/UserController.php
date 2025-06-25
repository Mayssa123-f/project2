<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
 public function index()
{
    $users = User::all(); 

    return response()->json($users);
}
   public function store(Request $request)
{
    $formfields = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|string|min:8'
    ]);
    $existingUser = User::where('email', $formfields['email'])->first();

    if ($existingUser) {
        return response()->json([
            'message' => 'Email already exists. Please use a different email.'
        ], 409);
    }
    $formfields['password'] = bcrypt($formfields['password']);

    $user = User::create($formfields);

    return response()->json([
        'message' => 'User created successfully',
        'data' => $user
    ], 201);
}

   public function update(Request $request, User $user)
{
    $formfields = $request->validate([
        'name' => 'required|string|sometimes',
        'email' => 'required|sometimes|email|unique:users,email,' . $user->id,
        'password' => 'required|sometimes|string|min:8'
    ]);

       if (isset($formfields['password'])) {
        $formfields['password'] = bcrypt($formfields['password']);
    }

    $user->update($formfields);

    return response()->json([
        'message' => 'User updated successfully.',
        'data' => $user
    ],201);
}

public function destroy(User $user){
    $user->delete();
    return response()->json([
        'message' => 'User deleted successfully.'
    ]);

}
public function exportUsers()
{
    return Excel::download(new UsersExport, 'users.xlsx');
}
}
