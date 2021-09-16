<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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
// Khai báo route/url cho chức năng trên web

Route::get('/', function () {
    return view('welcome');
});

//http://localhost/laravel_demo/public/test
Route::get('/test', function () {
    echo "Test route";
});
// Route thêm mới sản phẩm:
// Laravel chia làm nhiều phương thức cho route:
// GET: hiển thị
// POST: thêm mới bản ghi vào CSDL
// PUT/PATCH: cập nhật bản ghi
// DELETE: xóa bản ghi
// -> API RESTFULL -> bảo mật chức năng hơn
// index.php?controller=product&action=delete&id=3
// -> API RESUT -> DELETE -> biết URL xóa cũng ko thể xóa đc!
// Cần 2 route cho chức năng thêm mới: GET cho hiển thị ra form thêm mới, POST xử lý thêm
//mới sản phẩm
//http://localhost/laravel_demo/public/product/create
// Route hiển thị form thêm mới
Route::get('product/create', [ProductController::class, 'create']);
// Route thêm mới sp vào db
Route::post('product/createSave', [ProductController::class, 'createSave']);
