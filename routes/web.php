<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Route::resource('departments', [App\Http\Controllers\DepartmentController::class, 'index'])->only([
//     'index', 'create', 'edit', 'store', 'update', 'destroy'
// ]);

// Route::any('departments', [App\Http\Controllers\DepartmentController::class, 'index']);

// Route::get('departments/getData', 'DepartmentController@getData')->name('departments.getData');
Route::get('departments', [App\Http\Controllers\DepartmentController::class, 'index'])->name('departments.index');
Route::get('departments/get-data', [App\Http\Controllers\DepartmentController::class, 'getData'])->name('departments.get-data');
Route::get('departments/edit/{id}', [App\Http\Controllers\DepartmentController::class, 'edit'])->name('departments.edit');
Route::post('departments/store', [App\Http\Controllers\DepartmentController::class, 'store'])->name('departments.store');
Route::put('departments/update/{id}', [App\Http\Controllers\DepartmentController::class, 'update'])->name('departments.update');
Route::delete('departments/delete/{id}', [App\Http\Controllers\DepartmentController::class, 'destroy'])->name('departments.delete');


Route::get('job-titles', [App\Http\Controllers\JobTitleController::class, 'index'])->name('job-titles.index');
Route::get('job-titles/get-data', [App\Http\Controllers\JobTitleController::class, 'getData'])->name('job-titles.get-data');
Route::get('job-titles/edit/{id}', [App\Http\Controllers\JobTitleController::class, 'edit'])->name('job-titles.edit');
Route::post('job-titles/store', [App\Http\Controllers\JobTitleController::class, 'store'])->name('job-titles.store');
Route::put('job-titles/update/{id}', [App\Http\Controllers\JobTitleController::class, 'update'])->name('job-titles.update');
Route::delete('job-titles/delete/{id}', [App\Http\Controllers\JobTitleController::class, 'destroy'])->name('job-titles.delete');

Route::get('employees', [App\Http\Controllers\EmployeeController::class, 'index'])->name('employees.index');
Route::get('employees/get-data', [App\Http\Controllers\EmployeeController::class, 'getData'])->name('employees.get-data');
Route::get('employees/edit/{id}', [App\Http\Controllers\EmployeeController::class, 'edit'])->name('employees.edit');
Route::get('employees/view/{id}', [App\Http\Controllers\EmployeeController::class, 'view'])->name('employees.view');
Route::post('employees/store', [App\Http\Controllers\EmployeeController::class, 'store'])->name('employees.store');
Route::put('employees/update/{id}', [App\Http\Controllers\EmployeeController::class, 'update'])->name('employees.update');
Route::delete('employees/delete/{id}', [App\Http\Controllers\EmployeeController::class, 'destroy'])->name('employees.delete');
Route::get('/employees/search', [App\Http\Controllers\EmployeeController::class, 'search']);


Route::get('salary-records', [App\Http\Controllers\SalaryRecordController::class, 'index'])->name('salary-records.index');
Route::get('salary-records/get-data', [App\Http\Controllers\SalaryRecordController::class, 'getData'])->name('salary-records.get-data');
Route::get('salary-records/edit/{id}', [App\Http\Controllers\SalaryRecordController::class, 'edit'])->name('salary-records.edit');
Route::get('salary-records/view/{id}', [App\Http\Controllers\SalaryRecordController::class, 'view'])->name('salary-records.view');
Route::post('salary-records/store', [App\Http\Controllers\SalaryRecordController::class, 'store'])->name('salary-records.store');
Route::put('salary-records/update/{id}', [App\Http\Controllers\SalaryRecordController::class, 'update'])->name('salary-records.update');
Route::delete('salary-records/delete/{id}', [App\Http\Controllers\SalaryRecordController::class, 'destroy'])->name('salary-records.delete');
