<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Restaura usuarios eliminados accidentalmente por el bug de formularios anidados.
        // El bug causaba que "Actualizar" disparara un DELETE en lugar de UPDATE.
        DB::table('users')
            ->whereNotNull('deleted_at')
            ->update(['deleted_at' => null]);
    }

    public function down(): void {}
};
