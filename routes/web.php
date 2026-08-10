<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorizeController;
use App\Http\Controllers\BulkEvaluationController;
use App\Http\Controllers\EndUserController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\HeadController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\PgsoController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgotPasswordController;
use App\Models\Evaluation;
use App\Models\Office;
use App\Models\Pdf;
use App\Models\PurchaseOrder;
use App\Models\Requests;
use App\Models\User;
use Illuminate\Support\Facades\Route;



Route::get(
    '/forgot-password/puzzle',
    [ForgotPasswordController::class, 'getPuzzle']
);

Route::get(
    '/register/puzzle',
    [AuthController::class, 'getRegisterPuzzle']
);

Route::post(
    '/forgot-password',
    [ForgotPasswordController::class,'send']
)->middleware('throttle:5,1');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update')
    ->middleware('throttle:5,1');


Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', [
        'token' => $token
    ]);
})->name('password.reset');

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
Route::get('/refresh-csrf', function () {
    return response()->json(['token' => csrf_token()]);
});

Route::get('/', function () {
    if (auth()->check()) {

        $user = auth()->user();

        if ($user->role === 'administrator') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'end_user') {
            return redirect()->route('enduser.dashboard');
        }
        if ($user->role === 'presentative_staff') {
            return redirect()->route('enduser.dashboard');
        }
        if ($user->role === 'head') {
            return redirect()->route('head.dashboard');
        }
    }

    return redirect()->route('auth.login');
});

Route::get('/login', function () {
    if (auth()->check()) {

        $user = auth()->user();

        // Redirect based on role
        if ($user->role === 'administrator') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'end_user') {
            return redirect()->route('enduser.dashboard');
        }
        if ($user->role === 'head') {
            return redirect()->route('head.dashboard');
        }

        if ($user->role === 'presentative_staff') {
            return redirect()->route('enduser.dashboard');
        }

        // fallback
        return redirect('/');
    }

    return app(App\Http\Controllers\AuthController::class)->login();
})->name('auth.login');



Route::get('/Privacy', [PrivacyController::class, 'privacy'])->name('privacy.privacy');


Route::post('/login-status', [AuthController::class, 'loginStatus']);

Route::get('/api/check-auth', function () {
    if (auth()->check()) {
        return response()->json(['authenticated' => true]);
    }

    return response()->json(['authenticated' => false], 401);
});

Route::post('/logincontrol', [AuthController::class, 'loginControl'])->middleware('throttle:10,1');

Route::post('/register', [AuthController::class, 'register'])->name('register')->middleware('throttle:5,1');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'role:administrator'])->group(function () {


    Route::get('/admin/activity-logs', [AdminController::class, 'activityLogs']);

    // Threat Scanner & Security Routes
    Route::get('/admin/threat-scanner', [AdminController::class, 'threatScannerView'])->name('admin.threat_scanner');
    Route::get('/admin/threat-scanner/scan', [AdminController::class, 'runThreatScan']);
    Route::post('/admin/threat-scanner/delete', [AdminController::class, 'deleteThreatFile']);
    Route::post('/admin/threat-scanner/delete-all', [AdminController::class, 'deleteAllThreats']);

    Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin-dashboard/data', [AdminController::class, 'fetchData']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/fetch', [UserController::class, 'fetchUsers']);
    Route::post('/users/approve/{id}', [UserController::class, 'approve']);
    Route::post('/users/reject/{id}', [UserController::class, 'reject']);


    Route::get('/authorization-users/fetch', [UserController::class, 'fetchAuthorizationUsers']);
    Route::get('/authorization-letter/{id}', [UserController::class, 'downloadAuthorizationLetter']);
    Route::post('/authorization-users/{id}/status', [UserController::class, 'updateStatus']);



    Route::get('/admin/evaluations/summary/download', [AdminController::class, 'downloadSummary'])
        ->name('admin.evaluations.summary.download');

Route::post(
    '/purchase-orders/{id}/upload-pdf',
    [PurchaseOrderController::class, 'uploadPdf']
)->name('po.upload.pdf');


// Bar chart
Route::get('/analytics/departments', [AdminController::class, 'getDepartments']);
Route::get('/analytics/department/{department}/suppliers', [AdminController::class, 'getDepartmentSuppliers']);
// Line chart
Route::get('/analytics/department/{department}/monthly-evaluations', [AdminController::class, 'getMonthlyEvaluations']);
// Semester chart & PDF export
Route::get('/analytics/department/{department}/semester-evaluations', [AdminController::class, 'getSemesterEvaluations']);
Route::get('/analytics/semester-evaluations/download', [AdminController::class, 'downloadSemesterSummary'])
    ->name('admin.evaluations.semester.download');


Route::post('/purchase-orders/import', [PurchaseOrderController::class, 'import'])->name('po.import');
Route::post('/purchase-orders/store', [PurchaseOrderController::class, 'store'])
    ->name('po.store');
Route::delete('/purchase-orders/{id}', [PurchaseOrderController::class, 'destroy'])
    ->name('po.delete');

Route::put('/purchase-orders/{id}', [PurchaseOrderController::class, 'update']);

Route::get('/offices/list', [OfficeController::class, 'list']);
Route::post('/offices/store', [OfficeController::class, 'store']);
Route::delete('/offices/delete/{id}', [OfficeController::class, 'delete']);
// Route::get('/offices/count', [OfficeController::class, 'count']);

Route::post('/users/{id}/status', [UserController::class, 'updateStatus']);

Route::post('/offices/import', [OfficeController::class, 'import']);
});


Route::middleware(['auth', 'role:end_user,presentative_staff'])->group(function () {
    Route::get('/enduser-dashboard', [EndUserController::class, 'dashboard'])
        ->name('enduser.dashboard');
});


Route::middleware(['auth', 'role:head'])->group(function () {

    Route::get('/head-dashboard', [HeadController::class, 'dashboard'])
        ->name('head.dashboard');
});



Route::middleware(['auth', 'role:pgso'])->group(function () {

Route::get('/pgso-dashboard', [PgsoController::class, 'dashboard'])
    ->name('pgso.dashboard');
});

Route::middleware(['auth', 'role:administrator,end_user,pgso,head,presentative_staff'])->group(function () {

    Route::get('/signature/{user}', [UserController::class, 'signature'])
    ->name('signature');


Route::prefix('bulk-evaluation')
    ->name('bulk-evaluation.')
    ->group(function () {

        // =========================
        // PO LIST
        // =========================
        Route::get('/po-list', [BulkEvaluationController::class, 'getPOList'])
            ->name('po-list');

        // =========================
        // SUPPLIERS BY END USER
        // =========================
        Route::get('/suppliers-by-end-user', [BulkEvaluationController::class, 'getSuppliersByEndUser'])
            ->name('suppliers-by-end-user');

        // =========================
        // STORE SELECTED POS
        // =========================
        Route::post('/store-pos', [BulkEvaluationController::class, 'storeBulkPOEvaluation'])
            ->name('store-pos');


    });

    Route::middleware(['auth'])->group(function () {
    Route::get('/bulk-evaluation', [BulkEvaluationController::class, 'bulkSupplierPage'])
        ->name('bulk.page');
    Route::get('/evaluations/bulk/fetch', [BulkEvaluationController::class, 'fetchEvaluations']);
    Route::post('/evaluations/bulk/save', [BulkEvaluationController::class, 'saveSelected']);
    Route::get('/evaluations/bulk-suppliers', [BulkEvaluationController::class, 'getSuppliers']);

    Route::post('/evaluations/bulk-store', [BulkEvaluationController::class, 'bulkStore'])
    ->name('evaluations.bulkStore');
    });
Route::get(
    '/evaluations/bulk/{evaluation}/data',
    [BulkEvaluationController::class,
    'showBulkEvaluationData']
);

Route::get('/purchase-orders/pdf/{id}', [PurchaseOrderController::class, 'viewPdf'])
    ->name('po.view.pdf');









    Route::get('/evaluations/list', [EvaluationController::class, 'evaluationsList']);
    // Route::get('/evaluations/count', [EvaluationController::class, 'countEvaluations']);
    // Route::get('/users/pending/count', [UserController::class, 'countPendingUsers']);
    Route::get('/evaluations/count-pending', [EvaluationController::class, 'countPendingEvaluations']);
    Route::get('/evaluations/count-head', [EvaluationController::class, 'countHeadEvaluations']);
    Route::get('/evaluations/count-approve', [EvaluationController::class, 'countApproveEvaluations']);
    Route::get('/showupdate/{id}', [EvaluationController::class, 'showupdate']);
    Route::get('/evaluations/{id}', [EvaluationController::class, 'show']);
    Route::post('/evaluation/store', [EvaluationController::class, 'store'])->name('evaluation.store');
    Route::put('/updateevaluations/{id}', [EvaluationController::class, 'update'])
    ->name('evaluation.update');

    Route::get('/evaluations/{id}/review-link', [EvaluationController::class, 'getReviewLink']);
    Route::get('/evaluations/{id}/download', [EvaluationController::class, 'download'])->name('evaluations.download');
    Route::get('/departments', [EvaluationController::class, 'getDepartments'])->middleware('auth');
    Route::get('/requests-for-table', [EvaluationController::class, 'fetchRequestsForTable']);
    Route::get('/requests-for-dropdown', [EvaluationController::class, 'fetchRequestsForDropdown']);
    Route::post('/requests/store', [EvaluationController::class, 'storeRequest']);

    Route::post('/requests/{request}/cancel', [EvaluationController::class, 'cancel']);



    Route::post('/user/change-password', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/delete/evaluations/{id}', [EvaluationController::class, 'destroy']);

    Route::get('/evaluations/deleted/list', [EvaluationController::class, 'deletedList']);
    Route::post('/evaluations/{id}/restore', [EvaluationController::class, 'restore']);
    Route::delete('/evaluations/{id}/force-delete', [EvaluationController::class, 'forceDelete']);

    // Route::get('/requests/count', [EvaluationController::class, 'countRequests']);



    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('po.index');

    Route::post('/purchase-orders/{id}/evaluate', [PurchaseOrderController::class, 'storePOEvaluation'])
    ->name('po.evaluate.save');


    // Route::get('/po-count', [PurchaseOrderController::class, 'countPurchaseOrders']);


Route::get('/sidebar-counts', function () {

    $user = auth()->user();

    $evaluationsQuery = Evaluation::notDeleted();

    /** @var User $user */
    if (!$user->isAdmin()) {
        $evaluationsQuery->where('office_id', $user->office_id);
    }

    $requestsQuery = Requests::query();

    if (!$user->isAdmin()) {
        $requestsQuery->where('user_id', $user->id);
    }

    $pdfsQuery = Pdf::query();

    if (!$user->isAdmin()) {
        $pdfsQuery->where('user_id', $user->id);
    }

    return response()->json([
        'evaluations' => $evaluationsQuery->count(),
        'requests'    => $requestsQuery->count(),
        'po'          => PurchaseOrder::count(),
        'users'       => User::where('status', 'inactive')->count(),
        'offices'     => Office::count(),
        'pdfs'        => $pdfsQuery->count(),
    ]);
});

Route::post('/authorize/upload', [AuthorizeController::class, 'upload'])
    ->name('authorize.upload');

Route::get('/pdf/fetch', [AuthorizeController::class, 'fetchPdfData'])
    ->name('pdf.fetch');

Route::patch('/pdf/status/{id}', [AuthorizeController::class, 'updateStatus'])->name('pdf.status');


Route::middleware('auth')->get('/secure-pdf/{filename}', [AuthorizeController::class, 'viewPdf'])
    ->name('secure.pdf');


Route::get('/office-head/{id}', function ($id) {
    $office = Office::findOrFail($id);

    return response()->json([
        'name' => $office->head_name,
        'designation' => $office->head_designation,
    ]);
});





});


Route::middleware(['auth', 'role:administrator,pgso'])->group(function () {
    Route::post('/requests/{request}/approve', [EvaluationController::class, 'approve']);
    Route::post('/requests/{request}/reject', [EvaluationController::class, 'reject']);
});


Route::post('/evaluation/update/{token}', [HeadController::class, 'updateEvaluation'])->name('evaluation.head.update');

Route::get('/evaluation/head-review/{token}', [HeadController::class, 'reviewPage']);

Route::get('/evaluation/review/{token}', [HeadController::class, 'reviewEvaluation'])->name('evaluation.review');


Route::post('/evaluation/validate-code/{token}', [HeadController::class, 'validateCode']);
