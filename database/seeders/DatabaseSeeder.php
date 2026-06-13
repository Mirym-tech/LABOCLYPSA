<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laboratorio;
use App\Models\User;
use App\Models\AnalisisTipo;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Roles ─────────────────────────────────────────────────────────────
        foreach (['admin', 'bioanalista', 'recepcionista'] as $rol) {
            Role::firstOrCreate(['name' => $rol, 'guard_name' => 'web']);
        }

        $permisos = ['validar-resultados', 'gestionar-usuarios', 'ver-auditoria'];
        foreach ($permisos as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        Role::findByName('admin')->givePermissionTo(Permission::all());
        Role::findByName('bioanalista')->givePermissionTo('validar-resultados');

        // ── Laboratorios ──────────────────────────────────────────────────────
        $lab1 = Laboratorio::firstOrCreate(['nombre' => 'Lab 1 — Norte'], [
            'direccion' => 'Calle Principal #1, Santiago',
            'telefono'  => '809-000-0001',
            'ciudad'    => 'Santiago',
        ]);
        $lab2 = Laboratorio::firstOrCreate(['nombre' => 'Lab 2 — Sur'], [
            'direccion' => 'Av. Central #45, Santo Domingo',
            'telefono'  => '809-000-0002',
            'ciudad'    => 'Santo Domingo',
        ]);

        // ── Usuarios ──────────────────────────────────────────────────────────
        $adminPassword = env('SUPER_ADMIN_PASSWORD')
            ? Hash::make(env('SUPER_ADMIN_PASSWORD'))
            : '$2y$10$Stwqz6X4/JY4N4ux7nD6lOYnJZzXxHMnGN5uYsoVhT21SssjfEmyu';

        $admin = User::updateOrCreate(
            ['email' => 'mirym@laboclypsa.com'],
            ['name' => 'Mirym', 'password' => $adminPassword, 'laboratorio_id' => $lab1->id, 'activo' => true]
        );
        $admin->syncRoles(['admin']);

        // Usuarios de demo: solo crear si NO existen (nunca sobreescribir datos existentes)
        foreach ([
            ['email' => 'ysabel@laboclypsa.com',    'name' => 'Ysabel Pérez',  'password' => 'Bio1234!', 'role' => 'bioanalista'],
            ['email' => 'recepcion@laboclypsa.com', 'name' => 'Recepcionista', 'password' => 'Rec1234!', 'role' => 'recepcionista'],
        ] as $demo) {
            $u = User::withTrashed()->where('email', $demo['email'])->first();
            if (!$u) {
                $u = User::create([
                    'email'          => $demo['email'],
                    'name'           => $demo['name'],
                    'password'       => Hash::make($demo['password']),
                    'laboratorio_id' => $lab1->id,
                    'activo'         => true,
                ]);
            } elseif ($u->trashed()) {
                $u->restore();
            }
            $u->syncRoles([$demo['role']]);
        }

        // ── Catálogo de Análisis ──────────────────────────────────────────────
        $catalogo = [
            ['codigo'=>'HEM-01','nombre'=>'Hemograma Completo CBC','categoria'=>'HEMATOLOGIA'],
            ['codigo'=>'HEM-02','nombre'=>'Hemograma Manual Completo','categoria'=>'HEMATOLOGIA'],
            ['codigo'=>'HEM-03','nombre'=>'Grupo Sanguíneo y Rh','categoria'=>'HEMATOLOGIA'],
            ['codigo'=>'HEM-04','nombre'=>'Eritrosedimentación','categoria'=>'HEMATOLOGIA'],
            ['codigo'=>'COA-01','nombre'=>'Tiempo de Protrombina (TP)','categoria'=>'HEMATO/COAGULACION'],
            ['codigo'=>'COA-02','nombre'=>'Tiempo Parcial Tromboplastina','categoria'=>'HEMATO/COAGULACION'],
            ['codigo'=>'COA-03','nombre'=>'Fibrinógeno','categoria'=>'HEMATO/COAGULACION'],
            ['codigo'=>'BAC-01','nombre'=>'Cultivo de Orina','categoria'=>'BACTERIOLOGIA'],
            ['codigo'=>'BAC-02','nombre'=>'Cultivo de Garganta','categoria'=>'BACTERIOLOGIA'],
            ['codigo'=>'BAC-03','nombre'=>'Cultivo de Heces Fecales','categoria'=>'BACTERIOLOGIA'],
            ['codigo'=>'BAC-04','nombre'=>'Cultivo de Esputo','categoria'=>'BACTERIOLOGIA'],
            ['codigo'=>'BAC-05','nombre'=>'Cultivo de Heridas','categoria'=>'BACTERIOLOGIA'],
            ['codigo'=>'BAC-06','nombre'=>'Cultivo de LCR','categoria'=>'BACTERIOLOGIA'],
            ['codigo'=>'BAC-07','nombre'=>'Cultivo de Secreción Vaginal','categoria'=>'BACTERIOLOGIA'],
            ['codigo'=>'SER-01','nombre'=>'Aglutininas Febriles','categoria'=>'ANTIGENOS'],
            ['codigo'=>'SER-02','nombre'=>'A.S.O','categoria'=>'ANTIGENOS'],
            ['codigo'=>'SER-03','nombre'=>'Factor Reumatoide','categoria'=>'ANTIGENOS'],
            ['codigo'=>'SER-04','nombre'=>'Proteína C Reactiva (PCR)','categoria'=>'ANTIGENOS'],
            ['codigo'=>'SER-05','nombre'=>'V.D.R.L.','categoria'=>'ANTIGENOS'],
            ['codigo'=>'SER-06','nombre'=>'HIV 1/2','categoria'=>'ANTIGENOS'],
            ['codigo'=>'COL-01','nombre'=>'Análisis de Cólera','categoria'=>'ANALISIS DE COLERA'],
            ['codigo'=>'URO-01','nombre'=>'Examen General de Orina','categoria'=>'UROANALISIS'],
            ['codigo'=>'URO-02','nombre'=>'Coprológico Seriado','categoria'=>'UROANALISIS'],
            ['codigo'=>'DIG-01','nombre'=>'Digestión en Heces','categoria'=>'DIGESTION EN HECES'],
            ['codigo'=>'VAR-01','nombre'=>'Glucosa','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-02','nombre'=>'Urea','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-03','nombre'=>'Creatinina','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-04','nombre'=>'Ácido Úrico','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-05','nombre'=>'Colesterol Total','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-06','nombre'=>'Triglicéridos','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-07','nombre'=>'HDL Colesterol','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-08','nombre'=>'LDL Colesterol','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-09','nombre'=>'TGO (AST)','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-10','nombre'=>'TGP (ALT)','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-11','nombre'=>'Bilirrubina Total','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-12','nombre'=>'TSH','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-13','nombre'=>'T3 Libre','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-14','nombre'=>'T4 Libre','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-15','nombre'=>'Prueba de Embarazo (Suero)','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-16','nombre'=>'HbA1c','categoria'=>'ANALISIS VARIOS'],
            ['codigo'=>'VAR-17','nombre'=>'Antidoping Básico','categoria'=>'ANALISIS VARIOS'],
        ];

        foreach ($catalogo as $item) {
            AnalisisTipo::firstOrCreate(
                ['codigo' => $item['codigo']],
                array_merge($item, ['precio' => 0, 'activo' => true])
            );
        }

        $this->command->info('✅ Seeders completados:');
        $this->command->info('   Labs: Lab 1 Norte / Lab 2 Sur');
        $this->command->info('   Admin: mirym@laboclypsa.com');
        $this->command->info('   Analisis: ' . count($catalogo) . ' tipos');
    }
}
