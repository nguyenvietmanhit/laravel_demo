@extends('layouts/main')
@section('page_title', 'DS SP')
@section('content')
    <a href="{{ url('product/create') }}">
        Thêm mới sp
    </a>
    <h1>Danh sách sản phẩm</h1>

    <table border="1" cellspacing="0" cellpadding="8">
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Price</th>
            <th>Content</th>
            <th>Created_at</th>
            <th>Updated_at</th>
            <th></th>
        </tr>
        @foreach($products AS $product)
            <tr>
{{--                Có thể sử dụng cú pháp object hoặc mảng để lấy giá trị --}}
                <td>{{ $product->id }}</td>
{{--                <td>{{ $product['id'] }}</td>--}}
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price) }} đ</td>
{{--             Dùng cú pháp echo plaintext, để hiển thị đc cả HTML   --}}
                <td>{!! $product->content !!}</td>
                <td>{{ date('d-m-Y H:i:s', strtotime($product->created_at)) }}</td>
                <td>{{ date('d-m-Y H:i:s', strtotime($product->updated_at)) }}</td>
                <td>
                    <a href="{{ url("suasp/" . $product->id) }}">Sửa</a> &nbsp;
                    <a href="{{ url("xoasp/" . $product->id) }}" onclick="return confirm('Chắc chắn xóa?')">Xóa</a>
                </td>
            </tr>
        @endforeach
    </table>
{{--    Hiển thi phân trang theo cơ chế Laravel --}}
    {{ $products->links() }}
@endsection
