<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardControllerIndex;
use App\Http\Controllers\MoSaveController;
use App\Http\Controllers\Preview\PreviewDelete;
use App\Http\Controllers\Preview\PreviewEdit;
use App\Http\Controllers\PreviewStatusController;
use App\Livewire\Category\CategoryIndex;
use App\Livewire\Dashboard\DashboardIndex;
use App\Livewire\Parameters\ParametersIndex;
use App\Livewire\Preview\PreviewCreate;
use App\Livewire\Preview\PreviewIndex;
use App\Livewire\User\UserCreate;
use App\Livewire\User\UserEdit;
use App\Livewire\User\UserIndex;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

Route::get('/', DashboardControllerIndex::class)->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');

    Route::get('/categoria', CategoryIndex::class)->name('category');

    Route::post('/categoria/mo', MoSaveController::class)->name('mo.store');


    Route::get('/previa', PreviewIndex::class)->name('preview');
    Route::get('/previa/create', PreviewCreate::class)->name('preview.create');
    Route::post('/previa/edit', PreviewEdit::class)->name('preview.edit');

    Route::get('/orcamentos', function() {
        $tmp_users = [];
        $users = User::where('cc', '!=', '')->get();

        foreach ($users as $user) {
            array_push($tmp_users, $user->cc);
        }

        $orcamentos = [];

        $tmp_orcamento = DB::connection('mysql_dump')
        ->table('ORCAMENTO')
        ->get();

        foreach ($tmp_orcamento as $orc) {
            $cc = trim($orc->CV1_CTTINI);

            if (in_array($cc, $tmp_users)) {
                $dt = explode("/", $orc->CV1_DTINI);
                $month_ref = $dt[1] . '_' . substr($dt[2], -2);
                $dt = array_reverse($dt);
                $dt = join('-', $dt);

                array_push($orcamentos, [
                    'cc' => trim($orc->CV1_CTTINI),
                    'dt' => $dt,
                    'period' => $orc->CV1_PERIOD,
                    'value' => $orc->CV1_VALOR,
                    'month_ref' => $month_ref,
                    'group' => trim($orc->CT1_GRUPO),
                ]);
            }
        }

        return $orcamentos;
    });

    Route::delete('/previa/delete', PreviewDelete::class)->name('preview.delete');

    Route::post('/previa/status', PreviewStatusController::class)->name('preview.status');
});

Route::middleware(['auth', 'is-admin'])->group(function () {
    Route::get('/categoria/parametros', ParametersIndex::class)->name('category.parameters');

    Route::get('/profiles', UserIndex::class)->name('profiles.index');
    Route::get('/profiles/create', UserCreate::class)->name('profiles.create');
    Route::get('/profiles/edit/{user}', UserEdit::class)->name('profiles.edit');
});

require __DIR__ . '/auth.php';
