<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use App\Models\Laboratorio;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Asegurar que el rol admin existe
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $lab = Laboratorio::first();

        // Hash pre-generado de 'DeanWinchester#1012'
        // Si SUPER_ADMIN_PASSWORD está definido en Railway, lo usa; si no, usa el hash por defecto.
        $password = env('SUPER_ADMIN_PASSWORD')
            ? \Illuminate\Support\Facades\Hash::make(env('SUPER_ADMIN_PASSWORD'))
            : '$2y$10$Stwqz6X4/JY4N4ux7nD6lOYnJZzXxHMnGN5uYsoVhT21SssjfEmyu';

        $user = User::updateOrCreate(
            ['email' => 'mirym@laboclypsa.com'],
            [
                'name'           => 'Mirym',
                'password'       => $password,
                'laboratorio_id' => $lab?->id,
                'activo'         => true,
            ]
        );

        $user->syncRoles(['admin']);
    }

    public function down(): void {}
};
