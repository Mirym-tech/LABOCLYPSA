<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Laboratorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with(['laboratorio', 'roles'])->orderBy('name')->paginate(20);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $laboratorios = Laboratorio::where('activo', true)->get();
        $roles = Role::all();
        return view('usuarios.create', compact('laboratorios', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:8|confirmed',
            'laboratorio_id' => 'required|exists:laboratorios,id',
            'role'          => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'laboratorio_id' => $request->laboratorio_id,
            'activo'        => true,
        ]);

        $user->assignRole($request->role);
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        $laboratorios = Laboratorio::where('activo', true)->get();
        $roles = Role::all();
        return view('usuarios.edit', compact('usuario', 'laboratorios', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|unique:users,email,' . $usuario->id,
            'laboratorio_id' => 'required|exists:laboratorios,id',
            'role'          => 'required|exists:roles,name',
            'password'      => 'nullable|min:8|confirmed',
        ]);

        $usuario->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'laboratorio_id' => $request->laboratorio_id,
            'activo'        => $request->boolean('activo', true),
        ]);

        if ($request->filled('password')) {
            $usuario->update(['password' => Hash::make($request->password)]);
        }

        $usuario->syncRoles([$request->role]);
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }
}
