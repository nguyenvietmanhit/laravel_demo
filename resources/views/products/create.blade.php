{{--create.blade.php--}}
@extends('layouts/main')

{{--Nếu giá trị động ngắn thì viết 1 dòng như sau--}}
@section('page_title', 'Trang thêm mới sản phẩm')
{{--Nếu giá trị mà nhiều, viết như sau--}}
@section('content')
    <h1>Form thêm mới sản phẩm</h1>
    <form method="post" action="{{ url('product/createSave') }}">
{{--    Cần đặt name của input trùng với tên trường để khi xử lý lưu vào db tiện    --}}
{{--     Mặc định form của Laravel bắt buộc phải khai báo token thì mới submit đc form
   -> chống lỗi bảo mật CSRF Token -> lỗi giả mạo người dùng --}}
{{--        <input type="hidden" name="_token" value="{{ csrf_token() }}" />--}}
        @csrf

        Tên sản phẩm:
        <input type="text" name="name" value="" /> <br />
        Giá sản phẩm:
        <input type="text" name="price" value="" /> <br />
        Chi tiết sản phẩm:
        <textarea name="content"></textarea> <br />

        <input type="submit" name="submit" value="Lưu" />
    </form>
@endsection


