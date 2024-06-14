<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use League\CommonMark\Node\Block\Document;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobHiringController;
use App\Http\Controllers\UserTableController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ApplicationUserController;
use App\Http\Controllers\DashboardController;

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::post("/login", [HomeController::class, "loginPost"])->name("login");
Route::post("/register", [HomeController::class, "registerPost"])->name("register");

Route::middleware('auth')->group(function () {
    // Routes accessible only by admin and hr
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin');
    Route::resource("users", UserTableController::class);
    Route::resource("hirings", JobHiringController::class);
    Route::resource('profile', ProfileController::class);
    Route::get('getDates', [DashboardController::class, 'CalendarDates'])->name('getDates');
    Route::post('/markAsRead', [NotificationController::class, 'markAsRead'])->name('markAsRead');

    // Routes accessible for Selection Board
    Route::get('BEIUpdate', [ApplicationController::class, 'IndividualBEI'])->name('IndividualBEI');
    Route::post('UploadBEI', [ApplicationController::class, 'UploadBEI'])->name('UploadBEI');

    // Routes accessible by all authenticated users
    Route::resource("applicants", ApplicationController::class);
    Route::get('BeiPDF', [ApplicationController::class, 'generateBEI'])->name('generateBEI');
    Route::post('upload', [ApplicationController::class, 'updateApplicant'])->name('application.updateApplicant');
    Route::get('initial-interview', [ApplicationController::class, 'initialInterview'])->name('initialInterview');
    Route::post('shortlist', [ApplicationController::class, 'shortlistHR'])->name('applicants.shortlist');
    

    // Routes accessible only by users
    Route::get('apply-process', [ApplicationUserController::class, 'applyProcess'])->name('application.applyUpload');
    Route::get('apply', [ApplicationUserController::class, 'apply'])->name('application.apply');
    Route::post('cancel-application', [ApplicationUserController::class, 'cancelApplication'])->name('application.cancel');
    Route::get('view-applications', [ApplicationController::class, 'viewApplications'])->name('applications.view');
    Route::post('shortlistApplicants', [ApplicationController::class, 'guestSelectQualified'])->name("selectApplicant");
    Route::get("/upload-requirement", [ApplicationUserController::class, "uploadRequirement"])->name("upload-requirement");
    Route::post("/store-requirement", [ApplicationUserController::class, "storeRequirement"])->name("store-requirement");

    // Routes accessible by all authenticated users
    Route::get("notifications/get", [NotificationController::class, "getNotificationsData"])->name("notifications.get");
    Route::get("notifications/getUser", [NotificationController::class, "ApplicantsNotif"])->name("NotifUserGet");
});

Route::get("/", [HomeController::class,"index"])->name("home");

