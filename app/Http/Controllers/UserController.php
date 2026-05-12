<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAll();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('name', ['super-admin', 'admin'])->get();

        return view('users.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        $this->userService->create($request->all());

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function show(string $id)
    {
        $user = $this->userService->find($id);

        return view('users.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = $this->userService->find($id);
        $roles = Role::whereNotIn('name', ['super-admin', 'admin'])->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UserRequest $request, string $id)
    {
        $this->userService->update($id, $request->all());

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy(string $id)
    {
        $this->userService->delete($id);

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
}
