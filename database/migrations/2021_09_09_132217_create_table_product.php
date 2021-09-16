<?php
// Laravel sử dụng cơ chế namespace để tự động load class mỗi khi đc gọi
// -> ko thấy require_once như MVC thuần
// Illuminate\Database\Migrations\Migration -> namespace tới class Migration
// PHPStorm hỗ trợ auto thêm namespace khi gọi class
// use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
//            $table->id();
//            $table->timestamps();
            // Tạo các trường bằng code của Laravel
            // Bảng products: id, name, price, content, created_at, updated_at
            $table->increments('id');// tạo id là khóa chính tăng dần
            $table->string('name', 100);
            $table->integer('price');
            $table->text('content');
            // Tự sinh created_at và updated_at theo đúng cơ chế
            // created_at: là thời gian hiện tại khi thêm bản ghi mới vào bảng
            // updated_at: thời gian hiện tại khi 1 bản ghi đc update
            $table->timestamps();
            // Xóa các file migrate mặc định của Laravel
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
