<?php

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

Route::get('/', function() {
	return redirect('login');
});

Auth::routes();

Route::get('logout', 'Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function() {
	
	// Attendance Routes...
	Route::any('attendances/create', 'AttendanceController@create');
	Route::any('attendances/list', 'AttendanceController@getList');
	Route::post('attendances/update', 'AttendanceController@update');
	Route::post('attendances/delete', 'AttendanceController@delete');
	Route::resource('attendances', 'AttendanceController');

	// Report Routes...
	Route::any('reports/daily-attendance/{view?}', 'ReportController@getDailyAttendance');
	Route::any('reports/sectionwise-attendance/{view?}', 'ReportController@getSectionWiseAttendance');
});

// Student Routes...
Route::any('students/list', 'StudentController@getList');
Route::post('students/delete', 'StudentController@delete');
Route::resource('students', 'StudentController');

Route::get('test', function() {

	$student = \App\Student::findOrFail(1);
	dd($student->getSectionTotalStudent());
	$day_no = 30;
	$toDate = \Carbon::now();
	while($day_no) {
		echo \Carbon::now()->subDays($day_no) . '<br/>';
		$day_no--;
	}
	$gender = array_rand(config('constants.gender'));

	dd($gender);
});
