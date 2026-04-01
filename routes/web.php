<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\BoardMemberController;
use App\Http\Controllers\Admin\DiklatRegistrationController;
use App\Http\Controllers\Admin\DiklatPeriodController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiklatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\StrukturController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
Route::get('/struktur', [StrukturController::class, 'index'])->name('struktur.index');
Route::get('/prestasi', [PrestasiController::class, 'index'])->name('prestasi.index');
Route::get('/prestasi/{achievement}', [PrestasiController::class, 'show'])->name('prestasi.show');
Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
Route::get('/kegiatan/{activity}', [KegiatanController::class, 'show'])->name('kegiatan.show');

// Diklat Registration (Public)
Route::get('/diklat/register', [DiklatController::class, 'create'])->name('diklat.register');
Route::post('/diklat/register', [DiklatController::class, 'store'])->name('diklat.store');
Route::get('/diklat/success', [DiklatController::class, 'success'])->name('diklat.success');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (Protected - Only super_admin and pengurus)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.access'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    
    // Diklat Periods Management
    Route::get('/diklat/periods', [DiklatPeriodController::class, 'index'])->name('diklat.periods.index');
    Route::get('/diklat/periods/create', [DiklatPeriodController::class, 'create'])->name('diklat.periods.create');
    Route::post('/diklat/periods', [DiklatPeriodController::class, 'store'])->name('diklat.periods.store');
    Route::get('/diklat/periods/{period}/edit', [DiklatPeriodController::class, 'edit'])->name('diklat.periods.edit');
    Route::put('/diklat/periods/{period}', [DiklatPeriodController::class, 'update'])->name('diklat.periods.update');
    Route::patch('/diklat/periods/{period}/toggle', [DiklatPeriodController::class, 'toggleOpen'])->name('diklat.periods.toggle');
    Route::delete('/diklat/periods/{period}', [DiklatPeriodController::class, 'destroy'])->name('diklat.periods.destroy');
    
    // Diklat Registration Management
    Route::get('/diklat', [DiklatRegistrationController::class, 'index'])->name('diklat.index');
    Route::get('/diklat/{registration}', [DiklatRegistrationController::class, 'show'])->name('diklat.show');
    Route::patch('/diklat/{registration}/status', [DiklatRegistrationController::class, 'updateStatus'])->name('diklat.update-status');
    Route::post('/diklat/periods/{period}/accept-all', [DiklatRegistrationController::class, 'acceptAll'])->name('diklat.accept-all');
    Route::post('/diklat/accept-all-global', [DiklatRegistrationController::class, 'acceptAllGlobal'])->name('diklat.accept-all-global');
    Route::delete('/diklat/{registration}', [DiklatRegistrationController::class, 'destroy'])->name('diklat.destroy');
    
    // Member Management
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
    Route::patch('/members/{member}/status', [MemberController::class, 'updateStatus'])->name('members.update-status');
    Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
    
    // Board Member (Struktur Pengurus) Management
    Route::get('/board', [BoardMemberController::class, 'index'])->name('board.index');
    Route::get('/board/search-members', [BoardMemberController::class, 'searchMembers'])->name('board.search-members');
    Route::post('/board', [BoardMemberController::class, 'store'])->name('board.store');
    Route::put('/board/{boardMember}', [BoardMemberController::class, 'update'])->name('board.update');
    Route::patch('/board/{boardMember}/toggle-status', [BoardMemberController::class, 'toggleStatus'])->name('board.toggle-status');
    Route::post('/board/{boardMember}/create-account', [BoardMemberController::class, 'createAccount'])->name('board.create-account');
    Route::delete('/board/{boardMember}', [BoardMemberController::class, 'destroy'])->name('board.destroy');
    Route::post('/board/reorder', [BoardMemberController::class, 'reorder'])->name('board.reorder');
    
    // Achievement (Galeri Prestasi) Management
    Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
    Route::get('/achievements/create', [AchievementController::class, 'create'])->name('achievements.create');
    Route::post('/achievements', [AchievementController::class, 'store'])->name('achievements.store');
    Route::get('/achievements/{achievement}', [AchievementController::class, 'show'])->name('achievements.show');
    Route::get('/achievements/{achievement}/edit', [AchievementController::class, 'edit'])->name('achievements.edit');
    Route::put('/achievements/{achievement}', [AchievementController::class, 'update'])->name('achievements.update');
    Route::patch('/achievements/{achievement}/toggle-publish', [AchievementController::class, 'togglePublish'])->name('achievements.toggle-publish');
    Route::delete('/achievements/{achievement}', [AchievementController::class, 'destroy'])->name('achievements.destroy');
    
    // Activity (Galeri Kegiatan) Management
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
    Route::get('/activities/{activity}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
    Route::put('/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
    Route::patch('/activities/{activity}/toggle-publish', [ActivityController::class, 'togglePublish'])->name('activities.toggle-publish');
    Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    
    // Document Template Management
    Route::get('/templates', [App\Http\Controllers\Admin\DocumentTemplateController::class, 'index'])->name('templates.index');
    Route::post('/templates', [App\Http\Controllers\Admin\DocumentTemplateController::class, 'store'])->name('templates.store');
    Route::put('/templates/{template}', [App\Http\Controllers\Admin\DocumentTemplateController::class, 'update'])->name('templates.update');
    Route::delete('/templates/{template}', [App\Http\Controllers\Admin\DocumentTemplateController::class, 'destroy'])->name('templates.destroy');
    Route::get('/templates/{template}/download', [App\Http\Controllers\Admin\DocumentTemplateController::class, 'download'])->name('templates.download');
    Route::get('/templates/{template}/preview', [App\Http\Controllers\Admin\DocumentTemplateController::class, 'preview'])->name('templates.preview');
    
    // Letter Archive (Arsip Surat) Management
    Route::get('/letters', [App\Http\Controllers\Admin\LetterArchiveController::class, 'index'])->name('letters.index');
    Route::post('/letters', [App\Http\Controllers\Admin\LetterArchiveController::class, 'store'])->name('letters.store');
    Route::get('/letters/{letter}', [App\Http\Controllers\Admin\LetterArchiveController::class, 'show'])->name('letters.show');
    Route::delete('/letters/{letter}', [App\Http\Controllers\Admin\LetterArchiveController::class, 'destroy'])->name('letters.destroy');
    Route::get('/letters/{letter}/download', [App\Http\Controllers\Admin\LetterArchiveController::class, 'download'])->name('letters.download');
    Route::get('/letters/{letter}/preview', [App\Http\Controllers\Admin\LetterArchiveController::class, 'preview'])->name('letters.preview');
    
    // Studio Booking Management
    Route::get('/studio-bookings', [App\Http\Controllers\Admin\StudioBookingController::class, 'index'])->name('studio-bookings.index');
    Route::get('/studio-bookings/create', [App\Http\Controllers\Admin\StudioBookingController::class, 'create'])->name('studio-bookings.create');
    Route::post('/studio-bookings', [App\Http\Controllers\Admin\StudioBookingController::class, 'store'])->name('studio-bookings.store');
    Route::get('/studio-bookings/{booking}', [App\Http\Controllers\Admin\StudioBookingController::class, 'show'])->name('studio-bookings.show');
    Route::patch('/studio-bookings/{booking}/approve', [App\Http\Controllers\Admin\StudioBookingController::class, 'approve'])->name('studio-bookings.approve');
    Route::patch('/studio-bookings/{booking}/reject', [App\Http\Controllers\Admin\StudioBookingController::class, 'reject'])->name('studio-bookings.reject');
    Route::delete('/studio-bookings/{booking}', [App\Http\Controllers\Admin\StudioBookingController::class, 'destroy'])->name('studio-bookings.destroy');
    
    // User Management (Super Admin only)
    Route::middleware('super.admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
