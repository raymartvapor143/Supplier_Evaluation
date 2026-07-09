<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EndUserController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\HeadController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return redirect()->route('auth.login');
});

Route::get('/login', [AuthController::class, 'login'])->name('auth.login');

Route::post('/logincontrol', [AuthController::class, 'loginControl']);

Route::post('/register', [AuthController::class, 'register'])->name('register');


// Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');


    Route::post('/evaluation/store', [EvaluationController::class, 'store'])
        ->name('evaluation.store');

    Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/evaluations', [EvaluationController::class, 'evaluation']);


    Route::get('/evaluations/list', [EvaluationController::class, 'evaluationsList']);
    Route::get('/evaluations/count', [EvaluationController::class, 'countEvaluations']);
    Route::get('/evaluations/count-pending', [EvaluationController::class, 'countPendingEvaluations']);
    Route::get('/evaluations/count-head', [EvaluationController::class, 'countHeadEvaluations']);
    Route::get('/evaluations/count-approve', [EvaluationController::class, 'countApproveEvaluations']);
    Route::get('/showupdate/{id}', [EvaluationController::class, 'showupdate']);
    Route::get('/evaluations/{id}', [EvaluationController::class, 'show']);

    Route::put('/updateevaluations/{id}', [EvaluationController::class, 'update'])
    ->name('evaluation.update');

    Route::get('/evaluations/{id}/review-link', [EvaluationController::class, 'getReviewLink']);
    Route::get('/evaluations/{id}/download', [EvaluationController::class, 'download'])->name('evaluations.download');


Route::get('/admin-dashboard/data', [AdminController::class, 'fetchData']);

Route::get('/departments', [EvaluationController::class, 'getDepartments'])->middleware('auth');


Route::get('/admin/evaluations/summary/download', [AdminController::class, 'downloadSummary'])
    ->name('admin.evaluations.summary.download');


Route::post('/evaluation/update/{token}', [HeadController::class, 'updateEvaluation'])->name('evaluation.head.update');

Route::get('/evaluation/head-review/{token}', [HeadController::class, 'reviewPage']);

Route::get('/evaluation/review/{token}', [HeadController::class, 'reviewEvaluation'])->name('evaluation.review');


Route::post('/evaluation/validate-code/{token}', [HeadController::class, 'validateCode']);



Route::get('/requests-for-table', [EvaluationController::class, 'fetchRequestsForTable']);
Route::get('/requests-for-dropdown', [EvaluationController::class, 'fetchRequestsForDropdown']);
Route::post('/requests/store', [EvaluationController::class, 'storeRequest']);
Route::post('/requests/{request}/approve', [EvaluationController::class, 'approve']);
Route::post('/requests/{request}/reject', [EvaluationController::class, 'reject']);


Route::get('/users', [UserController::class, 'index']);
Route::get('/users/fetch', [UserController::class, 'fetchUsers']);
Route::post('/users/approve/{id}', [UserController::class, 'approve']);
Route::post('/users/reject/{id}', [UserController::class, 'reject']);





Route::get('/enduser-dashboard', [EndUserController::class, 'dashboard'])->name('enduser.dashboard');
