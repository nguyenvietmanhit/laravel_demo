<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Hiển thị ra form thêm mới
    public function create() {
        // Gọi view để hiển thị
        // Tạo cấu trúc views như sau:
        //resources/views/
                          //products
                                     //index.blade.php: View danh sách sản phẩm
                                     //create.blade.php: View tạo mới sản phẩm
                                     //edit.blade.php: View sửa sản phẩm
                          //layouts: main.blade.php: File layout chính của ứng dụng
        return view('products/create');
    }

    //Xử lý save vào db
    public function createSave() {
        echo "createSave";
    }
}
