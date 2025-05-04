<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisteredUserController as RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InspectionTravelerController;
use App\Http\Controllers\JobJournalController;
use App\Http\Controllers\MaterialInventoryController;
use App\Http\Controllers\MemberJobController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimeTrackerController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\VendorController;
use App\Http\Middleware\OnlyAdminAllowed;
use App\Http\Resources\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/csrf-token', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::get('/jobs', function () {
    $purchase_order = PurchaseOrder::with('parts')->paginate(20);
    return PurchaseOrderResource::collection($purchase_order);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::delete('/user-left-form', [UserActivityController::class, 'handleUserLeftForm'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->prefix('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');
});

Route::middleware(['guest'])->prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'store'])->name('register');
    Route::post('/admin-register', [RegisterController::class, 'storeAdmin'])->name('admin.register');
    Route::post('/login', [LoginController::class, 'store'])->name('login');
    Route::post('/admin-login', [LoginController::class, 'storeAdmin'])->name('admin.login');
});

Route::middleware(['guest'])->group(function () {
    Route::post('/forgot-password', [ResetPasswordController::class, 'sendOTP'])->name('password.email')
        // ->middleware('throttle:2,1')
    ;
    Route::post('/authenticate-otp', [ResetPasswordController::class, 'authenticateOTP'])->name('password.authenticate-otp');
    Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->name('password.update');
});

Route::post('/update-password', SettingController::class)->name('settings.update-password')->middleware('auth:sanctum');
Route::post('/material-inventories-alert-admin', [MaterialInventoryController::class, 'alertAdmin'])->middleware([
    'throttle:1,1',
    'auth:sanctum'
]);

Route::middleware([OnlyAdminAllowed::class, 'auth:sanctum'])->group(function () {
    Route::post('/purchase-orders/assign-members/{id}', [PurchaseOrderController::class, 'assignMembers'])->name('purchase-order.assign-member');
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-order.store');
    Route::patch('/purchase-orders/unassign-members/{id}', [PurchaseOrderController::class, 'unassignMembers']);
    Route::patch('/purchase-orders/{id}', [PurchaseOrderController::class, 'update'])->name('purchase-order.update');
    Route::delete('/purchase-orders/{id}', [PurchaseOrderController::class, 'destroy'])->name('purchase-order.destroy');

    Route::post('/routers', [RouterController::class, 'store'])->name('router.store');
    Route::patch('/routers/{id}', [RouterController::class, 'update'])->name('router.update');
    Route::delete('/routers/{id}', [RouterController::class, 'destroy'])->name('router.destroy');

    Route::post('/clients', [ClientController::class, 'store']);
    Route::patch('/clients/{id}', [ClientController::class, 'update']);
    Route::delete('/clients/{id}', [ClientController::class, 'destroy']);

    Route::get('/top-clients', [VendorController::class, 'topClients']);
    Route::post('/vendors', [VendorController::class, 'store']);
    Route::patch('/vendors/{id}', [VendorController::class, 'update']);
    Route::delete('/vendors/{id}', [VendorController::class, 'destroy']);


    Route::post('/material-inventories', [MaterialInventoryController::class, 'store']);
    Route::patch('/material-inventories/{id}', [MaterialInventoryController::class, 'update']);
    Route::delete('/material-inventories/{id}', [MaterialInventoryController::class, 'destroy']);

    Route::get('/inspection-travelers-statuses', [InspectionTravelerController::class, 'allStatuses']);
    Route::post('/inspection-travelers', [InspectionTravelerController::class, 'store']);
    Route::patch('/inspection-travelers/{id}', [InspectionTravelerController::class, 'update']);
    Route::delete('/inspection-travelers/{id}', [InspectionTravelerController::class, 'destroy']);

    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');

    Route::get('/job-journals', [JobJournalController::class, 'index']);
    Route::post('/job-journals-search', [PurchaseOrderController::class, 'search']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::patch('/update-job-status/{id}', [MemberJobController::class, 'updateJobProgressStatus']);

    Route::get('/time-tracker', TimeTrackerController::class);

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/job-tasks/{job_id}', [TaskController::class, 'jobTasks']);
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('task.show');
    Route::post('/tasks', [TaskController::class, 'store'])->name('task.store');
    Route::patch('/tasks/assign-user/{id}', [TaskController::class, 'assignUser'])->name('task.assign-user');
    Route::patch('/tasks/unassign-user/{id}', [TaskController::class, 'unassignUser'])->name('task.unassign-user');
    Route::patch('/tasks/{id}', [TaskController::class, 'update'])->name('task.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('task.destroy');

    Route::get('/purchase-orders/{id}', [PurchaseOrderController::class, 'show'])->name('purchase-order.show');
    Route::post('/purchase-orders/search', [PurchaseOrderController::class, 'search'])->name('purchase-order.search');

    Route::get('/routers', [RouterController::class, 'index'])->name('router.index');
    Route::get('/routers-tabs-stats', [RouterController::class, 'tabsStats'])->name('router.tab-stats');
    Route::get('/in-progress-routers', [RouterController::class, 'inProgressRouters'])->name('router.in-progress');
    Route::get('/completed-routers', [RouterController::class, 'completedRouters'])->name('router.completed');
    Route::get('/routers/{id}', [RouterController::class, 'show'])->name('router.show');
    Route::post('/routers/search', [RouterController::class, 'search'])->name('router.search');

    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/{id}', [ClientController::class, 'show']);
    Route::post('/clients/search', [ClientController::class, 'search']);

    Route::get('/vendors', [VendorController::class, 'index']);
    Route::get('/vendors/{id}', [VendorController::class, 'show']);
    Route::post('/vendors/search', [VendorController::class, 'search']);

    Route::get('/material-inventory-stats', [MaterialInventoryController::class, 'materialInventoriesStats']);
    Route::get('/ordered-material-inventories', [MaterialInventoryController::class, 'orderedMaterialInventories']);
    Route::get('/unavailable-material-inventories', [MaterialInventoryController::class, 'unavailableMaterialInventories']);
    Route::get('/available-material-inventories', [MaterialInventoryController::class, 'availableMaterialInventories']);
    Route::get('/material-inventories', [MaterialInventoryController::class, 'index']);
    Route::get('/material-inventories/{id}', [MaterialInventoryController::class, 'show']);
    Route::post('/material-inventories/search', [MaterialInventoryController::class, 'search']);

    Route::get('/inspection-travelers', [InspectionTravelerController::class, 'index']);
    Route::get('/inspection-travelers-stats', [InspectionTravelerController::class, 'inspectionTravelerStats']);
    Route::get('/completed-inspection-travelers', [InspectionTravelerController::class, 'completed']);
    Route::get('/in-progress-inspection-travelers', [InspectionTravelerController::class, 'inProgress']);
    Route::get('/pending-inspection-travelers', [InspectionTravelerController::class, 'pending']);
    Route::get('/overdue-inspection-travelers', [InspectionTravelerController::class, 'overdue']);
    Route::get('/inspection-travelers/{id}', [InspectionTravelerController::class, 'show']);
    Route::post('/inspection-travelers/search', [InspectionTravelerController::class, 'search']);
    Route::patch('/inspection-travelers/update-status/{id}', [InspectionTravelerController::class, 'updateStatus']);

    Route::get('/get-unread-notifications', [NotificationController::class, 'getUnreadNotifications']);
    Route::get('/get-read-notifications', [NotificationController::class, 'getReadNotifications']);
    Route::get('/get-all-notifications', [NotificationController::class, 'getAllNotifications']);
    Route::post('/mark-single-as-read/{id}', [NotificationController::class, 'markAsRead']);
    Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);

    Route::get('/assigned-jobs', [MemberJobController::class, 'assignedJobs']);
    Route::get('/member-jobs', [MemberJobController::class, 'memberJobs']);
    Route::post('/member-jobs/search', [MemberJobController::class, 'searchMemberJobs']);

    Route::post('/comments', [CommentController::class, 'store'])->name('comment.create');
    Route::patch('/comments/{id}', [CommentController::class, 'update'])->name('comment.update');


    Route::post('/files/download', [FileController::class, 'download'])->name('file.download');
    Route::post('/files/upload', [FileController::class, 'upload'])->name('file.upload');
    Route::delete('/files/delete/{upload_id?}', [FileController::class, 'deleteFromTempoarayStorage'])->name('file.delete');

    Route::get('/all-floor-team-members', [OverviewController::class, 'getFloorTeamMembers']);
    Route::get('/dashboard-stats', [OverviewController::class, 'dashboardJobStatsCount']);

    //overview page
    Route::get('/all-purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-order.index');
    Route::get('/all-completed-jobs', [OverviewController::class, 'completedJobs']);
    Route::get('/recent-purchase-orders', [PurchaseOrderController::class, 'recentPurchaseOrders'])->name('purchase-order.recent');

    Route::get('/departments', [OverviewController::class, 'getDepartments']);

    Route::get('/jobs-in-progress-count', [OverviewController::class, 'JobsInProgressCount'])->name('purchase-order.in-progress');
    Route::get('/jobs-completed-count', [OverviewController::class, 'JobsCompletedCount'])->name('purchase-order.completed');
    Route::get('/all-jobs-count', [OverviewController::class, 'AllJobsCount'])->name('purchase-order.all-jobs');
    Route::post('/po-vs-completed-job-stats', [OverviewController::class, 'PurchaseOrderVsJobsCompleted'])->name('po-vs-completed-job-stats');

    Route::post('/purchase-orders-import-csv', [PurchaseOrderController::class, 'importCSV'])->name('purchase-order.import');




    // THEY WERE MOVED TO WEB
    // Route::get('/purchase-orders-export-csv', [PurchaseOrderController::class, 'exportAsCSV'])->name('purchase-order.export-csv');
    // Route::get('/purchase-orders-export-excel', [PurchaseOrderController::class, 'exportAsExcel'])->name('purchase-order.export-excel');
    // Route::get('/purchase-orders-export-pdf', [PurchaseOrderController::class, 'exportAsPDF'])->name('purchase-order.export-pdf');

    // Route::get('/clients-export-csv', [ClientController::class, 'exportAsCSV']);
    // Route::post('/clients-export-multiple-csv', [ClientController::class, 'exportMultipleAsCSV']);
    // Route::get('/clients-export-pdf', [ClientController::class, 'exportAsPDF']);

    // Route::post('/routers-download', [RouterController::class, 'download'])->name('router.multi-download');

    // Route::get('/vendors-export-csv', [VendorController::class, 'exportCSV'])->name('vendor.export-csv');
    // Route::post('/vendors-export-multiple-csv', [VendorController::class, 'exportMultipleAsCSV'])->name('vendor.export-csv');
    // Route::get('/vendors-export-pdf', [VendorController::class, 'exportPDF'])->name('vendor.export-pdf');

    // Route::get('/inspection-travelers-export-csv', [InspectionTravelerController::class, 'exportCSV']);

    // Route::post('/purchase-orders-multiple-export-csv', [PurchaseOrderController::class, 'exportMultipleAsCSV'])->name('purchase-order.export-multiple-csv');
});
