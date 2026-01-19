<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintDemoController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\JobValidationController;
use App\Http\Controllers\MasterEnsambleController;

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
    return view('testprint');
});

Route::get('/print-demo', [PrintDemoController::class, 'index'])->name('print.demo');
Route::post('/print-demo', [PrintDemoController::class, 'print'])->name('print.demo.print');

Route::get('/job-validation', [JobValidationController::class, 'index'])->name('job.validation');
Route::post('/job-validation/reload', [JobValidationController::class, 'reload'])->name('job.validation.reload');

Route::post('/print-browser', [PrintDemoController::class, 'printBrowser'])
    ->name('print.browser');

Route::post('/print-csv', [PrintDemoController::class, 'print'])->name('print.csv');

Route::get('/templates/{name}', [TemplateController::class, 'open'])
    ->name('templates.open');

Route::get('/master-ensamble', [MasterEnsambleController::class, 'index'])
    ->name('master.ensamble');

Route::post('/master-ensamble/pdf', [MasterEnsambleController::class, 'pdf'])
    ->name('master.ensamble.pdf');