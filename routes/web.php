<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TempsPasseController;
use App\Http\Controllers\AdminController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/signup', [PageController::class, 'signup'])->name('signup');
Route::post('/signup', [PageController::class, 'register'])->name('register');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
    Route::get('/settings', [PageController::class, 'settings'])->name('settings');

    // Admin uniquement
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::patch('/admin/users/{user}/role', [AdminController::class, 'updateRole'])->name('admin.updateRole');
        Route::patch('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
        Route::post('/admin/projets/{projet}/collaborateurs', [AdminController::class, 'addCollaborateur'])->name('admin.addCollaborateur');
        Route::delete('/admin/projets/{projet}/collaborateurs/{user}', [AdminController::class, 'removeCollaborateur'])->name('admin.removeCollaborateur');
    });

    // Projets que admin et client peuvent créer
    Route::middleware('role:admin,client')->group(function () {
        Route::get('/projets/create', [ProjetController::class, 'create'])->name('projets.create');
        Route::post('/projets', [ProjetController::class, 'store'])->name('projets.store');
    });

    // Projets que admin peut modifier/supprimer
    Route::middleware('role:admin')->group(function () {
        Route::get('/projets/{projet}/edit', [ProjetController::class, 'edit'])->name('projets.edit');
        Route::put('/projets/{projet}', [ProjetController::class, 'update'])->name('projets.update');
        Route::delete('/projets/{projet}', [ProjetController::class, 'destroy'])->name('projets.destroy');
    });

    // Projets que tout le monde peut voir
    Route::get('/projets', [ProjetController::class, 'index'])->name('projets.index');
    Route::get('/projets/{projet}', [ProjetController::class, 'show'])->name('projets.show');

    // Tickets pour admin et collaborateur
    Route::middleware('role:admin,collaborateur')->group(function () {
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    });

    // Tickets que l'admin peut supprimer
    Route::middleware('role:admin')->group(function () {
        Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    });

    // Tickets où tout le monde peut voir
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

    // Temps passé pour admin et collaborateur
    Route::middleware('role:admin,collaborateur')->group(function () {
        Route::post('/tickets/{ticket}/temps', [TempsPasseController::class, 'store'])->name('temps.store');
        Route::delete('/temps/{tempsPasse}', [TempsPasseController::class, 'destroy'])->name('temps.destroy');
    });
});

require __DIR__.'/auth.php';