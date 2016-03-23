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

	Route::get('audits/{id}/users',['as' => 'audits.users', 'uses' => 'AuditUserController@index']);

	Route::get('audits/{id}/categories',['as' => 'audits.categories', 'uses' => 'AuditCategoryController@index']);

	Route::get('audits/{id}/groups',['as' => 'audits.groups', 'uses' => 'AuditGroupController@index']);

	Route::get('audits/{id}/secondarydisplay',['as' => 'audits.secondarydisplay', 'uses' => 'AuditSecondaryDisplayController@index']);
	Route::get('audits/{id}/uploadsecondarydisplay',['as' => 'audits.uploadsecondarydisplay', 'uses' => 'AuditSecondaryDisplayController@create']);
	Route::post('audits/{id}/postuploadsecondarydisplay',['as' => 'audits.postuploadsecondarydisplay', 'uses' => 'AuditSecondaryDisplayController@store']);

	Route::get('audits/{id}/osatargets',['as' => 'audits.osatargets', 'uses' => 'AuditOsaTargetController@index']);
	Route::get('audits/{id}/uploadosatargets',['as' => 'audits.uploadosatargets', 'uses' => 'AuditOsaTargetController@create']);
	Route::post('audits/{id}/postuploadosatargets',['as' => 'audits.postuploadosatargets', 'uses' => 'AuditOsaTargetController@store']);
	
    Route::resource('audits', 'AuditController');
});
