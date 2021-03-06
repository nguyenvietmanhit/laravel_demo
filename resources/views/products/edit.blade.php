{{--edit.blade.php--}}
@extends('layouts/main')

{{--Nếu giá trị động ngắn thì viết 1 dòng như sau--}}
@section('page_title', 'Sửa sản phẩm')
{{--Nếu giá trị mà nhiều, viết như sau--}}
@section('content')
    <h1>Form sửa sản phẩm</h1>
    <form method="post" action="{{ url('product/editSave/' . $product->id) }}">
        {{--    Cần đặt name của input trùng với tên trường để khi xử lý lưu vào db tiện    --}}
        {{--     Mặc định form của Laravel bắt buộc phải khai báo token thì mới submit đc form
           -> chống lỗi bảo mật CSRF Token -> lỗi giả mạo người dùng --}}
        {{--        <input type="hidden" name="_token" value="{{ csrf_token() }}" />--}}
        @csrf
{{--    Do route của action đang là PUT, trong khi form HTML chỉ hỗ trợ 2 route GET và POST,
    nên Laravel quy định cơ chế riêng cho cơ chế PUT này--}}
        @method('PUT')

        Tên sản phẩm:
        <input type="text" name="name" value="{{ $product->name }}" /> <br />
        Giá sản phẩm:
        <input type="text" name="price" value="{{ $product->price }}" /> <br />
        Chi tiết sản phẩm:
        <textarea name="content">{!! $product->content !!}</textarea> <br />
        <input type="submit" name="submit" value="Cập nhật" />
    </form>
@endsection


