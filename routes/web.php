<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;

use App\Livewire\Category\CategoryIndex;
use App\Livewire\Dashboard\DashboardIndex;
use App\Livewire\Preview\PreviewIndex;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->user() ) {
        return to_route('dashboard');
    }

    return to_route('login');
})->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    Route::get('/categoria', CategoryIndex::class)->name('categoria');
    Route::get('/previa', PreviewIndex::class)->name('previa');

    Route::prefix('dump')->group(function(){
        Route::get('/clientes', function () {
            $clientes = DB::connection('mysql_dump')->table('CLIENTES')->get();

            return [
                "clientes" => $clientes
            ];
        });

        Route::get('/orcamento', function () {
            $orcamento = DB::connection('mysql_dump')->table('ORCAMENTO')->get();

            return [
                "orcamento" => $orcamento
            ];
        });

        Route::get('/funcionarios', function () {
            $funcionarios = DB::connection('mysql_dump')->table('FUNCIONARIOS')->get();

            return [
                "funcionarios" => $funcionarios
            ];
        });
    });
});

require __DIR__.'/auth.php';
