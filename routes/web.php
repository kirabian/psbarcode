<?php

use App\Http\Livewire\ImeiGenerator;
use App\Http\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route Login
Route::get('/login', Login::class)->name('login');

// Logout
Route::get('/logout', function () {
    Session::flush();
    return redirect()->to('/login');
})->name('logout');

// Proteksi Halaman Utama
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/', ImeiGenerator::class);
});

Route::get('/barcode/{value}/{h}', function ($value, $h) {
    $generator = new BarcodeGeneratorPNG();

    return response(
        $generator->getBarcode($value, $generator::TYPE_CODE_128, 2, (int)$h),
        200,
        [
            'Content-Type'  => 'image/png',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache'
        ]
    );
});
