<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Laboratorio;

return new class extends Migration
{
    public function up(): void
    {
        $password = env('SUPER_ADMIN_PASSWORD');
        if (!$password) return;

        $lab = Laboratorio::first();

        $user = User::updateOrCreate(
            ['email' => 'mirym@laboclypsa.com'],
            [
                'name'           => 'Mirym',
                'password'       => Hash::make($password),
                'laboratorio_id' => $lab?->id,
                'activo'         => true,
            ]
        );

        $user->syncRoles(['admin']);

        // Desactivar el admin genérico si existe
        User::where('email', 'admin@laboclypsa.com')->update(['activo' => false]);
    }

    public function down(): void {}
};
