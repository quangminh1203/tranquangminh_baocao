<?php

use Illuminate\Support\Facades\Route;
// controller frontend
use App\Http\Controllers\frontend\SiteController;
// controller backend
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\BrandController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\ContactController;
use App\Http\Controllers\backend\CustomerController;
use App\Http\Controllers\backend\MenuController;
use App\Http\Controllers\backend\OrderController;
use App\Http\Controllers\backend\OrderdetailController;
use App\Http\Controllers\backend\PageController;
use App\Http\Controllers\backend\PostController;
use App\Http\Controllers\backend\ProductController;
use App\Http\Controllers\backend\SliderController;
use App\Http\Controllers\backend\TopicController;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\backend\AuthController;
use App\Http\Controllers\backend\CommentController;
use App\Http\Controllers\frontend\SiteLoginController;
use App\Http\Controllers\frontend\CartController;
use App\Http\Controllers\frontend\CheckOutController;
use App\Http\Controllers\frontend\SiteContactController;
use App\Models\Contact;

// trang người dùng
Route::get('/', [SiteController::class, 'index'])->name('site.home');
Route::get('product', [SiteController::class, 'product'])->name('site.product');
Route::get('product-sale', [SiteController::class, 'productSale'])->name('site.product-sale');
Route::get('post', [SiteController::class, 'post'])->name('site.post');
Route::get('page', [SiteController::class, 'page'])->name('site.page');
Route::get('contact', [SiteContactController::class, 'sitecontact'])->name('site.contact');

Route::post('comment', [CommentController::class, 'store'])->name('comment.store');
Route::post('reply', [CommentController::class, 'reply'])->name('comment.reply');
Route::post('replys', [CommentController::class, 'replys'])->name('comment.replys');
Route::post('dang-ky', [SiteLoginController::class, 'register'])->name('site.register');
Route::get('dang-nhap', [SiteLoginController::class, 'getlogin'])->name('site.getlogin');
Route::post('dang-nhap', [SiteLoginController::class, 'postlogin'])->name('site.postlogin');
Route::get('xac-nhan/{id}/{actived_token}', [SiteLoginController::class, 'actived'])->name('site.actived');
Route::get('xac-nhan-lai/{id}', [SiteLoginController::class, 'actived_again'])->name('site.actived_again');



Route::get('load-cart-data', [CartController::class, 'cartcount']);
Route::get('gio-hang', [CartController::class, 'cart'])->name('site.cart');
Route::get('check-out', [CheckOutController::class, 'check']);
Route::post('place-order', [CheckOutController::class, 'placeorder']);

Route::post('add-to-cart', [CartController::class, 'addcart']);
Route::post('delete-cart-item', [CartController::class, 'deletecart']);
Route::post('update-cart', [CartController::class, 'updatecart']);


Route::post('contact-admin', [SiteContactController::class, 'contactadmin']);


Route::get('profile', [SiteLoginController::class, 'profile'])->name('site.profile');
Route::get('lay-lai-mat-khau', [SiteLoginController::class, 'forget_password'])->name('site.forget_password');
Route::post('lay-lai-mat-khau', [SiteLoginController::class, 'postforget_password'])->name('site.postforget_password');
Route::get('dat-lai-mat-khau/{id}/{actived_token}', [SiteLoginController::class, 'get_password'])->name('site.get_password');
Route::post('dat-lai-mat-khau/{id}', [SiteLoginController::class, 'postget_password'])->name('site.postget_password');
Route::get('dang-xuat', [SiteLoginController::class, 'logout'])->name('site.logout');



// trang admin
Route::get('admin/login', [AuthController::class, 'getlogin'])->name('admin.getlogin');
Route::post('admin/login', [AuthController::class, 'postlogin'])->name('admin.postlogin');
Route::get('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::prefix('admin')->middleware('adminlogin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    //product
    Route::get('product/trash', [ProductController::class, 'trash'])->name('product.trash')->where('trash', '[A-Za-x]+');
    Route::resource('product', ProductController::class);
    Route::prefix('product')->group(function () {
        Route::get('status/{product}', [ProductController::class, 'status'])->name('product.status');
        Route::get('delete/{product}', [ProductController::class, 'delete'])->name('product.delete');
        Route::get('destroy/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
        Route::get('show/{product}', [ProductController::class, 'show'])->name('product.show');
        Route::get('restore/{product}', [ProductController::class, 'restore'])->name('product.restore');
        Route::post('deleteAll', [ProductController::class, 'deleteAll'])->name('product.deleteAll');
        Route::post('trashAll', [ProductController::class, 'trashAll'])->name('product.trashAll');
        Route::get('image/{product}', [ProductController::class, 'image'])->name('product.image');
        Route::post('imageDelete', [ProductController::class, 'imageDelete'])->name('product.imageDelete');
        Route::post('imageUpload/{product}', [ProductController::class, 'imageUpload'])->name('product.imageUpload');
    });
    // brand
    route::get('brand/trash', [BrandController::class, 'trash'])->name('brand.trash')->where('trash', '[A-Za-x]+');
    Route::resource('brand', BrandController::class);
    route::prefix('brand')->group(function () {
        route::get('status/{brand}', [BrandController::class, 'status'])->name('brand.status');
        route::get('delete/{brand}', [BrandController::class, 'delete'])->name('brand.delete');
        route::get('destroy/{brand}', [BrandController::class, 'destroy'])->name('brand.destroy');
        route::get('restore/{brand}', [BrandController::class, 'restore'])->name('brand.restore');
        Route::post('deleteAll', [BrandController::class, 'deleteAll'])->name('brand.deleteAll');
        Route::post('trashAll', [BrandController::class, 'trashAll'])->name('brand.trashAll');

    });
    // category
    route::get('category/trash', [CategoryController::class, 'trash'])->name('category.trash')->where('trash', '[A-Za-x]+');
    Route::resource('category', CategoryController::class);
    route::prefix('category')->group(function () {
        route::get('status/{category}', [CategoryController::class, 'status'])->name('category.status');
        route::get('delete/{category}', [CategoryController::class, 'delete'])->name('category.delete');
        route::get('destroy/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
        route::get('restore/{category}', [CategoryController::class, 'restore'])->name('category.restore');
        Route::post('deleteAll', [CategoryController::class, 'deleteAll'])->name('category.deleteAll');
        Route::post('trashAll', [CategoryController::class, 'trashAll'])->name('category.trashAll');

    });



    // contact
    route::get('contact/trash', [ContactController::class, 'trash'])->name('contact.trash')->where('trash', '[A-Za-x]+');
    Route::resource('contact', ContactController::class);
    Route::prefix('contact')->group(function () {
        Route::get('edit/{contact}', [ContactController::class, 'replay'])->name('contact.replay');
        Route::get('restore/{contact}', [ContactController::class, 'restore'])->name('contact.restore');
        Route::get('show/{contact}', [ContactController::class, 'show'])->name('contact.show');
        Route::get('delete/{contact}', [ContactController::class, 'delete'])->name('contact.delete');
        Route::get('destroy/{contact}', [ContactController::class, 'destroy'])->name('contact.destroy');
    });
    // customer
    Route::get('customer/trash', [CustomerController::class, 'trash'])->name('customer.trash')->where('trash', '[A-Za-x]+');
    Route::resource('customer', CustomerController::class);
    Route::prefix('customer')->group(function () {
        Route::get('status/{customer}', [CustomerController::class, 'status'])->name('customer.status');
        Route::get('delete/{customer}', [CustomerController::class, 'delete'])->name('customer.delete');
        Route::get('destroy/{customer}', [CustomerController::class, 'destroy'])->name('customer.destroy');
        Route::get('show/{customer}', [CustomerController::class, 'show'])->name('customer.show');
        Route::get('restore/{customer}', [CustomerController::class, 'restore'])->name('customer.restore');
    });
    // menu
    Route::get('menu/trash', [MenuController::class, 'trash'])->name('menu.trash')->where('trash', '[A-Za-x]+');
    Route::resource('menu', MenuController::class);
    Route::prefix('menu')->group(function () {
        Route::get('status/{menu}', [MenuController::class, 'status'])->name('menu.status');
        Route::get('delete/{menu}', [MenuController::class, 'delete'])->name('menu.delete');
        Route::get('destroy/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');
        Route::get('show/{menu}', [MenuController::class, 'show'])->name('menu.show');
        Route::get('restore/{menu}', [MenuController::class, 'restore'])->name('menu.restore');
        Route::post('deleteAll', [MenuController::class, 'deleteAll'])->name('menu.deleteAll');
        Route::post('trashAll', [MenuController::class, 'trashAll'])->name('menu.trashAll');
    });
    // order
    Route::get('order/new', [orderController::class, 'new'])->name('order.new')->where('new', '[A-Za-x]+');
    Route::get('order/confirm', [orderController::class, 'confirm'])->name('order.confirm')->where('confirm', '[A-Za-x]+');
    Route::get('order/package', [orderController::class, 'package'])->name('order.package')->where('package', '[A-Za-x]+');
    Route::get('order/transport', [orderController::class, 'transport'])->name('order.transport')->where('transport', '[A-Za-x]+');
    Route::get('order/delivered', [orderController::class, 'delivered'])->name('order.delivered')->where('delivered', '[A-Za-x]+'); 
    Route::get('order/trash', [orderController::class, 'trash'])->name('order.trash')->where('trash', '[A-Za-x]+');
    Route::resource('order', OrderController::class);
    Route::prefix('order')->group(function () {
        Route::get('status/{order}', [orderController::class, 'status'])->name('order.status');
        Route::get('delete/{order}', [orderController::class, 'delete'])->name('order.delete');
        Route::get('destroy/{order}', [orderController::class, 'destroy'])->name('order.destroy');
        Route::get('show/{order}', [orderController::class, 'show'])->name('order.show');
       
    });
    // orderdertail
    Route::resource('orderdetail', OrderdetailController::class);


    // page
    Route::get('page/trash', [PageController::class, 'trash'])->name('page.trash')->where('trash', '[A-Za-x]+');
    Route::resource('page', PageController::class);
    Route::prefix('page')->group(function () {
        Route::get('status/{page}', [PageController::class, 'status'])->name('page.status');
        Route::get('delete/{page}', [PageController::class, 'delete'])->name('page.delete');
        Route::get('destroy/{page}', [PageController::class, 'destroy'])->name('page.destroy');
        Route::get('show/{page}', [PageController::class, 'show'])->name('page.show');
        Route::get('restore/{page}', [PageController::class, 'restore'])->name('page.restore');
        Route::post('deleteAll', [PageController::class, 'deleteAll'])->name('page.deleteAll');
        Route::post('trashAll', [PageController::class, 'trashAll'])->name('page.trashAll');
    });
    // post
    Route::get('post/trash', [PostController::class, 'trash'])->name('post.trash')->where('trash', '[A-Za-x]+');
    Route::resource('post', PostController::class);
    Route::prefix('post')->group(function () {
        Route::get('status/{post}', [PostController::class, 'status'])->name('post.status');
        Route::get('delete/{post}', [PostController::class, 'delete'])->name('post.delete');
        Route::get('destroy/{post}', [PostController::class, 'destroy'])->name('post.destroy');
        Route::get('show/{post}', [PostController::class, 'show'])->name('post.show');
        Route::get('restore/{post}', [PostController::class, 'restore'])->name('post.restore');
        Route::post('deleteAll', [PostController::class, 'deleteAll'])->name('post.deleteAll');
        Route::post('trashAll', [PostController::class, 'trashAll'])->name('post.trashAll');
    });
    // slider
    Route::get('slider/trash', [SliderController::class, 'trash'])->name('slider.trash')->where('trash', '[A-Za-x]+');
    Route::resource('slider', SliderController::class);
    Route::prefix('slider')->group(function () {
        Route::get('status/{slider}', [SliderController::class, 'status'])->name('slider.status');
        Route::get('delete/{slider}', [SliderController::class, 'delete'])->name('slider.delete');
        Route::get('destroy/{slider}', [SliderController::class, 'destroy'])->name('slider.destroy');
        Route::get('show/{slider}', [SliderController::class, 'show'])->name('slider.show');
        Route::get('restore/{slider}', [SliderController::class, 'restore'])->name('slider.restore');
        Route::post('deleteAll', [SliderController::class, 'deleteAll'])->name('slider.deleteAll');
        Route::post('trashAll', [SliderController::class, 'trashAll'])->name('slider.trashAll');
    });
    // topic
    Route::get('topic/trash', [TopicController::class, 'trash'])->name('topic.trash')->where('trash', '[A-Za-x]+');
    Route::resource('topic', TopicController::class);
    Route::prefix('topic')->group(function () {
        Route::get('status/{topic}', [TopicController::class, 'status'])->name('topic.status');
        Route::get('delete/{topic}', [TopicController::class, 'delete'])->name('topic.delete');
        Route::get('destroy/{topic}', [TopicController::class, 'destroy'])->name('topic.destroy');
        Route::get('show/{topic}', [TopicController::class, 'show'])->name('topic.show');
        Route::get('restore/{topic}', [TopicController::class, 'restore'])->name('topic.restore');
        Route::post('deleteAll', [TopicController::class, 'deleteAll'])->name('topic.deleteAll');
        Route::post('trashAll', [TopicController::class, 'trashAll'])->name('topic.trashAll');
    });
    // user
    Route::get('user/trash', [UserController::class, 'trash'])->name('user.trash')->where('trash', '[A-Za-x]+');
    Route::resource('user', UserController::class);
    Route::prefix('user')->group(function () {
        Route::get('status/{user}', [UserController::class, 'status'])->name('user.status');
        Route::get('delete/{user}', [UserController::class, 'delete'])->name('user.delete');
        Route::get('destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::get('show/{user}', [UserController::class, 'show'])->name('user.show');
        Route::get('restore/{user}', [UserController::class, 'restore'])->name('user.restore');
        Route::post('deleteAll', [UserController::class, 'deleteAll'])->name('user.deleteAll');
        Route::post('trashAll', [UserController::class, 'trashAll'])->name('user.trashAll');
    });
   
});

// site - end
Route::get('{slug}', [SiteController::class, 'index'])->name('slug.home');
