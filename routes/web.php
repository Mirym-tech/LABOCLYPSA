<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ResultadoController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\Auth\LoginController;

// ── Auth ───────────────────────────────────────────────────────────────────────
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// ── Autenticado ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'forzar-lab'])->group(function () {

    Route::get('/', fn () => redirect()->route('ordenes.index'))->name('home');

    // Selector de laboratorio activo
    Route::post('laboratorio/seleccionar/{id}', function ($id) {
        $lab = \App\Models\Laboratorio::where('id', $id)->where('activo', true)->firstOrFail();
        session(['laboratorio_activo_id' => $lab->id]);
        return back()->with('success', 'Laboratorio activo cambiado.');
    })->name('laboratorio.seleccionar');

    // Pacientes
    Route::resource('pacientes', PacienteController::class);

    // Órdenes
    Route::resource('ordenes', OrdenController::class)
        ->except(['edit', 'update', 'destroy'])
        ->parameters(['ordenes' => 'orden']);
    Route::post('ordenes/{orden}/validar', [OrdenController::class, 'validar'])->name('ordenes.validar');
    Route::post('ordenes/{orden}/agregar-analisis', [OrdenController::class, 'agregarAnalisis'])->name('ordenes.agregarAnalisis');

    // Resultados — rutas específicas por tipo de análisis
    Route::prefix('resultados')->name('resultados.')->group(function () {
        Route::get('hematologia/{oa}',    [ResultadoController::class, 'hematologia'])->name('hematologia');
        Route::post('hematologia/{oa}',   [ResultadoController::class, 'guardarHematologia'])->name('hematologia.guardar');
        Route::post('hematologia/{oa}/validar', [ResultadoController::class, 'validarHematologia'])->name('hematologia.validar');

        Route::get('bacteriologia/{oa}',  [ResultadoController::class, 'bacteriologia'])->name('bacteriologia');
        Route::post('bacteriologia/{oa}', [ResultadoController::class, 'guardarBacteriologia'])->name('bacteriologia.guardar');

        Route::get('serologia/{oa}',      [ResultadoController::class, 'serologia'])->name('serologia');
        Route::post('serologia/{oa}',     [ResultadoController::class, 'guardarSerologia'])->name('serologia.guardar');

        Route::get('colera/{oa}',         [ResultadoController::class, 'colera'])->name('colera');
        Route::post('colera/{oa}',        [ResultadoController::class, 'guardarColera'])->name('colera.guardar');

        Route::get('uroanalisis/{oa}',    [ResultadoController::class, 'uroanalisis'])->name('uroanalisis');
        Route::post('uroanalisis/{oa}',   [ResultadoController::class, 'guardarUroanalisis'])->name('uroanalisis.guardar');

        Route::get('digestion/{oa}',      [ResultadoController::class, 'digestion'])->name('digestion');
        Route::post('digestion/{oa}',     [ResultadoController::class, 'guardarDigestion'])->name('digestion.guardar');

        Route::get('varios/{oa}',         [ResultadoController::class, 'varios'])->name('varios');
        Route::post('varios/{oa}',        [ResultadoController::class, 'guardarVarios'])->name('varios.guardar');
        Route::put('varios/item/{resultado}',    [ResultadoController::class, 'actualizarVarios'])->name('varios.actualizar');
        Route::delete('varios/item/{resultado}', [ResultadoController::class, 'eliminarVarios'])->name('varios.eliminar');
    });

    // PDF / Impresión
    Route::prefix('pdf')->name('pdf.')->group(function () {
        Route::get('hematologia/{oa}',   [PdfController::class, 'hematologia'])->name('hematologia');
        Route::get('bacteriologia/{oa}', [PdfController::class, 'bacteriologia'])->name('bacteriologia');
        Route::get('colera/{oa}',        [PdfController::class, 'colera'])->name('colera');
        Route::get('uroanalisis/{oa}',   [PdfController::class, 'uroanalisis'])->name('uroanalisis');
        Route::get('coprologia/{oa}',    [PdfController::class, 'coprologia'])->name('coprologia');
        Route::get('digestion/{oa}',     [PdfController::class, 'digestion'])->name('digestion');
        Route::get('varios/{oa}',        [PdfController::class, 'varios'])->name('varios');
        Route::get('orden/{orden}',      [PdfController::class, 'ordenCompleta'])->name('orden');
    });

    // Auditoría — cualquier admin
    Route::middleware('role:admin')->group(function () {
        Route::get('auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
    });

    // Gestión de usuarios — solo mirym@laboclypsa.com
    Route::middleware(['role:admin', 'superadmin'])->group(function () {
        Route::post('usuarios/{id}/restaurar', [UsuarioController::class, 'restore'])->name('usuarios.restore');
        Route::resource('usuarios', UsuarioController::class)->except(['show']);
    });
});
