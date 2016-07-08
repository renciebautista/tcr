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

Route::group(['middleware' => ['web']], function () {

	Route::get('login', 'Auth\AuthController@getLogin');
	Route::get('auth/login', 'Auth\AuthController@getLogin');
	Route::get('auth/logout', 'Auth\AuthController@getLogout');
	// Route::get('auth/register', 'Auth\AuthController@getRegister');
	Route::post('auth/login', 'Auth\AuthController@postLogin');

	Route::group(array('prefix' => 'api'), function()
	{
	   	Route::get('auth', 'Api\AuthController@auth');
	    Route::get('download', 'Api\DownloadController@index');
	    Route::post('storeaudit', 'Api\UploadController@storeaudit');
	    Route::post('uploaddetails', 'Api\UploadController@uploaddetails');
	   	Route::post('uploadimage/{audit_id}', 'Api\UploadController@uploadimage');
	   	Route::post('uploadtrace', 'Api\UploadController@uploadtrace');
	   	Route::post('uploadcheckin', 'Api\UploadController@uploadcheckin');

	   	Route::get('audits', 'Api\AuditController@index');
	   	Route::get('postedaudits', 'Api\AuditController@postedaudits');
	   	Route::get('usersummaryreport/{audit_id}/user/{user_id}', 'Api\ReportController@getUserSummary');
	   	Route::get('storesummaryreport/{audit_id}/user/{user_id}', 'Api\ReportController@getStoreSummary');

	});

});



Route::group(['middleware' => ['web', 'auth']], function () {

	Route::group(['middleware' => ['role:admin']], function(){
		Route::get('audits/{id}/stores',['as' => 'audits.stores', 'uses' => 'AuditStoreController@index']);
		Route::get('audits/{id}/uploadstores',['as' => 'audits.uploadstores', 'uses' => 'AuditStoreController@create']);
		Route::post('audits/{id}/postuploadstores',['as' => 'audits.postuploadstores', 'uses' => 'AuditStoreController@store']);

		Route::get('audits/{id}/templates',['as' => 'audits.templates', 'uses' => 'AuditTemplateController@index']);
		Route::get('audits/{id}/uploadtemplates',['as' => 'audits.uploadtemplates', 'uses' => 'AuditTemplateController@create']);
		Route::post('audits/{id}/postuploadtemplates',['as' => 'audits.postuploadtemplates', 'uses' => 'AuditTemplateController@store']);
		Route::delete('audits/{id}/templates',['as' => 'audits.templatedestroy', 'uses' => 'AuditTemplateController@destroy']);

		Route::get('audits/{id}/dttemplates',['as' => 'audits.dttemplates', 'uses' => 'AuditDtTemplateController@index']);
		Route::get('audits/{id}/uploaddttemplates',['as' => 'audits.uploaddttemplates', 'uses' => 'AuditDtTemplateController@create']);
		Route::post('audits/{id}/postuploaddttemplates',['as' => 'audits.postuploaddttemplates', 'uses' => 'AuditDtTemplateController@store']);
		Route::delete('audits/{id}/dttemplates',['as' => 'audits.dttemplatedestroy', 'uses' => 'AuditDtTemplateController@destroy']);


		Route::get('audits/{audit}/users/{id}',['as' => 'audits.mappedstores', 'uses' => 'AuditUserController@mappedstores']);
		Route::get('audits/{id}/users',['as' => 'audits.users', 'uses' => 'AuditUserController@index']);
		Route::get('audits/{id}/uploadpjptarget',['as' => 'audits.uploadpjptarget', 'uses' => 'AuditUserController@create']);
		Route::post('audits/{id}/postuploadpjptarget',['as' => 'audits.postuploadpjptarget', 'uses' => 'AuditUserController@store']);


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

	    Route::resource('users', 'UserController' );
	    Route::get('users/{id}/updatestatus',['as'=>'users.updatestatus','uses'=>'UserController@updatestatus']);
	    Route::get('users/{id}/managefields',['as'=>'users.managefields','uses'=>'UserController@managefields']);
	    Route::get('users/{id}/managefields/create',['as'=>'users.managefields_create','uses'=>'UserController@managefields_create']);
	    Route::get('users/{id}/managefields/template/create',['as'=>'users.managefields_template_create','uses'=>'UserController@managefields_template_create']);
	    
	    Route::post('users/managefields/store',['as'=>'users.managefields_store','uses'=>'UserController@managefields_store']);
	    Route::post('users/managefields/template/store',['as'=>'users.managefields_template_store','uses'=>'UserController@managefields_template_store']);
	    
	    Route::post('users/managefieldsupdate',['as'=>'users.managefieldsupdate','uses'=>'UserController@managefieldsupdate']);
	    Route::post('users/managefieldstemplateupdate',['as'=>'users.managefieldstemplateupdate','uses'=>'UserController@managefieldstemplateupdate']);
	    
	    Route::resource('templatemaintenance', 'TemplateController' );
	    
	    Route::get('templatemaintenance/{id}/updatestatus',['as'=>'templatemaintenance.updatestatus','uses'=>'TemplateController@updatestatus']);

	    Route::get('deviceerror', ['as' => 'deviceerror.index', 'uses' => 'DeviceErrorController@index']);
	    Route::get('deviceerror/getfile/{filename}', ['as' => 'deviceerror.getfile', 'uses' => 'DeviceErrorController@getfile']);


	    Route::resource('deviceerror', 'DeviceErrorController' );

	    Route::get('sostypes',['as' => 'sostypes.index', 'uses' => 'SosTypesController@index']);
    	Route::get('enrollmenttypes',['as' => 'enrollmenttypes.index', 'uses' => 'EnrollmentTypesController@index']);
	});
	Route::get('/',['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);
	Route::get('/dashboard',['as' => 'dashboard.index', 'uses' => 'DashboardController@index']);

	

    Route::get('auditreport',['as' => 'auditreport.index', 'uses' => 'AuditReportController@index']);
    Route::post('auditreport',['as' => 'auditreport.create', 'uses' => 'AuditReportController@create']);
    Route::get('auditreport/{id}/download',['as' => 'auditreport.download', 'uses' => 'AuditReportController@download']);
    Route::get('usersummaryreport',['as' => 'usersummaryreport.index', 'uses' => 'UserSummaryReportController@index']);
    Route::post('usersummaryreport',['as' => 'usersummaryreport.create', 'uses' => 'UserSummaryReportController@create']);
    Route::get('usersummaryreport/{audit_id}/user/{user_id}',['as' => 'usersummaryreport.show', 'uses' => 'UserSummaryReportController@show']);
    Route::get('storesummaryreport/{id}',['as' => 'storesummaryreport.show', 'uses' => 'StoreSummaryReportController@show']);

    Route::get('osareport',['as' => 'osareport.index', 'uses' => 'OsaReportController@index']);
    Route::post('osareport',['as' => 'osareport.create', 'uses' => 'OsaReportController@create']);

    Route::get('sosreport',['as' => 'sosreport.index', 'uses' => 'SosReportController@index']);
    Route::post('sosreport',['as' => 'sosreport.create', 'uses' => 'SosReportController@create']);

    Route::get('npireport',['as' => 'npireport.index', 'uses' => 'NpiReportController@index']);
    Route::post('npireport',['as' => 'npireport.create', 'uses' => 'NpiReportController@create']);

    Route::get('customizedplanoreport',['as' => 'customizedplanoreport.index', 'uses' => 'CustomizedPlanogramReportController@index']);
    Route::post('customizedplanoreport',['as' => 'customizedplanoreport.create', 'uses' => 'CustomizedPlanogramReportController@create']);

    Route::get('pjpreport',['as' => 'pjpreport.index', 'uses' => 'PjpReportController@index']);
    Route::post('pjpreport',['as' => 'pjpreport.create', 'uses' => 'PjpReportController@create']);

    Route::get('customerreport',['as' => 'customerreport.index', 'uses' => 'CustomerReportController@index']);
    Route::post('customerreport',['as' => 'customerreport.create', 'uses' => 'CustomerReportController@create']);
    Route::get('customerreport/{id}/download',['as' => 'customerreport.download', 'uses' => 'CustomerReportController@download']);
    Route::get('customerreport/{customer_code}/region/{region_code}/template/{channel_code}/audit/{audit_id}',['as' => 'customerreport.show', 'uses' => 'CustomerReportController@show']);

    	//owaa
    Route::get('customerregionalreport',['as' => 'customerregionalreport.index', 'uses' => 'CustomerRegionalReportController@index']);
    Route::post('customerregionalreport',['as' => 'customerregionalreport.create', 'uses' => 'CustomerRegionalReportController@create']);
    Route::get('customerregionalreport/{id}/download',['as' => 'customerregionalreport.download', 'uses' => 'CustomerRegionalReportController@download']);
    Route::get('customerregionalreport/{customer_code}/region/{region_code}/template/{channel_code}/audit/{audit_id}',['as' => 'customerregionalreport.show', 'uses' => 'CustomerRegionalReportController@show']);



    Route::get('auditimage/{folder}/{filename}', 'Api\DownloadController@auditimage');


    Route::get('templates/{id}/categories', ['as' => 'templates.categories', 'uses' => 'AuditTemplateController@categories']);

    



});





