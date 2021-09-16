{{--main.blade.php--}}
{{--
View trong Laravel dùng Template Engine: Blade
-> giúp code PHP dễ đọc hơn so với viết code PHP thuần
Template: Twig
Blade bản chất là 1 file .php nên viết code PHP bình thường
- Khi dựng layout Laravel, cần xác định các tham số động giống như MVC thuần
->sử dụng từ khóa yield
- Tạo thư mục css, js đế nhúng
public/
      /css: style.css
      /js: script.js
- echo trong blade sử dụng cú pháp {{  }}, giúp tránh lỗi bảo mật XSS
--}}
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8" />
        <title>@yield('page_title')</title>
{{--    Sử dụng code PHP ko theo format Blade    --}}
{{--        <link rel="stylesheet" href="<?php echo asset('css/style.css')?>" />--}}
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    </head>
    <body>
        <div class="header">
            <h1>Đây là header</h1>
        </div>
        <div class="main">
            @yield('content')
        </div>
        <div class="footer">
            <h1>Đây là footer</h1>
        </div>
        <script src="{{ asset('js/script.js') }}"></script>
    </body>
</html>
