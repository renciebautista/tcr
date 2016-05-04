<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::get('test', function(){
	$attr = new App\MyClasses\SurveyQuestion();
	dd($attr);
});

Route::group(['middleware' => ['web']], function () {
	Route::get('/',['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);

	Route::get('audits/{id}/stores',['as' => 'audits.stores', 'uses' => 'AuditStoreController@index']);
	Route::get('audits/{id}/uploadstores',['as' => 'audits.uploadstores', 'uses' => 'AuditStoreController@create']);
	Route::post('audits/{id}/postuploadstores',['as' => 'audits.postuploadstores', 'uses' => 'AuditStoreController@store']);

	Route::get('audits/{id}/templates',['as' => 'audits.templates', 'uses' => 'AuditTemplateController@index']);
	Route::get('audits/{id}/uploadtemplates',['as' => 'audits.uploadtemplates', 'uses' => 'AuditTemplateController@create']);
	Route::post('audits/{id}/postuploadtemplates',['as' => 'audits.postuploadtemplates', 'uses' => 'AuditTemplateController@store']);

	Route::get('audits/{audit}/users/{id}',['as' => 'audits.mappedstores', 'uses' => 'AuditUserController@mappedstores']);
	Route::get('audits/{id}/users',['as' => 'audits.users', 'uses' => 'AuditUserController@index']);


	Route::get('audits/{id}/categories',['as' => 'audits.categories', 'uses' => 'AuditCategoryController@index']);
	Route::post('audits/{id}/categories',['as' => 'audits.categories_update', 'uses' => 'AuditCategoryController@store']);

	Route::get('audits/{id}/groups',['as' => 'audits.groups', 'uses' => 'AuditGroupController@index']);
	Route::post('audits/{id}/groups',['as' => 'audits.groups_update', 'uses' => 'AuditGroupController@store']);

	Route::get('audits/{audit}/secondarydisplay',['as' => 'audits.secondarydisplay', 'uses' => 'AuditSecondaryDisplayController@index']);
	Route::get('audits/{audit}/secondarydisplay/{id}',['as' => 'audits.secondarydisplay_details', 'uses' => 'AuditSecondaryDisplayController@edit']);
	Route::put('audits/{audit}/secondarydisplay/{id}',['as' => 'audits.secondarydisplayupdate', 'uses' => 'AuditSecondaryDisplayController@update']);
	Route::get('audits/{audit}/uploadsecondarydisplay',['as' => 'audits.uploadsecondarydisplay', 'uses' => 'AuditSecondaryDisplayController@create']);
	Route::post('audits/{audit}/postuploadsecondarydisplay',['as' => 'audits.postuploadsecondarydisplay', 'uses' => 'AuditSecondaryDisplayController@store']);

	Route::get('audits/{audit}/osatargets',['as' => 'audits.osatargets', 'uses' => 'AuditOsaTargetController@index']);
	Route::get('audits/{audit}/osatargets/{id}',['as' => 'audits.osatargets_details', 'uses' => 'AuditOsaTargetController@edit']);
	Route::put('audits/{audit}/osatargets/{id}',['as' => 'audits.osatargetsupdate', 'uses' => 'AuditOsaTargetController@update']);
	Route::delete('osatargets/{id}',['as' => 'audits.osadestroy', 'uses' => 'AuditOsaTargetController@destroy']);
	Route::get('audits/{audit}/uploadosatargets',['as' => 'audits.uploadosatargets', 'uses' => 'AuditOsaTargetController@create']);
	Route::post('audits/{audit}/postuploadosatargets',['as' => 'audits.postuploadosatargets', 'uses' => 'AuditOsaTargetController@store']);

	Route::get('audits/{audit}/sostargets',['as' => 'audits.sostargets', 'uses' => 'AuditSosTargetController@index']);
	Route::get('audits/{audit}/sostargets/{id}',['as' => 'audits.sostargets_details', 'uses' => 'AuditSosTargetController@edit']);
	Route::put('audits/{audit}/sostargets/{id}',['as' => 'audits.sostargetsupdate', 'uses' => 'AuditSosTargetController@update']);
	Route::delete('sostargets/{id}',['as' => 'audits.sosdestroy', 'uses' => 'AuditSosTargetController@destroy']);
	Route::get('audits/{audit}/uploadsostargets',['as' => 'audits.uploadsostargets', 'uses' => 'AuditSosTargetController@create']);
	Route::post('audits/{audit}/postuploadsostargets',['as' => 'audits.postuploadsostargets', 'uses' => 'AuditSosTargetController@store']);

	Route::get('audits/{id}/enrollments',['as' => 'audits.enrollments', 'uses' => 'AuditEnrollmentController@index']);
	
    Route::resource('audits', 'AuditController');

    Route::get('auditreport',['as' => 'auditreport.index', 'uses' => 'AuditReportController@index']);
    Route::post('auditreport',['as' => 'auditreport.create', 'uses' => 'AuditReportController@create']);
    Route::get('auditreport/{id}/download',['as' => 'auditreport.download', 'uses' => 'AuditReportController@download']);
    Route::get('usersummaryreport',['as' => 'usersummaryreport.index', 'uses' => 'UserSummaryReportController@index']);
    Route::post('usersummaryreport',['as' => 'usersummaryreport.create', 'uses' => 'UserSummaryReportController@create']);
    Route::get('usersummaryreport/{audit_id}/store/{store_id}',['as' => 'usersummaryreport.show', 'uses' => 'UserSummaryReportController@show']);
    Route::get('storesummaryreport/{id}',['as' => 'storesummaryreport.show', 'uses' => 'StoreSummaryReportController@show']);

    Route::get('sostypes',['as' => 'sostypes.index', 'uses' => 'SosTypesController@index']);
    Route::get('enrollmenttypes',['as' => 'enrollmenttypes.index', 'uses' => 'EnrollmentTypesController@index']);

    Route::get('auditimage/{folder}/{filename}', 'Api\DownloadController@auditimage');


    Route::get('templates/{id}/categories', ['as' => 'templates.categories', 'uses' => 'AuditTemplateController@categories']);
    Route::group(array('prefix' => 'api'), function()
	{
	   	Route::get('auth', 'Api\AuthController@auth');
	    Route::get('download', 'Api\DownloadController@index');
	    Route::post('storeaudit', 'Api\UploadController@storeaudit');
	    Route::post('uploaddetails', 'Api\UploadController@uploaddetails');
	   	Route::post('uploadimage/{audit_id}', 'Api\UploadController@uploadimage');

	});
});



