<?php

use App\Helpers\UserRole;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardControllerIndex;
use App\Http\Controllers\MoSaveController;
use App\Http\Controllers\Preview\PreviewDelete;
use App\Http\Controllers\Preview\PreviewEdit;
use App\Http\Controllers\Preview\PreviewExport;
use App\Http\Controllers\PreviewSaveController;
use App\Http\Controllers\PreviewStatusController;
use App\Livewire\Category\CategoryIndex;
use App\Livewire\CategoryDone\CategoryDoneIndex;
use App\Livewire\Dashboard\DashboardIndex;
use App\Livewire\Parameters\ParametersIndex;
use App\Livewire\Preview\PreviewCreate;
use App\Livewire\Preview\PreviewDoneIndex;
use App\Livewire\Preview\PreviewIndex;
use App\Livewire\User\UserCreate;
use App\Livewire\User\UserEdit;
use App\Livewire\User\UserIndex;
use App\Models\Option;
use App\Models\Preview;
use App\Models\User;
use Illuminate\Http\Request;
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

  Route::get('/realizado', PreviewDoneIndex::class)->name('preview.done');

  Route::get('/previa', PreviewIndex::class)->name('preview');
  Route::get('/previa/create', PreviewCreate::class)->name('preview.create');
  Route::post('/previa/edit', PreviewEdit::class)->name('preview.edit');

  Route::get('/previa/{id}/export', PreviewExport::class)->name('preview.export');

  Route::get('/v1/orcamento', function (Request $request) {
    if (!auth()->user()) {
      abort(response()->json('Unauthorized', 401));
    }

    $ccs = (new UserRole())->getCc();

    if (sizeof($ccs) == 0) {
      return response()->json([
        "error" => true,
      ], 400);
    }

    $tmp_data_orcamento = [];

    $tmp_res_orcamento = DB::connection('mysql_dump')
      ->table('ORCAMENTO')
      ->whereIn('CV1_CTTINI', $ccs)
      ->get();

    foreach ($tmp_res_orcamento as $orc) {
      $cc = trim($orc->CV1_CTTINI);
      $dt = explode("/", $orc->CV1_DTINI);
      $month_ref = $dt[1] . '_' . substr($dt[2], -2);
      $group = trim($orc->CT1_GRUPO);
      $total_group = [];

      $dt = array_reverse($dt);
      $dt = join('-', $dt);

      if (!isset($tmp_data_orcamento[$cc])) {
        $tmp_data_orcamento[$cc] = [];
      }

      if (!isset($tmp_data_orcamento[$cc][$month_ref])) {
        $tmp_data_orcamento[$cc][$month_ref] = [];
      }

      if (!isset($tmp_data_orcamento[$cc][$month_ref][$group]) && !empty($group)) {
        $tmp_data_orcamento[$cc][$month_ref][$group]['total'] = 0;
      }

      if (!empty($group)) {
        $tmp_data_orcamento[$cc][$month_ref][$group]['total'] += (float) $orc->CV1_VALOR;
      }
    }

    return response()->json($tmp_data_orcamento, 200);
  });

  Route::get('/v1/reset', function (Request $request) {
    try {
      $options = Option::all();
      foreach ($options as $option) {
        $option->delete();
      }

      $previews = Preview::all();
      foreach ($previews as $preview) {
        $preview->delete();
      }
    } catch (Exception $error) {
      dd($error);
      return response()->json(["error" => true], 500);
    }

    return response()->json(["ok" => 1], 200);
  });

  Route::delete('/previa/delete', PreviewDelete::class)->name('preview.delete');

  Route::post('/previa/status', PreviewStatusController::class)->name('preview.status');

  Route::post('/previa/{preview}/publish', [PreviewSaveController::class, 'publish'])->name('preview.publish');

  Route::get('/previa/{preview}/redirect', [PreviewSaveController::class, 'redirect'])->name('preview.redirect');

  Route::get('/previa/{preview}/clear', [PreviewSaveController::class, 'clear'])->name('preview.clear');

  Route::post('/previa/{preview}/approve/{level}', [PreviewSaveController::class, 'approve'])->name('preview.approve');

  Route::post('/previa/{preview}/reprove/{level}', [PreviewSaveController::class, 'reprove'])->name('preview.reprove');

  Route::get('/import/users', function () {
    $tmp_created_users = User::get()->toArray();
    $tmp_dump_css = DB::connection('mysql_dump')->table('CCUSTO')->get()->toArray();

    $tmp_users = array_map(function ($user) {
      return $user['name'];
    }, $tmp_created_users);

    $tmp_ccs = array_map(function ($cc) {
      return trim($cc->CTT_CUSTO);
    }, $tmp_dump_css);

    $diff_users = array_diff($tmp_ccs, $tmp_users);

    if (sizeof($diff_users) > 0) {
      foreach ($diff_users as $diff_user) {
        User::create([
          'name' => $diff_user,
          'email' => $diff_user . '@teste.com',
          'password' => bcrypt('password'),
          'cc' => $diff_user,
        ]);
      }

      return response()->json([], 201);
    }

    return response()->json([], 304);
  })->name('users.import');
});

Route::middleware(['auth', 'is-admin'])->group(function () {
  Route::get('/categoria/parametros', ParametersIndex::class)->name('category.parameters');

  Route::get('/profiles', UserIndex::class)->name('profiles.index');
  Route::get('/profiles/create', UserCreate::class)->name('profiles.create');
  Route::get('/profiles/edit/{user}', UserEdit::class)->name('profiles.edit');
});

require __DIR__ . '/auth.php';
