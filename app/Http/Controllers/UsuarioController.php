<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Laboratorio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::withTrashed()
            ->with(['laboratorio', 'roles'])
            ->orderByRaw('deleted_at IS NOT NULL')
            ->orderBy('name')
            ->paginate(50);
        return view('usuarios.index', compact('usuarios'));
    }

    public function restore(int $id)
    {
        $usuario = User::withTrashed()->findOrFail($id);
        $usuario->restore();
        cache()->forget('bioanalistas_activos');
        return redirect()->route('usuarios.index')->with('success', "Usuario \"{$usuario->name}\" restaurado correctamente.");
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
            'name'           => 'required|string|max:100',
            'email'          => ['required', 'email', Rule::unique('users')->whereNull('deleted_at')],
            'password'       => 'required|min:8|confirmed',
            'laboratorio_id' => 'required|exists:laboratorios,id',
            'role'           => 'required|exists:roles,name',
        ]);

        // Si existe un usuario eliminado con ese email, restaurarlo en lugar de duplicar
        $user = User::withTrashed()->where('email', $request->email)->first();
        if ($user && $user->trashed()) {
            $user->restore();
            $user->update([
                'name'           => $request->name,
                'password'       => $request->password,
                'laboratorio_id' => $request->laboratorio_id,
                'activo'         => true,
            ]);
        } else {
            $user = User::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'password'       => $request->password,
                'laboratorio_id' => $request->laboratorio_id,
                'activo'         => true,
            ]);
        }

        $user->syncRoles([$request->role]);
        cache()->forget('bioanalistas_activos');
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
            'email'         => ['required', 'email', Rule::unique('users', 'email')->ignore($usuario->id)->whereNull('deleted_at')],
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
            $usuario->update(['password' => $request->password]);
        }

        $usuario->syncRoles([$request->role]);
        cache()->forget('bioanalistas_activos');
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $nombre = $usuario->name;
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', "Usuario \"{$nombre}\" eliminado.");
    }
}
