<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                'name',
                'email'
            ])
            ->allowedSorts(['id', 'name', 'email', 'created_at'])
            ->paginate($request->get('perPage', 10))
            ->appends($request->query());

        return response(['success' => true, 'data' => $users]);
    }
    public function show(User $user)
    {
        return response(['success' => true, 'data' => $user]);
    }
    public function store(Request $request)
    {
        $formfields = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create($formfields);

        return response(['success' => true, 'data' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $formfields = $request->validate([
            'name' => ['nullable', 'string'],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->update($formfields);

        return response(['success' => true, 'data' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response(['success' => true], Response::HTTP_NO_CONTENT);
    }
}
