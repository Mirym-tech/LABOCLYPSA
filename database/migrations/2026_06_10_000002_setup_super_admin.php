<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Laboratorio;

return new class extends Migration
{
    public function up(): void
    {
        $lab = Laboratorio::first();

        $data = [
            'name'           => 'Mirym',
            'laboratorio_id' => $lab?->id,
            'activo'         => true,
        ];

        // Solo actualiza la contraseña si la variable está definida
        if ($password = env('SUPER_ADMIN_PASSWORD')) {
            $data['password'] = Hash::make($password);
        }

        $user = User::updateOrCreate(
            ['email' => 'mirym@laboclypsa.com'],
            $data
        );

        $user->syncRoles(['admin']);

        // Desactivar el admin genérico si existe
        User::where('email', 'admin@laboclypsa.com')->update(['activo' => false]);
    }

    public function down(): void {}
};
