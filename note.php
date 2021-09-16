<?php
/**
 * 1 - Composer
 * + Là 1 công cụ quản lý thư viên -> các framework/CMS luôn luôn cần các thư viện
 * từ bên thứ 3 thì mới có thể chạy đc
 * + Các thư viện trong Laravel -> vendor, khai báo của các thư viện: composer.json
 * + Nếu ko dùng composer -> Laravel có chạy đc ko ? Có thể chạy ddc -> làm thủ công tải các thư viện
 * thủ công về local -> setup .... Giả sử cài đặt thủ công -> 1 thư viện nào đó có cập nhật bản mới
 * -> tải lại thư viện mới nhất đó -> update lại vào Laravel ? làm thế nào biết đc thư viện đó
 * có bản mới -> bất khả thi vì rất nhiều thư viện
 * -> Composer: quản lý thư viện 1 cách tự động -> dùng các lệnh của composer để cài mới / update
 * thư viện 1 cách tự động
 * composer require: cài mới thư viện
 * composer update: update thư viện
 * composer install: đọc file composer.lock nếu có để cài đặt các thư viện, nếu file này chưa có
 * thì sẽ đọc file composer.json
 * -> Composer -> NPM - Node Package Management
 *
 * 2 - Laravel
 * + Framework viết bằng PHP, cần có kiến thức về PHP cơ bản, OOP và MVC
 * + Framework: Zend, Cake, Code Igniter, Yii ... -> Laravel thông dụng nhất -> dễ học, tài nguyên
 * phong phú
 * + CMS: Wordpress, Zoomla, Magento, Drupal
 * + Hướng tiếp cận Framework thích hơn CMS
 * / Cấu trúc thư mục của Laravel cần quan tâm với beginner:
 * + app/Http
            /Controllers: nơi khai báo các controller của Laravel: C
 *          /Middleware: giống tường lửa của Window: là nơi để bắt các request vào hệ thống
 *                       trước khi tới controller
 * + app/Models: khai báo các model của Laravel, M
 * + resources/views: V, nơi khai báo các view: .blade
 * + public: khai báo các file css, js, images, font awesome, ckeditor, ckfinder trong
 * thư mục public này -> assets MVC thuần
 *        /index.php: file index gốc của ứng dụng
 * + routes:
 *         /web.php: nơi khai báo các url rewrite cho ứng dụng -> .htaccess MVC thuần
 * + .env:  enviroiment -> file môi trường -> cài đặt thông tin môi trương tại đây: database
 * , mail
 *
 * + Code ứng dụng CRUD Create Read Update Delete sản phẩm bằng Laravel
 * / Tạo CSDL: php1220e2_laravel
 * Bảng products: id, name, price, created_at, updated_at
 * -> tạo như thế nào? sử dụng PHPMyadmin -> tạo bằng giao diện/viết câu truy vấn SQL
 * -> Laravel dùng cơ chế migration để tạo bảng, tạo db vẫn tạo thủ công
 * -> 1 dự án có 5 member, trên mỗi máy của từng member đều phải có 1 db riêng
 * ->khởi tạo dự án, PM tọa 1 db trước -> .sql cho member -> import vào CSDL
 * -> tình huống: cấu trúc bảng bị thay đổi theo từng chức năng -> An cần thêm 1 trường
 * trong 1 bảng -> báo lên team -> An export file .sql mới nhất -> gửi member còn lại
 * ->import vào DB
 * -> dùng cơ chế migrate để quản lý tự động các thay đổi -> mỗi lần DB thay đổi -> chạy
 * lệnh -> update tự động
 * Lệnh artisan của Laravel: học Laravel mà ko biết artisan -> thiếu sót lớn
 * ->artisan.php
 * - Tạo CSDL thủ công bằng câu truy vấn:
 *
 * -> Sử dụng lệnh artisan để tạo cấu trúc bảng:
 * - Tạo file migrate chứa cấu trúc của bảng products:
 * php artisan make:migration create_table_product --create=products
 * -> vào database/migrations/2021_09_09_132217_create_table_product
 * - Cần cấu hình thông tin kết nối CSDL tại file .env
 *
 * / Code chức năng thêm mới sản phẩm:
 * - Cần tạo route/url để truy cập vào: routes/web.php
 * - Tạo controller quản lý sản phẩm, tên bắt buộc phải theo form sau: ProductController
 * -> cần tạo controller chuẩn Laravel -> dùng artisan để tạo controller
 * php artisan make:controller ProductController
 */
