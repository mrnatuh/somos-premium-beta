<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardControllerIndex;
// use App\Http\Controllers\Preview\PreviewCreate;
use App\Http\Controllers\Preview\PreviewDelete;
use App\Livewire\Category\CategoryIndex;
use App\Livewire\Dashboard\DashboardIndex;
use App\Livewire\Preview\PreviewCreate;
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

Route::get('/', DashboardControllerIndex::class)->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
    Route::get('/categoria', CategoryIndex::class)->name('category');
    Route::get('/previa', PreviewIndex::class)->name('preview');
    Route::get('/previa/create', PreviewCreate::class)->name('preview.create');

    Route::delete('/previa/delete', PreviewDelete::class)->name('preview.delete');
});

require __DIR__ . '/auth.php';
