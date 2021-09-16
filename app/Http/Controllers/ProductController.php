<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
    // Laravel có sẵn cơ chế đẩy object của class Request, dùng để lấy các giá trị của POST, GET ...
    public function createSave(Request $request) {
//        dump($request->all());
//        echo "createSave";
        // - Validate form: search: rule validate Laravel
        $rules = [
            'name' => ['required', 'min:2', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'content' => ['required']
        ];
        $messages = [
            'name.required' => 'Tên sp ko đc để trống',
            'name.min' => 'Tên sp ít nhất 2 ký tự',
            'name.max' => 'Tên sp tối đa 100 ký tự',
            'price.required' => 'Giá sp phải nhập',
            'price.numeric' => 'Giá sp phải là số',
            'price.min' => 'Giá sp nhỏ nhất là 0',
            'content.required' => 'Chi tiết sp phải nhập'
        ];

        $request->validate($rules, $messages);
        // Code phía sau chỉ chạy khi pass validate
        // - Gọi model để thêm bản ghi vào CSDL, nếu chưa có model cần tạo model: app/Models
        // Dùng artisan tạo model chuẩn Laravel: php artisan make:model Product
        $requests = $request->all();
//        dump($requests);
//        die;
        // Insert vào bảng product dựa vào mảng input lấy từ form truyền lên, với 1 điều kiện là
        // name của input form phải trùng với tên trường trong bảng
        $product = Product::create($requests);
        if ($product instanceof Product) {
            // Tạo session chứa message thành công
            session()->put('success', 'Thêm sp thành công');
            // Chuyển hướng về trang danh sách
            return redirect('products');
        } else {
            session()->put('error', 'Thêm sp thất bại');
            return redirect('product/create');
        }
    }

    public function index() {
        // Dùng model lấy tất cả bản ghi: Laravel có 2 cơ chế truy vấn: Eloquent, Query Builder
//        $products = Product::all();
//        dump($products);
        // Sử dụng phân trang để tránh load tất cả bản ghi
        $products = Product::paginate(2);
//        dump($products);
        return view('products/index', [
            'products' => $products
        ]);
    }


    public function edit($id) {
//        dump($id);
        // gọi model lấy sp theo id
//        $product = Product::find($id);
//        $product = Product::where('id', $id);
//        dump($product);
        // Nếu ko tìm thấy bản ghi thì trả về trang Not found
        $product = Product::findOrFail($id);
        return view('products/edit', [
            'product' => $product
        ]);
    }

    public function editSave(Request $request, $id) {
        // - Lấy sp tương ứng với id
        $product = Product::findOrFail($id);

//        dump($id);
        // - Validate form: giống create
        // - Gọi model để update bản ghi
        // Lấy mảng các input gửi lên
        $requests = $request->all();
        $is_update = $product->update($requests);
        if ($is_update) {
            session()->put('success', 'Cập nhật sp thành công');
            return redirect('products');
        } else {
            session()->put('error', 'Cập nhật sp thất bại');
            return redirect('product/edit/' . $id);
        }

    }
}
