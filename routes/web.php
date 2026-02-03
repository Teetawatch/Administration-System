<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OutgoingDocumentController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\NavyNewsController;
use App\Http\Controllers\SchoolOrderController;
use App\Http\Controllers\SpecialOrderController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PersonnelController;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resource routes for all modules
    Route::post('export-outgoing-documents-pdf', [OutgoingDocumentController::class, 'exportPdf'])->name('outgoing-documents.export-pdf');
    Route::resource('outgoing-documents', OutgoingDocumentController::class);
    Route::get('certificates/{certificate}/pdf', [CertificateController::class, 'exportPdf'])->name('certificates.pdf');
    Route::resource('certificates', CertificateController::class);
    Route::resource('navy-news', NavyNewsController::class);
    Route::get('school-orders/{schoolOrder}/pdf', [SchoolOrderController::class, 'exportPdf'])->name('school-orders.pdf');
    Route::resource('school-orders', SchoolOrderController::class);
    Route::get('special-orders/{specialOrder}/pdf', [SpecialOrderController::class, 'exportPdf'])->name('special-orders.pdf');
    Route::resource('special-orders', SpecialOrderController::class);
    Route::get('activities/participants-report/pdf', [ActivityController::class, 'exportParticipantsReport'])->name('activities.participants-report');
    Route::resource('activities', ActivityController::class);
    
    // Personnel Routes
    Route::post('personnel/import', [PersonnelController::class, 'import'])->name('personnel.import');
    Route::get('personnel/template', [PersonnelController::class, 'downloadTemplate'])->name('personnel.template');
    Route::get('personnel/pdf', [PersonnelController::class, 'exportPdf'])->name('personnel.pdf.export');
    Route::post('personnel/reorder', [PersonnelController::class, 'reorder'])->name('personnel.reorder');
    Route::resource('personnel', PersonnelController::class);

    // Vehicle Routes
    Route::resource('vehicles', \App\Http\Controllers\VehicleController::class);
    Route::resource('vehicle-bookings', \App\Http\Controllers\VehicleBookingController::class);
    Route::resource('vehicle-drivers', \App\Http\Controllers\VehicleDriverController::class);
});

require __DIR__.'/auth.php';


