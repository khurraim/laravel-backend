<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController; // Import the UserController
use App\Http\Controllers\ModelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingController;

use App\Http\Controllers\PagesController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\FooterMenuController;

use App\Http\Controllers\FooterController;
use App\Http\Controllers\FormGroupController;
use App\Http\Controllers\SocialIconsController;
use App\Http\Controllers\ContactController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);

// Create a new model
Route::post('models', [ModelController::class, 'store']);

Route::post('modelsByUser', [ModelController::class, 'StoreByUser']);

// Read all models
Route::get('models', [ModelController::class, 'index']);

// all models
Route::get('AllModels', [ModelController::class, 'AllModels']);

Route::get('ModelsByUsers',[ModelController::class, 'IndexByUser']);
Route::get('ModelsByAdmin',[ModelController::class, 'IndexByAdmin']);

// Unique Ages
Route::get('models/unique-ages', [ModelController::class, 'uniqueAges']);

// Unique Nationality
Route::get('models/unique-nationalities', [ModelController::class, 'uniqueNationalities']);

// Unique Prices
Route::get('models/unique-prices', [ModelController::class, 'uniquePrices']);

// Unique Locations
Route::get('models/unique-locations', [ModelController::class, 'uniqueLocations']);

// Unique Dress Size
Route::get('models/unique-dress-sizes', [ModelController::class, 'uniqueDressSizes']);

// Unique Rates 
Route::get('/models/rates/{id}',[ModelController::class, 'uniqueRates']);

// Read a specific model by ID
Route::get('models/{id}', [ModelController::class, 'show']);

Route::get('models/edit/{id}', [ModelController::class, 'edit']);

// Update a model by ID
Route::post('models/{id}', [ModelController::class, 'update']);

// Delete a model by ID
Route::delete('models/{id}', [ModelController::class, 'destroy']);

// Get Services By Model ID 
Route::get('/models/services/{id}', [ModelController::class, 'ShowServices']);

// Get Stats By Model ID
Route::get('/models/stats/{id}', [ModelController::class, 'ShowStats']);

// Get Gallery By Model ID 
Route::get('/models/gallery/{id}', [ModelController::class, 'ShowGallery']);


Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class,'register']);

Route::group(['middleware'=>'api'],function(){
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
});





//Route::resource('setting', SettingController::class);
Route::get('/setting', [SettingController::class, 'index']);
Route::post('/setting', [SettingController::class, 'store']);
Route::get('/setting/{id}', [SettingController::class, 'show']);
Route::put('/setting/{id}', [SettingController::class, 'update']);



Route::get('/pages', [PagesController::class, 'index']);
Route::post('/pages', [PagesController::class, 'store']);
Route::get('/pages/{id}', [PagesController::class, 'show']);
Route::put('/pages/{id}', [PagesController::class, 'update']);
Route::delete('/pages/{id}', [PagesController::class, 'destroy']);
Route::get('/pages/checkTitle/{title}', [PagesController::class, 'UniquePage']);


Route::get('/menu', [MenuController::class, 'index']);
Route::post('/menu', [MenuController::class, 'store']);
Route::get('/menu/{id}', [MenuController::class, 'show']);
Route::put('/menu/{id}', [MenuController::class, 'update']);
Route::delete('/menu/{id}', [MenuController::class, 'destroy']);

Route::get('/footer', [FooterMenuController::class, 'index']);
Route::post('/footer', [FooterMenuController::class, 'store']);
Route::get('/footer/{id}', [FooterMenuController::class, 'show']);
Route::put('/footer/{id}', [FooterMenuController::class, 'update']);
Route::delete('/footer/{id}', [FooterMenuController::class, 'destroy']);


Route::get('/footerContent', [FooterController::class, 'index']);
Route::post('/footerContent', [FooterController::class, 'store']);
Route::get('/footerContent/{id}', [FooterController::class, 'show']);
Route::post('/footerContent/{id}', [FooterController::class, 'update']);
Route::delete('/footerContent/{id}', [FooterController::class, 'destroy']);


//Route::resource('form-groups', FormGroupController::class);
Route::delete('/service/{id}', [ModelController::class, 'destroyService']);
Route::delete('/rate/{id}', [ModelController::class, 'destroyRate']);
Route::delete('/gallery/{id}', [ModelController::class, 'destroyGallery']);


// Index - Display a listing of form groups
Route::get('form-groups', [FormGroupController::class, 'index'])->name('form-groups.index');

// Create - Show the form for creating a new form group
Route::get('form-groups/create', [FormGroupController::class, 'create'])->name('form-groups.create');

// Store - Store a newly created form group in the database
Route::post('form-groups', [FormGroupController::class, 'store'])->name('form-groups.store');

// Show - Display the specified form group
Route::get('form-groups/{id}', [FormGroupController::class, 'show'])->name('form-groups.show');

// Edit - Show the form for editing the specified form group
Route::get('form-groups/{id}/edit', [FormGroupController::class, 'edit'])->name('form-groups.edit');

// Update - Update the specified form group in the database
Route::post('form-groups/{id}', [FormGroupController::class, 'update'])->name('form-groups.update');

// Destroy - Remove the specified form group from the database
Route::delete('form-groups/{id}', [FormGroupController::class, 'destroy'])->name('form-groups.destroy');


// Social Icons API Routes
Route::prefix('social-icons')->group(function () {
    Route::get('/', [SocialIconsController::class, 'index']);
    Route::post('/', [SocialIconsController::class, 'store']);
    Route::get('/{id}', [SocialIconsController::class, 'show']);
    Route::put('/{id}', [SocialIconsController::class, 'update']);
    Route::delete('/{id}', [SocialIconsController::class, 'destroy']);
});

// Contact Us Form API Routes
Route::get('/contacts', [ContactController::class, 'index']);
Route::get('/contacts/{id}', [ContactController::class, 'show']);
Route::post('/contacts', [ContactController::class, 'store']);
Route::put('/contacts/{id}', [ContactController::class, 'update']);
Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);