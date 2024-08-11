<?php
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/', 'LoginController@index')->name('/');
    Route::get('/index', 'LoginController@index')->name('index');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
    /*Route::post('ajxlogin', 'LoginController@ajxlogin')->name('ajxlogin');
    Route::post('verifyEmail', 'LoginController@verifyEmail')->name('verifyEmail');
    Route::match(['get', 'post'], 'reset', 'LoginController@reset')->name('reset');
    Route::post('uppassword', 'LoginController@uppassword')->name('uppassword');
    Route::get('singup', 'SingUpController@index')->name('singup');
    Route::post('ajxregister', 'SingUpController@store')->name('ajxregister');*/
});

Route::namespace('App\Http\Controllers')->middleware(['auth'])->group(function () {
    
    //modules
    Route::get('listModules', 'ModuleController@listModules')->name('listModules');
    Route::post('loadModules', 'ModuleController@loadModules')->name('loadModules');
    Route::get('editModule/{reg}', 'ModuleController@editModule')->name('editModule');
    Route::get('addModule', 'ModuleController@addModule')->name('addModule');
    Route::post('delModule', 'ModuleController@delModule')->name('delModule');
    Route::post('storeModule', 'ModuleController@storeModule')->name('storeModule');
    Route::post('loadSubModules', 'ModuleController@loadSubModules')->name('loadSubModules');
    Route::post('loadInfoModule', 'ModuleController@loadInfoModule')->name('loadInfoModule');
    Route::post('upInfoModule', 'ModuleController@upInfoModule')->name('upInfoModule');
    Route::post('loadInfoSubModule', 'ModuleController@loadInfoSubModule')->name('loadInfoSubModule');

    //users
    Route::get('listUsers', 'UserController@listUsers')->name('listUsers');
    Route::post('loadUsers', 'UserController@loadUsers')->name('loadUsers');
    Route::get('editUser/{reg}', 'UserController@editUser')->name('editUser');
    Route::get('addUser', 'UserController@addUser')->name('addUser');
    Route::post('delUser', 'UserController@delUser')->name('delUser');
    Route::match(['get', 'post'], 'loadInfoUser', 'UserController@loadInfoUser')->name('loadInfoUser');
    Route::match(['get', 'post'], 'upInfoReg', 'UserController@upInfoReg')->name('upInfoReg');
    Route::match(['get', 'post'], 'upPasswordUser', 'UserController@upPasswordUser')->name('upPasswordUser');
    Route::match(['get', 'post'], 'loadPermitsUser', 'UserController@loadPermitsUser')->name('loadPermitsUser');
    Route::post('storeUser', 'UserController@storeUser')->name('storeUser');

    //clientes
    Route::get('listClientes', 'ClienteController@listCliente')->name('listClientes');
    Route::post('loadClientes', 'ClienteController@loadClientes')->name('loadClientes');
    Route::get('addCliente', 'ClienteController@addCliente')->name('addCliente');
    Route::get('editCliente/{reg}', 'ClienteController@editCliente')->name('editCliente');
    Route::post('storeCliente', 'ClienteController@storeCliente')->name('storeCliente');
    Route::match(['get', 'post'], 'loadInfoCliente', 'ClienteController@loadInfoCliente')->name('loadInfoCliente');
    Route::match(['get', 'post'], 'upInfoRegCli', 'ClienteController@upInfoRegCli')->name('upInfoRegCli');
    Route::post('delCliente', 'ClienteController@delCliente')->name('delCliente');

    //Servicios
    Route::get('listServicios', 'ServicioController@listServicios')->name('listServicios');
    Route::post('loadServicios', 'ServicioController@loadServicios')->name('loadServicios');
    Route::get('addServicio', 'ServicioController@addServicio')->name('addServicio');
    Route::get('editServicio/{reg}', 'ServicioController@editServicio')->name('editServicio');
    Route::post('storeServicio', 'ServicioController@storeServicio')->name('storeServicio');
    Route::match(['get', 'post'], 'loadInfoServicio', 'ServicioController@loadInfoServicio')->name('loadInfoServicio');
    Route::match(['get', 'post'], 'upInfoRegServicio', 'ServicioController@upInfoRegServicio')->name('upInfoRegServicio');
    Route::post('delServicio', 'ServicioController@delServicio')->name('delServicio');
    
    //Juntas
    Route::post('loadJuntas', 'ServicioController@loadJuntas')->name('loadJuntas');
    Route::get('addJunta/{reg}', 'ServicioController@addJunta')->name('addJunta');
    Route::post('storeJunta', 'ServicioController@storeJunta')->name('storeJunta');
    Route::get('editJunta/{reg}', 'ServicioController@editJunta')->name('editJunta');
    Route::match(['get', 'post'], 'loadInfoJunta', 'ServicioController@loadInfoJunta')->name('loadInfoJunta');
    Route::match(['get', 'post'], 'upInfoRegJunta', 'ServicioController@upInfoRegJunta')->name('upInfoRegJunta');
    Route::post('upFileJunta', 'ServicioController@upFileJunta')->name('upFileJunta');
    Route::post('loadFileJunta', 'ServicioController@loadFileJunta')->name('loadFileJunta');

    //account
    Route::get('profile', 'AccountController@profile')->name('profile');
    Route::get('logs', 'AccountController@logs')->name('logs');
    Route::match(['get', 'post'], 'upPassword', 'AccountController@upPassword')->name('upPassword');
    Route::match(['get', 'post'], 'myPermits', 'AccountController@myPermits')->name('myPermits');
    Route::match(['get', 'post'], 'loadPermits', 'AccountController@loadPermits')->name('loadPermits');
    Route::match(['get', 'post'], 'asignPermit', 'AccountController@asignPermit')->name('asignPermit');


    Route::post('loadImageUser', 'AccountController@loadImageUser')->name('loadImageUser');
    Route::post('upImgUser', 'AccountController@upImgUser')->name('upImgUser');
    Route::post('upProfile', 'AccountController@upProfile')->name('upProfile');

    //users
    Route::get('listPosts', 'PostController@listPosts')->name('listPosts');
    Route::post('loadPosts', 'PostController@loadPosts')->name('loadPosts');
    Route::post('getLastPosts', 'PostController@getLastPosts')->name('getLastPosts');
    Route::get('editPost/{reg}', 'PostController@editPost')->name('editPost');
    Route::get('addPost', 'PostController@addPost')->name('addPost');
    Route::post('delPost', 'PostController@delPost')->name('delPost');
    Route::match(['get', 'post'], 'upload', 'Post@uploadImageCkeditor')->name('uploadImageCkeditor');
    Route::match(['get', 'post'], 'imageckeditor/{segment}', 'Post@imageckeditor')->name('imageckeditor');
    Route::post('storePost', 'PostController@storePost')->name('storePost');
    Route::post('loadImagePost', 'PostController@loadImagePost')->name('loadImagePost');
    Route::post('upImgPost', 'PostController@upImgPost')->name('upImgPost');
    Route::post('upPost', 'PostController@upPost')->name('upPost');
});