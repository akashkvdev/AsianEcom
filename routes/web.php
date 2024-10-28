<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ProductSubCategoryController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TempImageController;
use App\Models\ShippingCharge;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

// Route::get('/', function () {
//     return view('welcome');
// });
// ===========================Common Users Sections Start=================================== 
Route::get('/',[FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[ShopController::class,'index'])->name('front.shop');

Route::get('/product/{slug}',[ShopController::class,'product'])->name('front.product');

Route::get('admin/login',[AdminController::class,'index'])->name('admin.login');

Route::get('/cart',[CartController::class,'cart'])->name('front.cart');
Route::post('/add-to-cart',[CartController::class,'addToCart'])->name('front.addToCart');
Route::post('/update-cart',[CartController::class,'updateCart'])->name('front.updateCart');
Route::post('/delete-item',[CartController::class,'deleteItem'])->name('front.deleteItem.from.cart');

Route::get('/check-out',[CartController::class,'checkout'])->name('front.checkout');
Route::post('/process-check-out',[CartController::class,'processCheckout'])->name('front.processCheckout');
Route::get('/thank-you/{orderId}',[CartController::class,'thankyou'])->name('front.thankyou');
Route::post('/get-order-summery',[CartController::class,'getOrderSummery'])->name('front.getOrderSummery');



Route::group(['prefix'=>'account'],function(){
    Route::group(['middleware'=>'guest'],function(){
        Route::get('/register',[AuthController::class,'register'])->name('account.register');
        
        Route::post('/processregister',[AuthController::class,'processRegister'])->name('account.processRegister');
        
        Route::get('/login',[AuthController::class,'login'])->name('account.login');
        Route::post('/authenticate',[AuthController::class,'authenticate'])->name('account.authenticate');
    });
    Route::group(['middleware'=>'auth'],function(){
        Route::get('/profile',[AuthController::class,'profile'])->name('account.profile');
        Route::get('/logout',[AuthController::class,'logout'])->name('account.logout');
    });
});
// ===========================Common Users Sections END=================================== 

// =======================Admin Section==========================
Route::group(['prefix'=>'admin'],function(){
    Route::group(['middleware'=>'admin.guest'],function(){
        Route::get('login',[AdminController::class,'index'])->name('admin.login');
        Route::post('authenticate',[AdminController::class,'login'])->name('admin.authenticate');
        

    });

    Route::group(['middleware'=>'admin.auth'],function(){
        Route::get('/dashboard',[AdminController::class,'dashBoard'])->name('admin.dashboard');
        Route::get('/logout',[AdminController::class,'logout'])->name('admin.logout');

        // Category Routes
        Route::get('/categories',[CategoryController::class,'index'])->name('categories.index');
        Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create');
        Route::post('/categories',[CategoryController::class,'store'])->name('categories.store');
        Route::get('/categories/{category}/edit',[CategoryController::class,'edit'])->name('categories.edit');
        Route::put('/categories/{category}',[CategoryController::class,'update'])->name('categories.update');
        Route::delete('/categories/{category}',[CategoryController::class,'destroy'])->name('categories.delete');
        
        // Sub Category Routes
        Route::get('/sub-categories',[SubCategoryController::class,'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create',[SubCategoryController::class,'create'])->name('sub-categories.create');
        Route::post('/sub-categories',[SubCategoryController::class,'store'])->name('sub-categories.store');

        Route::get('/sub-categories/{subCategory}/edit',[SubCategoryController::class,'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{category}',[SubCategoryController::class,'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{category}',[SubCategoryController::class,'destroy'])->name('sub-categories.delete');

        
        // Brands Routes 
        Route::get('/brand',[BrandController::class,'index'])->name('brands.index');
        Route::get('/brand/create',[BrandController::class,'create'])->name('brands.create');
        Route::post('/brand',[BrandController::class,'store'])->name('brands.store');

        Route::get('/brands/{brand}/edit',[BrandController::class,'edit'])->name('brands.edit');
        Route::put('/brand/{brand}',[BrandController::class,'update'])->name('brands.update');
        Route::delete('/brand/{category}',[BrandController::class,'destroy'])->name('brands.delete');
        
        // Products Routes
        Route::get('/products/create',[ProductController::class,'create'])->name('products.create');
        Route::get('/product-subcategories',[ProductSubCategoryController::class,'index'])->name('product-subcategories.index');
        Route::post('/products/create',[ProductController::class,'store'])->name('products.store');
        Route::get('/products',[ProductController::class,'index'])->name('products.index');


        Route::get('/get-products',[ProductController::class,'getproducts'])->name('products.getproducts');
        
        Route::get('/products/{product}/edit',[ProductController::class,'edit'])->name('products.edit');
        Route::put('/products/{products}',[ProductController::class,'update'])->name('products.update');

        Route::post('/products-images/update',[ProductImageController::class,'update'])->name('products-image.update');
        // product delete
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.delete');


        // product Image Deleted when delte button click
        
        
        
        // Temp Image create
        Route::post('/upload-temp-image',[TempImageController::class,'create'])->name('temp-images.create');
        Route::delete('/products-images/delete',[ProductImageController::class,'destroy'])->name('products-image.delete');

        // Shipping Routes
        Route::get('/shipping',[ShippingController::class,'index'])->name('shipping.index');
        Route::get('/shiping/create',[ShippingController::class,'create'])->name('shipping.create');
        Route::post('/shiping/store',[ShippingController::class,'store'])->name('shipping.store');
        Route::get('/shiping/{shipping}/edit',[ShippingController::class,'edit'])->name('shiping.edit');
        Route::put('/shiping/{shipingId}',[ShippingController::class,'update'])->name('shiping.update');

        Route::delete('/shipping/{id}', [ShippingController::class, 'destroy'])->name('shipping.delete');

        Route::get('/getSlug',function(Request $request){
            $slug='';
            if(!empty($request->title)){
                $slug=Str::slug($request->title);
            }

            return response()->json([
                'status'=>true,
                'slug'=>$slug
            ]);
        })->name('getSlug');


    });
});


// =======================Admin Section=END=========================
