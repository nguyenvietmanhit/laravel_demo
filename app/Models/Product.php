<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
//    use HasFactory;
    // Đặt tên bảng trong Laravel ở dạng số nhiều thì tên class tự mapping đc: Product -> bảng products
    protected $table = 'products';
    protected $primaryKey = 'id';
    //Cần thêm thuộc tính guarded để khi insert vào bảng bỏ qua name này
    protected $guarded = ['_token'];
}
