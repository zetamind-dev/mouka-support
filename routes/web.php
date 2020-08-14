<?php
use Illuminate\Support\Facades\Input;

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

//Home Route
/*
Route::get('/', function () {
return view('welcome');
});
 */
//Route::view('/emails', 'emails.sample_email');

//Route to display Login page as Homepage
Route::get('/', 'LoginPageController@index');

//Redirect Homepage to login
//Route::redirect('/', '/login', 301);

//Route for authentication Handling
Auth::routes();

// Route handles dynamic dependent category display
Route::get('/ajax-category', function () {
    $dept_id = Input::get('dept_id');

    $categories = ComplainDesk\Category::where('dept_id', '=', $dept_id)->get();

    return Response::json($categories);
});

// Route handles dynamic dependent category display
Route::get('/ajax-moderator', function () {
    $dept_id = Input::get('dept_id');

    $users = ComplainDesk\User::where('department_id', '=', $dept_id)->where('user_type', '>', 0)->get();
    return Response::json($users);
});

// Route handles dynamic dependent category display
Route::get('/ajax-report', function () {
    $dept_id = Input::get('dept_id');

    $categories = ComplainDesk\Category::where('dept_id', '=', $dept_id)->get();

    return Response::json($categories);

});

//Route to User's Dashboard
Route::get('/home', 'HomeController@index');

//Route to display Frequently asked Questions
Route::get('/faq', 'FaqsController@index');

//Route to display Page to Create Ticket
Route::get('/tickets', 'TicketsController@create');
//Route to Handle ticket Storage
Route::post('/tickets', 'TicketsController@store');
//Route to display authenticated User's Tickets
Route::get('/mytickets', 'TicketsController@userTickets');
//Route to display more information on a single ticket
Route::get('/tickets/{ticket_id}', 'TicketsController@show');
//Route to display edit page for a single ticket
Route::get('/tickets/edit/{ticket_id}', 'TicketsController@edit');
//Route to handle update for a single ticket
Route::post('/tickets/{ticket_id}/update', 'TicketsController@update');
Route::post('/tickets/{ticket_id}/drop', 'TicketsController@drop');

//Route to Handle new comments storage
Route::post('/comment', 'CommentsController@store');

//Route to Show View for User to Update Settings
Route::get('/settings', 'UserSettingsController@create');
//Route to Handle User Settings Update
Route::post('/settings', 'UserSettingsController@updateTelephone');

Route::get('/assets', 'AssetsController@create');
Route::post('/assets', 'AssetsController@store');
Route::post('/assets/{id}', 'AssetsController@delete');

//Admin routes( they should all be prefix with /admin in the Url)

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    //Route to display all tickets
    Route::get('tickets', 'TicketsController@index');
    //Routes to close a ticket
    Route::post('close_ticket/{ticket_id}', 'TicketsStatusController@close');
    //Change Ticket Visibility to Public
    Route::post('public_ticket/{ticket_id}', 'TicketsController@ticketVisibilityPublic');
    //Change Ticket Visibility to Private
    Route::post('private_ticket/{ticket_id}', 'TicketsController@ticketVisibilityPrivate');

    //Route to display Page to Create Ticket
    Route::get('/category', 'CategoriesController@create');
    //Route to Handle ticket Storage
    Route::post('/category', 'CategoriesController@store');
    //Route to Delete Category
    Route::post('category/delete/{id}', 'CategoriesController@delete');

    //Route to display Page to Create Ticket
    Route::get('/department', 'DepartmentsController@create');
    //Route to Handle ticket Storage
    Route::post('/department', 'DepartmentsController@store');
    //Route to Delete Category
    Route::post('department/delete/{id}', 'DepartmentsController@delete');

    //Route To Display New Admin Page
    Route::get('/users', 'AdminController@create');
    //Route to store New Admin User
    Route::post('/users', 'AdminController@store');
    //Rotue to delete new Admin User
    Route::post('/users/{id}', 'AdminController@delete');

    //Route To Display New Register Page
    Route::get('/users', 'AdminController@create');
    //Route to store New Admin User
    Route::post('/users', 'AdminController@store');
    //Rotue to delete new Admin User
    Route::post('/users/{id}', 'AdminController@delete');

    //Route to view Audit Logs
    Route::get('logs', 'LogsController@index');
    //Reout to view Report
    Route::get('reports', 'ReportController@getComplainList');
    // Route to export report to excel
    Route::post('reports/export', 'ReportController@export');

    // Route to filter report
    Route::post('reports/filter', 'ReportController@filter');

    //Route to display the page to create escalation level
    Route::get('/escalation', 'EscalationController@index');
    //Route to display the page to create escalation level
    Route::get('/escalation/{id}', 'EscalationController@edit');
    //Route to create new escalation level
    Route::post('/escalation', 'EscalationController@store');
    //Route to display the page to create escalation level
    Route::post('/escalation/{id}/update', 'EscalationController@update');

    //Route to display the page to create escalation level
    Route::post('/escalation/delete/{id}', 'EscalationController@delete');

    //Route to create FAQ
    Route::get('/faq', 'FaqsController@create');

    //Route to store newly created Frequently asked Questions
    Route::post('/faq', 'FaqsController@store');

    //Route to edit FAQ
    Route::get('/faq/edit/{id}', 'FaqsController@edit');

    //Route to update FAQ
    Route::post('/faq/update/{id}', 'FaqsController@update');

    //Route to update FAQ
    Route::post('/faq/delete/{id}', 'FaqsController@destroy');

    //Route to edit user
    Route::get('/edit-user', 'EditUserController@index');
    //Route to edit user
    Route::post('/edit-user', 'EditUserController@update');
    //Runs crons job on the server
    //Route::get('/execute','CronJobController@index');

});
